<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use WeProvide\AlternativeCategory\Service\Behaviour\BehaviourStrategy;

/**
 * Responsible for making the various behaviour strategies available for selection in the System Configuration
 *
 * @since 1.0.0
 *
 * @see BehaviourStrategy
 */
class Behaviour implements OptionSourceInterface
{
    /** @inheritDoc */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => BehaviourStrategy::REWRITE,
                'label' => 'Rewrite'
            ],
            [
                'value' => BehaviourStrategy::PERMANENT_REDIRECT,
                'label' => 'Permanent Redirect (301)'
            ],
            [
                'value' => BehaviourStrategy::TEMPORARY_REDIRECT,
                'label' => 'Temporary Redirect (302)'
            ]
        ];
    }
}
