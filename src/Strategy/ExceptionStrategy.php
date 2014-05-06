<?php

namespace Protec\BlooperReel\Strategy;

use Zend\Http\Response;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\View\Http\ExceptionStrategy as ZendExceptionStrategy;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\ResponseInterface;

/**
 * ExceptionStrategy
 *
 * @package   Protec\BlooperReel\Strategy
 * @author    Protec Innovations <support@protecinnovations.co.uk>
 * @copyright 2013 - 2014 Protec Innovations
 */
class ExceptionStrategy extends ZendExceptionStrategy implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * prepareExceptionViewModel
     *
     * @param \Zend\Mvc\MvcEvent $event
     */
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
