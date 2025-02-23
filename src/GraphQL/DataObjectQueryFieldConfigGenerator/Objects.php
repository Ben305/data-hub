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

namespace Pimcore\Bundle\DataHubBundle\GraphQL\DataObjectQueryFieldConfigGenerator;

use GraphQL\Type\Definition\Type;
use Pimcore\Bundle\DataHubBundle\GraphQL\DataObjectType\HrefType;
use Pimcore\Bundle\DataHubBundle\GraphQL\TypeDefinitionInterface;
use Pimcore\Model\DataObject\ClassDefinition\Data;

/**
 * Class Multihref
 *
 * @package Pimcore\Bundle\DataHubBundle\GraphQL\DataObjectQueryFieldConfigGenerator
 */
class Objects extends Base implements TypeDefinitionInterface
{
    /**
     * @param $attribute
     * @param Data $fieldDefinition
     * @param null $class
     * @param null $container
     *
     * @return mixed
     */
    public function getGraphQlFieldConfig($attribute, Data $fieldDefinition, $class = null, $container = null)
    {
        return $this->enrichConfig($fieldDefinition, $class, $attribute,
            [
                'name'    => $fieldDefinition->getName(),
                'type'    => $this->getFieldType($fieldDefinition, $class, $container),
                'resolve' => $this->getResolver($attribute, $fieldDefinition, $class)
            ],
            $container
        );
    }

    /**
     * @param Data $fieldDefinition
     * @param null $class
     * @param null $container
     *
     * @return \GraphQL\Type\Definition\ListOfType|mixed
     */
    public function getFieldType(Data $fieldDefinition, $class = null, $container = null)
    {
        return Type::listOf(new HrefType($this->getGraphQlService(), $fieldDefinition, $class));
    }

    /**
     * @param $attribute
     * @param Data $fieldDefinition
     * @param $class
     *
     * @return \Closure
     */
    public function getResolver($attribute, $fieldDefinition, $class)
    {
        $resolver = new \Pimcore\Bundle\DataHubBundle\GraphQL\DataObjectQueryFieldConfigGenerator\Helper\Objects($this->getGraphQlService(), $attribute, $fieldDefinition, $class);
        return [$resolver, "resolve"];
    }
}
