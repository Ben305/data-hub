<?php
declare(strict_types=1);
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
use Pimcore\Model\DataObject\ClassDefinition\Data;

/**
 * Class ImageGallery
 * @package Pimcore\Bundle\DataHubBundle\GraphQL\DataObjectQueryFieldConfigGenerator
 */
class ImageGallery extends Base
{
    const TYPE = 'imageGallery';

    /**
     * @param $attribute
     * @param Data $fieldDefinition
     * @param null $class
     * @param null $container
     * @throws \Exception
     * @return mixed
     */
    public function getGraphQlFieldConfig($attribute, Data $fieldDefinition, $class = null, $container = null)
    {
        return $this->enrichConfig($fieldDefinition, $class, $attribute,
            [
                'name' => $fieldDefinition->getName(),
                'type' => $this->getFieldType($fieldDefinition, $class, $container),
                'resolve' => $this->getResolver($attribute, $fieldDefinition, $class)
            ],
            $container
        );
    }

    /**
     * @param Data $fieldDefinition
     * @param null $class
     * @param null $container
     * @return \GraphQL\Type\Definition\ListOfType|mixed
     * @throws \Exception
     */
    public function getFieldType(Data $fieldDefinition, $class = null, $container = null)
    {
        $hotspotType = $this->getGraphQlService()->getDataObjectTypeDefinition(Hotspotimage::TYPE);
        return Type::listOf($hotspotType);
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
        $resolver = new \Pimcore\Bundle\DataHubBundle\GraphQL\DataObjectQueryFieldConfigGenerator\Helper\ImageGallery($this->getGraphQlService(), $attribute, $fieldDefinition, $class);
        return [$resolver, "resolve"];
    }
}
