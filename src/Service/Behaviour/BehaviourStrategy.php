<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Service\Behaviour;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Responsible for acting upon a given action / request with the freedom of continuing like normal
 * or returning a modified action depending on the desired behaviour
 *
 * @since 1.0.0
 */
interface BehaviourStrategy
{
    /** @var string */
    public const REWRITE = 'rewrite';

    /** @var string */
    public const PERMANENT_REDIRECT = 'permanent_redirect';

    /** @var string */
    public const TEMPORARY_REDIRECT = 'temporary_redirect';

    /**
     * Apply the behaviour strategy on a given action / request
     *
     * @param ActionInterface $action the action to apply the behaviour on
     * @param RequestInterface $request the request to apply the behaviour on
     * @param int $alternativeCategoryId the alternative category ID to use
     * @return ActionInterface the original action or a new one, depending on the desired behaviour
     */
    public function execute(ActionInterface $action, RequestInterface $request, int $alternativeCategoryId): ActionInterface;
}
