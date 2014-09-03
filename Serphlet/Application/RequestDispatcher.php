<?php
namespace Serphlet\Application;

/**
 * Defines an object that receives requests from the client and sends them to
 * any resource (such as a HTML or PHP file) on the server.
 *
 * @author Olivier HENRY <oliv.henry@gmail.com> (PHP5 port of Struts)
 * @author John WILDENAUER <jwilde@users.sourceforge.net> (PHP4 port of Struts)
 */
class RequestDispatcher implements \Serphlet\Request\DispatcherInterface
{
    /**
	 * Commons Logging instance.
	 *
	 * @var \Psr\Log\LoggerInterface
	 */
    protected $log = null;

    /**
	 * The context this RequestDispatcher is associated with.
	 *
	 * @var \Serphlet\Config\ServletContext
	 */
    protected $context = null;

    /**
	 * The request URI for this RequestDispatcher.
	 *
	 * @var string
	 */
    protected $requestURI = null;

    /**
	 * Construct a new instance of this class, configured according to the
	 * specified parameters.
	 *
	 * @param ServletContext $context The context this
	 * RequestDispatcher is associated with
	 * @param string $requestURI The request URI for this RequestDispatcher
	 */
    public function __construct(\Serphlet\Config\ServletContext $context)
    {
        $this->context = $context;
    }

    public function __wakeup()
    {
    }

    /**
	 * @return string
	 */
    public function getRequestURI()
    {
        return $this->requestURI;
    }

    /**
	 * @param string $requestURI
	 */
    public function setRequestURI($requestURI)
    {
        $this->requestURI = (string) $requestURI;
    }

    /**
	 * Forward this request and response to another resource for processing.
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request The servlet request to be
	 * forwarded
	 * @param \Symfony\Component\HttpFoundation\Response $response The servlet response to be
	 * forwarded
	 */
    public function doForward(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        // Reset any output that has been buffered, but keep headers/cookies
        if ($response->isCommitted()) {
            if (!empty($this->log)) {
                $this->log->error('Forward on committed response');
            }
            throw new \Serphlet\Exception\IllegalStateException('Cannot forward after response has been committed');
        }
        $response->resetBuffer();

        $this->invoke($request, $response);

        \Serphlet\Application\RequestDispatcherForward::commit($request, $response, $this->context);
    }

    /**
	 * Include the response from another resource in the current response.
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request The servlet request that is
	 * including this one
	 * @param \Symfony\Component\HttpFoundation\Response $response The servlet response to be
	 * appended to
	 */
    public function doInclude(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        $this->invoke($request, $response);
    }

    /**
	 * Ask the resource represented by this RequestDispatcher to process
	 * the associated request, and create (or append to) the associated response.
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request The servlet request we are
	 * processing
	 * @param \Symfony\Component\HttpFoundation\Response $response The servlet response we are
	 * creating
	 * @todo Manage exception if the resource doesn't exist.
	 */
    protected function invoke(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        $requestURI = urldecode($this->requestURI);
        $urls = @ parse_url($requestURI);
        if (array_key_exists('query', $urls)) {
            parse_str($urls['query'], $_GET);
        }
        $path = $this->context->getRealPath($urls['path']);

        $_REQUEST = array_merge($_GET, $_POST, $_COOKIE);

        $fileExists = @fopen($path, 'r', true);
        if (!$fileExists) {
            $this->$log->error('Resource ' . $path . ' is not found');
            throw new \Serphlet\Exception\UnavailableException('The resource is currently unavailable');
        } else fclose($fileExists);

        if ($response->getAutoflush()) {
            require $path;
        } else {
            ob_start();
            require $path;
            $response->write(ob_get_contents());
            ob_end_clean();
        }
    }
}
