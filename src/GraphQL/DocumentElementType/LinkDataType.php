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

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Pimcore\Bundle\DataHubBundle\GraphQL\DocumentResolver\Link;
use Pimcore\Bundle\DataHubBundle\GraphQL\Service;
use Pimcore\Bundle\DataHubBundle\GraphQL\Traits\ServiceTrait;

class LinkDataType extends ObjectType
{

    use ServiceTrait;

    protected $graphQlService;

    public function __construct(Service $graphQlService)
    {

        $this->graphQlService = $graphQlService;

        $anyTargetType = $graphQlService->buildGeneralType("anytarget");

        $config =
            [
                'name' => "document_tagLink_data",
                'fields' => [
                    '__tagType' => [
                        'type' => Type::string(),
                        'resolve' => static function ($value = null, $args = [], $context, ResolveInfo $resolveInfo = null) {
                            if ($value instanceof \Pimcore\Model\Document\Tag\Link) {
                                return $value->getType();
                            }
                        }
                    ],

                    'internal' => [
                        'type' => Type::boolean(),
                        'resolve' => static function ($value = null, $args = [], $context, ResolveInfo $resolveInfo = null) {
                            if ($value instanceof \Pimcore\Model\Document\Tag\Link) {
                                return $value->getData() ? $value->getData()["internal"] : null;
                            }
                        }
                    ],
                    'internalType' => [
                        'type' => Type::string(),
                        'resolve' => static function ($value = null, $args = [], $context, ResolveInfo $resolveInfo = null) {
                            if ($value instanceof \Pimcore\Model\Document\Tag\Link) {
                                return $value->getData() ? $value->getData()["internalType"] : null;
                            }
                        }
                    ],
                    'internalId' => [
                        'type' => Type::int(),
                        'resolve' => static function ($value = null, $args = [], $context, ResolveInfo $resolveInfo = null) {
                            if ($value instanceof \Pimcore\Model\Document\Tag\Link) {
                                return $value->getData() ? $value->getData()["internalId"] : null;
                            }
                        }
                    ],
                    '__tagType' => [
                        'type' => Type::string(),
                        'resolve' => static function ($value = null, $args = [], $context, ResolveInfo $resolveInfo = null) {
                            if ($value instanceof \Pimcore\Model\Document\Tag\Link) {
                                return $value->getData() ? $value->getData()["type"] : null;
                            }
                        }
                    ],

                    'path' => [
                        'type' => Type::string(),
                        'resolve' => static function ($value = null, $args = [], $context, ResolveInfo $resolveInfo = null) {
                            if ($value instanceof \Pimcore\Model\Document\Tag\Link) {
                                return $value->getData() ? $value->getData()["path"] : null;
                            }
                        }
                    ]
                    ,
                    'target' => [
                        'type' => $anyTargetType,
                        'resolve' => [new Link($this->getGraphQlService()), "resolveTarget"]
                    ]
                ]
            ];
        parent::__construct($config);
    }

}
