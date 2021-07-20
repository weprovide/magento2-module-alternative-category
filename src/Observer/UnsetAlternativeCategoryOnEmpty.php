<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Observer;

use Magento\Catalog\Model\Category;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use WeProvide\AlternativeCategory\Model\AlternativeCategoryAttributeInterface;

/**
 * Responsible for removing the value from the category for the "Alternative Category" attribute if it was unset / cleared from the UI
 *
 * @since 1.0.0
 */
class UnsetAlternativeCategoryOnEmpty implements ObserverInterface
{

    /** @inheritDoc */
    public function execute(Observer $observer)
    {
        /** @var RequestInterface $request */
        $request = $observer->getEvent()->getData('request');

        /** @var Category $category */
        $category = $observer->getEvent()->getData('category');

        if (empty($request->getParam(AlternativeCategoryAttributeInterface::ATTRIBUTE_CODE))) {
            $category->setData(AlternativeCategoryAttributeInterface::ATTRIBUTE_CODE);
        }
    }
}
