<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\DataHubBundle\GraphQL\DocumentElementType;

use Carbon\Carbon;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class DateType extends ObjectType
{
    protected static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            $config =
                [
                    'name' => "document_tagDate",
                    'fields' => [
                        '__tagName' => [
                            'type' => Type::string(),
                            'resolve' => static function ($value = null, $args = [], $context, ResolveInfo $resolveInfo = null) {
                                if ($value) {
                                    return $value->getName();
                                }
                            }
                        ],
                        '__tagType' => [
                            'type' => Type::string(),
                            'resolve' => static function ($value = null, $args = [], $context, ResolveInfo $resolveInfo = null) {
                                if ($value instanceof \Pimcore\Model\Document\Tag\Date) {
                                    return $value->getType();
                                }
                            }
                        ],
                        'timestamp' => [
                            'type' => Type::int(),
                            'resolve' => static function ($value = null, $args = [], $context, ResolveInfo $resolveInfo = null) {
                                if ($value instanceof \Pimcore\Model\Document\Tag\Date) {
                                    $data = $value->getData();
                                    if ($data instanceof Carbon) {
                                        return $data->getTimestamp();
                                    }
                                }
                            }
                        ],
                        'formatted' => [
                            'type' => Type::string(),
                            'args' => ['format' => ['type' => Type::nonNull(Type::string()), 'description' => 'see Carbon::format']],
                            'resolve' => static function ($value = null, $args = [], $context, ResolveInfo $resolveInfo = null) {
                                if ($value instanceof \Pimcore\Model\Document\Tag\Date) {
                                    $data = $value->getData();
                                    if ($data instanceof Carbon) {
                                        $format = $args['format'];
                                        $formattedValue = $data->format($format);
                                        return $formattedValue;
                                    }
                                }
                            }
                        ]
                    ],
                ];
            self::$instance = new static($config);
        }

        return self::$instance;
    }
}
