<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Service\Behaviour;

use Magento\Framework\ObjectManagerInterface;
use Magento\UrlRewrite\Model\OptionProvider as RedirectTypeOptionProvider;

/**
 * Responsible for instantiating the proper concrete behaviour strategy implementation for a given "identifier" of that behaviour strategy
 *
 * @since 1.0.0
 *
 * @see BehaviourStrategy::REWRITE
 * @see BehaviourStrategy::PERMANENT_REDIRECT
 * @see BehaviourStrategy::TEMPORARY_REDIRECT
 */
class BehaviourStrategyFactory
{
    /** @var ObjectManagerInterface */
    protected $objectManager;

    /**
     * BehaviourStrategyFactory constructor
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create an instance of the proper concrete behaviour strategy implementation for the given "identifier" of that behaviour strategy
     *
     * @param string $strategy the "identifier" of the behaviour strategy to instantiate the concrete implementation for
     * @return BehaviourStrategy the concrete implementation for the given "identifier" of the behaviour strategy
     */
    public function create(string $strategy): BehaviourStrategy
    {
        switch (strtolower($strategy)) {
            case BehaviourStrategy::REWRITE:
                return $this->objectManager->create(RewriteStrategy::class);
            case BehaviourStrategy::PERMANENT_REDIRECT:
                return $this->objectManager->create(RedirectStrategy::class, ['redirectType' => RedirectTypeOptionProvider::PERMANENT]);
            case BehaviourStrategy::TEMPORARY_REDIRECT:
                return $this->objectManager->create(RedirectStrategy::class, ['redirectType' => RedirectTypeOptionProvider::TEMPORARY]);
        }

        throw new \RuntimeException('Unsupported Behaviour');
    }
}
