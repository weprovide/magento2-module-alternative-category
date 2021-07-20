<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Plugin;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Controller\Category\View as CategoryViewController;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Router\Base as StandardRouter;
use Magento\Framework\Exception\NoSuchEntityException;
use WeProvide\AlternativeCategory\Model\AlternativeCategoryAttributeInterface;
use WeProvide\AlternativeCategory\Service\Behaviour\BehaviourContext;
use WeProvide\AlternativeCategory\Service\Config;

/**
 * Responsible for applying the proper / configured behaviour on the matched action / request of the "standard" router
 *
 * @see https://devdocs.magento.com/guides/v2.4/extension-dev-guide/routing.html#standard-router
 * @since 1.0.0
 */
class ReplaceCategoryIdOnAlternative
{
    /** @var Config */
    protected $config;

    /** @var CategoryRepositoryInterface */
    protected $categoryRepository;

    /** @var BehaviourContext */
    protected $behaviourContext;

    /**
     * ReplaceCategoryIdOnAlternative constructor
     *
     * @param Config $config
     * @param CategoryRepositoryInterface $categoryRepository
     * @param BehaviourContext $behaviourContext
     */
    public function __construct(
        Config $config,
        CategoryRepositoryInterface $categoryRepository,
        BehaviourContext $behaviourContext
    ) {
        $this->config = $config;
        $this->categoryRepository = $categoryRepository;
        $this->behaviourContext = $behaviourContext;
    }

    /**
     * @param StandardRouter $subject
     * @param ActionInterface|null $result
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function afterMatch(StandardRouter $subject, ?ActionInterface $result, RequestInterface $request): ?ActionInterface
    {
        if ($this->config->isEnabled() && $result instanceof CategoryViewController) {
            $categoryId = $request->getParam('id');

            if ($categoryId !== null && is_numeric($categoryId)) {
                $alternativeId = $this->getAlternativeCategory((int) $categoryId);

                if ($alternativeId !== null) {
                    return $this->behaviourContext->execute($result, $request, $alternativeId);
                }
            }
        }

        return $result;
    }

    /**
     * Get the "Alternative Category" of a category
     *
     * @param int $categoryId the ID of the category to get the "Alternative Category" from
     * @return int|null the ID of the "Alternative Category", null if none is configured
     *
     */
    protected function getAlternativeCategory(int $categoryId): ?int
    {
        /*
         * Using a collection to only select the "Alternative Category" attribute might be more performant in terms
         * of execution time and memory usage, but it is also less neat and readable.
         *
         * I decided to prioritize readability over a premature optimization. It can always (and easily) be
         * refactored if the repository turns out to be a performance bottleneck.
         */

        try {
            $category = $this->categoryRepository->get($categoryId);
            $alternativeCategory = $category->getCustomAttribute(AlternativeCategoryAttributeInterface::ATTRIBUTE_CODE);
            return $alternativeCategory ? (int) $alternativeCategory->getValue() : null;
        } catch (NoSuchEntityException $exception) {
            return null;
        }
    }
}
