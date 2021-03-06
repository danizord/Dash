<?php
/**
 * Dash
 *
 * @link      http://github.com/DASPRiD/Dash For the canonical source repository
 * @copyright 2013-2015 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Dash\Route;

use Dash\Parser\ParserManager;
use Dash\Parser\Segment;
use Dash\RouteCollection\RouteCollection;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class GenericFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  array              $options
     * @return Segment
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $route = new Generic();

        if ($options === null) {
            return $route;
        }

        $parserManager = $container->get(ParserManager::class);
        $routeManager  = $container->get(RouteManager::class);

        if (!isset($options['path']) && isset($options[0])) {
            $options['path'] = $options[0];
        }

        if (isset($options['path_parser'])) {
            $route->setPathParser($parserManager->get($options['path_parser'], $options));
        } elseif (isset($options['path'])) {
            $route->setPathParser($parserManager->get('PathSegment', $options));
        }

        if (isset($options['hostname_parser'])) {
            $route->setHostnameParser($parserManager->get($options['hostname_parser'], $options));
        } elseif (null !== ($options['hostname'] = (isset($options['hostname']) ? $options['hostname'] : null))) {
            $route->setHostnameParser($parserManager->get('HostnameSegment', $options));
        }

        if (!isset($options['methods']) && isset($options[3])) {
            $options['methods'] = $options[3];
        }

        if (isset($options['methods'])) {
            $route->setMethods($options['methods']);
        }

        if (isset($options['secure'])) {
            $route->setSecure($options['secure']);
        }

        $defaults = (isset($options['defaults']) ? $options['defaults'] : []);

        if (!isset($options['controller']) && isset($options[2])) {
            $defaults['controller'] = $options[2];
        }

        if (!isset($options['action']) && isset($options[1])) {
            $defaults['action'] = $options[1];
        }

        $route->setDefaults($defaults);

        if (isset($options['children'])) {
            $routeList = new RouteCollection($routeManager);

            foreach ($options['children'] as $name => $child) {
                $routeList->insert(
                    $name,
                    $child,
                    is_array($child) && isset($child['priority']) ? $child['priority'] : 1
                );
            }

            $route->setChildren($routeList);
        }

        return $route;
    }
}
