<?php

namespace Protec\BlooperReel\PrettyPageHandler;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory
 *
 * @package   Protec\BlooperReel\PrettyPageHandler
 * @author    Protec Innovations <support@protecinnovations.co.uk>
 * @copyright 2013 - 2014 Protec Innovations
 */
class Factory implements FactoryInterface
{
    /**
     * createService
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return array|mixed|object
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $prettyPageHandler = $serviceLocator->get('Whoops\PrettyPageHandler');

        $editor = $serviceLocator->get('config')['whoops']['editor'];

        $prettyPageHandler->setEditor($editor);

        return $prettyPageHandler;
    }
}
