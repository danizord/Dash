<?php
/**
 * Dash
 *
 * @link      http://github.com/DASPRiD/Dash For the canonical source repository
 * @copyright 2013-2015 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Dash\Route;

use Zend\ServiceManager\AbstractPluginManager;

/**
 * Default plugin manager for handling routes.
 */
class RouteManager extends AbstractPluginManager
{
    /**
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * @var string
     */
    protected $instanceOf = RouteInterface::class;

    /**
     * @var array
     */
    protected $factories = [
        'Generic' => GenericFactory::class,
    ];
}
