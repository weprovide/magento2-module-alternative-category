<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Service\Behaviour;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use WeProvide\AlternativeCategory\Service\Config;

/**
 * Responsible for applying the proper / configured behaviour strategy to a given action / request
 *
 * @since 1.0.0
 *
 * @see BehaviourStrategy
 */
class BehaviourContext
{
    /** @var Config */
    private $config;

    /** @var BehaviourStrategyFactory */
    protected $behaviourStrategyFactory;

    /**
     * BehaviourContext constructor
     *
     * @param Config $config
     * @param BehaviourStrategyFactory $behaviourStrategyFactory
     */
    public function __construct(
        Config $config,
        BehaviourStrategyFactory $behaviourStrategyFactory
    ) {
        $this->config = $config;
        $this->behaviourStrategyFactory = $behaviourStrategyFactory;
    }

    /**
     * Apple the proper / configured behaviour strategy to a given action / request
     *
     * @param ActionInterface $action the action to apply the behaviour strategy on
     * @param RequestInterface $request the request to apply the behaviour strategy on
     * @param int $alternativeCategoryId the alternative category ID to use within the behaviour being executed
     * @return ActionInterface the original action or a new one, depending on the behaviour strategy being executed
     */
    public function execute(ActionInterface $action, RequestInterface $request, int $alternativeCategoryId): ActionInterface
    {
        $strategy = $this->behaviourStrategyFactory->create($this->config->getBehaviour());
        return $strategy->execute($action, $request, $alternativeCategoryId);
    }
}
