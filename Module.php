<?php
/**
 * ZF2 Integration for Whoops
 * @author Protec Innovations <dev@protecinnovations.co.uk>
 */

namespace BlooperReel;

use \Zend\EventManager\EventInterface;

class Module
{
    protected $whoops;
    protected $config = array();

    public function onBootstrap(EventInterface $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        $this->whoops = $serviceManager->get('Whoops');

        $PrettyPageHandler = $serviceManager->get('BlooperReel\PrettyPageHandler');
        $this->config = $serviceManager->get('config');

        $this->whoops->register();
        $this->whoops->pushHandler($events);

        $this->attachListeners($event);
    }

    protected function attachListeners(EventInterface $event)
    {
        $request = $event->getRequest();
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        $eventManager = $application->getEventManager();

        if ($request instanceof ConsoleRequest) {
            return
        }

        $exceptionStrategy = $serviceManager->get('BlooperReel\ExceptionStrategy');

        $exceptionStrategy->attach($eventManager);

        //Detach default ExceptionStrategy
        $services->get('Zend\Mvc\View\Http\ExceptionStrategy')->detach($eventManager);
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'Whoops' => 'Whoops\Run',
                'Whoops\PrettyPageHandler' => 'Whoops\Handler\PrettyPageHandler',
                'BlooperReel\ExceptionStrategy' => 'BlooperReel\Strategy\ExceptionStrategy'
            ],
            'factories' => [
                'BlooperReel\PrettyPageHandler' => 'BlooperReel\PrettyPageHandler\Factory'
            ]
        ];
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
