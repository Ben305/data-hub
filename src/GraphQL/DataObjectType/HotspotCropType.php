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
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\DataHubBundle\GraphQL\DataObjectType;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class HotspotCropType
 * @package Pimcore\Bundle\DataHubBundle\GraphQL\DataObjectType
 */
class HotspotCropType extends ObjectType
{
    /**
     * @var self
     */
    protected static $instance;

    /**
     * @return HotspotCropType
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            $config = [
                'fields' => [
                    'cropTop' => Type::float(),
                    'cropLeft' => Type::float(),
                    'cropHeight' => Type::float(),
                    'cropWidth' => Type::float(),
                    'cropPercent' => Type::boolean(),
                ],
            ];
            self::$instance = new static($config);
        }

        return self::$instance;
    }
}
