<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Service\Api;

use Magento\Catalog\Api\Data\CategoryInterface;

/**
 * Responsible for retrieving the alternative category of a category
 *
 * @since 1.1.0
 * @api
 */
interface GetAlternativeCategoryInterface
{
    /**
     * Get the alternative category of a category
     *
     * @param CategoryInterface $category the category to get the alternative category of
     * @return CategoryInterface|null the alternative category, null if no alternative category was configured
     */
    public function execute(CategoryInterface $category): ?CategoryInterface;
}
