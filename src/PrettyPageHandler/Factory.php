<?php

namespace BlooperReel\PrettyPageHandler;

use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;

class Factory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $prettyPageHandler = $serviceLocator->get('Whoops\PrettyPageHandler');

        $editor = $serviceLocator->get('config')['whoops']['editor'];

        $prettyPageHandler->setEditor($editor);

        return $prettyPageHandler;
    }
}
