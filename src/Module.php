<?php

namespace Protec\BlooperReel;

use Zend\EventManager\EventInterface;

/**
 * Module
 *
 * @package   Protec\BlooperReel
 * @author    Protec Innovations <support@protecinnovations.co.uk>
 * @copyright 2013 - 2014 Protec Innovations
 */
class Module
{
    protected $whoops;
    protected $config = array();

    /**
     * onBootstrap
     *
     * @param \Zend\EventManager\EventInterface $event
     */
    public function onBootstrap(EventInterface $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        $this->whoops = $serviceManager->get('Whoops');

        $prettyPageHandler = $serviceManager->get('\Protec\BlooperReel\PrettyPageHandler');

        $this->config = $serviceManager->get('config');

        //$this->whoops->register();
        $this->whoops->pushHandler($prettyPageHandler);

        $this->attachListeners($event);
    }

    /**
     * attachListeners
     *
     * @param \Zend\EventManager\EventInterface $event
     */
    protected function attachListeners(EventInterface $event)
    {
        $request = $event->getRequest();
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        $eventManager = $application->getEventManager();

        if ($request instanceof ConsoleRequest) {
            return;
        }
        $exceptionStrategy = $serviceManager->get('\Protec\BlooperReel\ExceptionStrategy');

        $exceptionStrategy->attach($eventManager);

        //Detach default ExceptionStrategy
        $serviceManager->get('ExceptionStrategy')->detach($eventManager);


    }

    /**
     * getServiceConfig
     *
     * @return array
     */
    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'Whoops' => 'Whoops\Run',
                'Whoops\PrettyPageHandler' => '\Whoops\Handler\PrettyPageHandler',
                '\Protec\BlooperReel\ExceptionStrategy' => '\Protec\BlooperReel\Strategy\ExceptionStrategy'
            ],
            'factories' => [
                '\Protec\BlooperReel\PrettyPageHandler' => '\Protec\BlooperReel\PrettyPageHandler\Factory'
            ]
        ];
    }

    /**
     * getConfig
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
