<?php

namespace BlooperReel\Strategy;

use \Zend\Stdlib\ResponseInterface;
use \Zend\Mvc\MvcEvent;
use \Zend\Mvc\View\Http\ExceptionStrategy as ZendExceptionStrategy;
use \Zend\ServiceManager\ServiceLocatorAwareInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;



// TODO: use \Zend\ServiceManager\ServiceLocatorAwareTrait;

class ExceptionStrategy extends ZendExceptionStrategy implements ServiceLocatorAwareInterface
{
    // TODO: use ServiceLocatorAwareTrait;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function prepareExceptionViewModel(MvcEvent $event)
    {
        $error = $event->getError();

        if (empty($error) || $event->getResult() instanceof ResponseInterface) {
            return;
        }

        switch ($error) {
            case Application::ERROR_CONTROLLER_NOT_FOUND:
            case Application::ERROR_CONTROLLER_INVALID:
            case Application::ERROR_ROUTER_NO_MATCH:
                return;

            case Application::ERROR_EXCEPTION:
            default:
                $response = $event->getResponse();

                if (!$response || $response->getStatusCode() === Response::STATUS_CODE_200) {
                    header('HTTP/1.0 500 Internal Server Error', true, Response::STATUS_CODE_500);
                }

                // TODO: switch here based on whether we're in production or not.

                $this->getServiceLocator()
                    ->get('Whoops')
                    ->handleException($event->getParam('exception'));
                break;
        }

    }
}
