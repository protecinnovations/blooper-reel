<?php

namespace Protec\BlooperReel\Recorder;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RecorderFactory
 *
 * @package   Protec\BlooperReel\Recorder
 * @author    Protec Innovations <support@protecinnovations.co.uk>
 * @copyright 2014 Protec Innovations
 */
class RecorderFactory implements FactoryInterface
{

    /**
     * createService
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $recorder_class = $serviceLocator->get('config')['blooper_reel']['recorder'];

        return $serviceLocator->get($recorder_class);
    }
}
