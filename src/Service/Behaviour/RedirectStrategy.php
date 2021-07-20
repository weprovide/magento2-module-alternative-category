<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Service\Behaviour;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect as RedirectResult;
use Magento\Framework\Controller\Result\RedirectFactory as RedirectResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Responsible for redirecting the request using a Permanent (301) or Temporary (302) redirect
 *
 * @since 1.0.0
 */
class RedirectStrategy implements BehaviourStrategy
{
    /** @var RedirectResultFactory */
    protected $redirectResultFactory;

    /** @var CategoryRepositoryInterface */
    protected $categoryRepository;

    /** @var int */
    protected $redirectType;

    /**
     * RedirectStrategy constructor
     *
     * @param RedirectResultFactory $redirectResultFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param int $redirectType
     */
    public function __construct(
        RedirectResultFactory $redirectResultFactory,
        CategoryRepositoryInterface $categoryRepository,
        int $redirectType
    ) {
        $this->redirectResultFactory = $redirectResultFactory;
        $this->categoryRepository = $categoryRepository;
        $this->redirectType = $redirectType;
    }

    /** @inheritDoc */
    public function execute(ActionInterface $action, RequestInterface $request, int $alternativeCategoryId): ActionInterface
    {
        try {
            $category = $this->categoryRepository->get($alternativeCategoryId);

            $redirect = $this->redirectResultFactory->create();
            $redirect->setUrl($category->getUrl());
            $redirect->setHttpResponseCode($this->redirectType);

            /*
             * I decided to use an anonymous class because we can't nest classes in PHP (such as "private class ... implements ActionInterface"). I didn't
             * want to create a separate class for this and encapsulate everything related to this strategy within its class.
             */

            return (new class($redirect) implements ActionInterface {

                /** @var RedirectResult */
                private $redirect;

                /**
                 * Constructor
                 *
                 * @param RedirectResult $redirect
                 */
                public function __construct(RedirectResult $redirect)
                {
                    $this->redirect = $redirect;
                }

                /** @inheritDoc */
                public function execute(): ResultInterface
                {
                    return $this->redirect;
                }
            });
        } catch (NoSuchEntityException $exception) {
            return $action;
        }
    }
}
