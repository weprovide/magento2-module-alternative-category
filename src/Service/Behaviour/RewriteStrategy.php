<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Service\Behaviour;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Responsible for rewriting the request by overriding the ID which was passed to the request rather than redirecting the whole request
 *
 * @since 1.0.0
 */
class RewriteStrategy implements BehaviourStrategy
{
    /** @inheritDoc */
    public function execute(ActionInterface $action, RequestInterface $request, int $alternativeCategoryId): ActionInterface
    {
        $request->setParams(array_merge($request->getParams(), [
            'id' => $alternativeCategoryId
        ]));

        return $action;
    }
}
