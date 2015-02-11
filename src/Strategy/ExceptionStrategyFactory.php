<?php

namespace Protec\BlooperReel\Strategy;

use ArrayAccess;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ExceptionStrategyFactory
 *
 * @package \Protec\BlooperReel\Strategy
 * @author Protec Innovations <support@protecinnovations.co.uk>
 * @copyright 2014 - 2015 Protec Innovations
 */
class ExceptionStrategyFactory implements FactoryInterface
{
    /**
     * createService
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $recorder = $serviceLocator->get('\Protec\BlooperReel\Recorder');

        $strategy = $serviceLocator->get('\Protec\BlooperReel\Strategy\ExceptionStrategy');

        $strategy->setRecorder($recorder);

        $config = $serviceLocator->get('config');

        $viewmanager_config = array();

        if (isset($config['view_manager'])) {
            if (is_array($config['view_manager']) || $config['viewManager'] instanceof ArrayAccess) {
                $viewmanager_config = $config['view_manager'];
            }
        }

        $displayExceptions = false;
        $exceptionTemplate = 'error';

        if (isset($viewmanager_config['display_exceptions'])) {
            $displayExceptions = $viewmanager_config['display_exceptions'];
        }
        if (isset($viewmanager_config['exception_template'])) {
            $exceptionTemplate = $viewmanager_config['exception_template'];
        }

        $strategy->setDisplayExceptions($displayExceptions);
        $strategy->setExceptionTemplate($exceptionTemplate);

        return $strategy;
    }
}
