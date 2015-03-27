<?php

namespace Protec\BlooperReel\Strategy;

use Protec\BlooperReel\Recorder\RecorderInterface;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\View\Http\ExceptionStrategy as ZendExceptionStrategy;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * ExceptionStrategy
 *
 * @package   Protec\BlooperReel\Strategy
 * @author    Protec Innovations <support@protecinnovations.co.uk>
 * @copyright 2013 - 2015 Protec Innovations
 */
class ExceptionStrategy extends ZendExceptionStrategy implements
    ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var \Protec\BlooperReel\Recorder\RecorderInterface $recorder
     */
    protected $recorder = null;

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
                $identifier = $this->generateUniqueId();

                $whoops = $this->getServiceLocator()->get('Whoops');

                $whoops->writeToOutput(false);
                $whoops->allowQuit(false);

                $whoops_output = $whoops->handleException(
                    $event->getParam('exception')
                );

                $this->getRecorder()->save($identifier, $whoops_output);

                $request = $event->getRequest();

                if ($request instanceof HttpRequest && $request->isXmlHttpRequest()) {
                    $view_model = new JsonModel();
                } else {
                    $view_model = new ViewModel();
                }

                $view_model->setVariables(
                    [
                        'error_identifier' => $identifier,
                        'whoops' => $whoops_output,
                        'message' => 'An error occurred during execution; please try again later.',
                        'exception' => $event->getParam('exception'),
                        'display_exceptions' => $this->displayExceptions(),
                    ]
                );

                $view_model->setTerminal(true);

                $view_model->setTemplate($this->getExceptionTemplate());
                $event->setResult($view_model);

                $response = $event->getResponse();
                if (!$response) {
                    $response = new HttpResponse();
                    $response->setStatusCode(500);
                    $event->setResponse($response);
                } elseif ($response instanceof HttpResponse) {
                    $statusCode = $response->getStatusCode();
                    if ($statusCode === 200) {
                        $response->setStatusCode(500);
                    }
                } else {
                    $exception = $event->getParam('exception');
                    if (isset($exception->xdebug_message)) {
                        $response->setContent($exception->xdebug_message);
                    } else {
                        $response->setContent(
                            sprintf(
                                "%s\n\n%s",
                                $exception->getMessage(),
                                $exception->getTraceAsString()
                            )
                        );
                    }
                }
                break;
        }
    }

    /**
     * generateUniqueId
     *
     * @return string
     */
    protected function generateUniqueId()
    {
        return base_convert(uniqid(), 16, 36);
    }

    /**
     * getRecorder
     *
     * @return \Protec\BlooperReel\Recorder\RecorderInterface
     */
    public function getRecorder()
    {
        return $this->recorder;
    }

    /**
     * setRecorder
     *
     * @param \Protec\BlooperReel\Recorder\RecorderInterface $recorder
     *
     * @return $this
     */
    public function setRecorder(RecorderInterface $recorder)
    {
        $this->recorder = $recorder;

        return $this;
    }
}
