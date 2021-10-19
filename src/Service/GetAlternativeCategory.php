<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Service;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use WeProvide\AlternativeCategory\Service\Api\GetAlternativeCategoryInterface;

class GetAlternativeCategory implements GetAlternativeCategoryInterface
{
    /** @var CategoryRepositoryInterface */
    protected $categoryRepository;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * GetAlternativeCategory constructor
     *
     * @param CategoryRepositoryInterface $categoryRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        LoggerInterface $logger
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->logger = $logger;
    }

    /** @inheritDoc */
    public function execute(CategoryInterface $category): ?CategoryInterface
    {
        if ($category instanceof DataObject) {
            $alternativeCategoryId = $category->getData('alternative_category');

            if ($alternativeCategoryId === null) {
                return null;
            }

            if (!is_int($alternativeCategoryId) && !is_numeric($alternativeCategoryId)) {
                // should never be the case but can happen if direct changes to the database are made

                $this->logger->warning(sprintf(
                    "The configured \"alternative_category\" for %d is not an integer nor a numeric string (%s)",
                    $category->getId(), $alternativeCategoryId
                ));

                return null;
            }

            try {
                return $this->categoryRepository->get($alternativeCategoryId);
            } catch (NoSuchEntityException $exception) {
                // occurs when a category is configured as "alternative_category" and deleted afterwards
                return null;
            }
        }

        // implementing the "CategoryInterface" does not guarantee us that it implements "getData(string)"
        return null;
    }
}
