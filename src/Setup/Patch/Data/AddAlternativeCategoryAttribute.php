<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use WeProvide\AlternativeCategory\Model\AlternativeCategoryAttributeInterface;
use Zend_Validate_Exception;

/**
 * Responsible for adding the "Alternative Category" attribute to the "Category" entity
 *
 * @since 1.0.0
 */
class AddAlternativeCategoryAttribute implements DataPatchInterface, PatchRevertableInterface
{
    /** @var ModuleDataSetupInterface */
    protected $moduleDataSetup;

    /** @var EavSetupFactory */
    protected $eavSetupFactory;

    /**
     * AddAlternativeCategoryAttribute constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Responsible for adding the "Alternative Category" attribute to the "Category" entity
     *
     * @throws LocalizedException|Zend_Validate_Exception thrown when something goes wrong whilst adding the attribute
     */
    public function apply(): void
    {
        $this->getEavSetup()->addAttribute(Category::ENTITY, AlternativeCategoryAttributeInterface::ATTRIBUTE_CODE, [
            'type' => 'text',
            'label' => 'Alternative Category',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'required' => false
        ]);
    }

    /** @inheritDoc */
    public function revert(): void
    {
        $this->getEavSetup()->removeAttribute(Category::ENTITY, AlternativeCategoryAttributeInterface::ATTRIBUTE_CODE);
    }

    /**
     * Get a newly created {@code EavSetup} using the injected factory
     *
     * @return EavSetup the {@code EavSetup} that was instantiated
     */
    protected function getEavSetup(): EavSetup
    {
        return $this->eavSetupFactory->create([
            'setup' => $this->moduleDataSetup
        ]);
    }

    /** @inheritDoc */
    public function getAliases(): array
    {
        return [];
    }

    /** @inheritDoc */
    public static function getDependencies(): array
    {
        return [];
    }
}
