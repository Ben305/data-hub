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
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\DataHubBundle\GraphQL\DataObjectType;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Pimcore\Bundle\DataHubBundle\GraphQL\Resolver\MultihrefMetadata;
use Pimcore\Bundle\DataHubBundle\GraphQL\Service;
use Pimcore\Bundle\DataHubBundle\GraphQL\Traits\ServiceTrait;
use Pimcore\Model\DataObject\ClassDefinition\Data;

class MultihrefMetadataType extends ObjectType
{
    use ServiceTrait;

    /**
     * @var null
     */
    protected $class;

    /**
     * @var Data
     */
    protected $fieldDefinition;

    /**
     * MultihrefMetadataType constructor.
     * @param Service $graphQlService
     * @param Data|null $fieldDefinition
     * @param null $class
     * @param array $config
     */
    public function __construct(Service $graphQlService, Data $fieldDefinition = null, $class = null, $config = [])
    {
        $this->class = $class;
        $this->setGraphQlService($graphQlService);
        $this->fieldDefinition = $fieldDefinition;
        $config['name'] = 'object_'.$class->getName().'_'.$fieldDefinition->getName();
        $this->build($config);
        parent::__construct($config);
    }

    /**
     * @param array $config
     */
    public function build(&$config)
    {
        $fieldDefinition = $this->fieldDefinition;
        $class = $this->class;
        $resolver = new MultihrefMetadata($fieldDefinition, $class, $this->getGraphQlService()->getObjectFieldHelper());
        $fields = ['element'  =>
                       [
                           'type'    => new HrefType($this->getGraphQlService(), $this->fieldDefinition, $this->class),
                           'resolve' => [$resolver, "resolveElement"]
                       ],
                   'metadata' => [
                       'type'    => Type::listOf(new ElementMetadataKeyValuePairType()),
                       'resolve' => [$resolver, "resolveMetadata"]
                   ]];

        $config['fields'] = $fields;

        return;
    }
}
