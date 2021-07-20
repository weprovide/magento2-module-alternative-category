<?php
declare(strict_types=1);

namespace WeProvide\AlternativeCategory\Model;

/**
 * Interface with the sole purpose of storing / centralising the attribute code of the "Alternative Category" attribute
 *
 * @since 1.0.0
 */
interface AlternativeCategoryAttributeInterface
{
    /** @var string the attribute code used for the "Alternative Category" attribute */
    public const ATTRIBUTE_CODE = 'alternative_category';
}
