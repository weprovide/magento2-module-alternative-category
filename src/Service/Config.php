<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Responsible for providing easy access to the System Configuration ("system.xml") through the scope config of Magento
 *
 * @see ScopeConfigInterface
 */
class Config
{
    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /**
     * Config constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check if the module is enabled or not
     *
     * @return bool true if the module is enabled, false otherwise
     */
    public function isEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue('alternative_category/general/enable', ScopeInterface::SCOPE_STORE);
    }

    public function getBehaviour(): string
    {
        return (string) $this->scopeConfig->getValue('alternative_category/general/behaviour', ScopeInterface::SCOPE_STORE);
    }
}
