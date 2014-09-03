<?php
namespace Serphlet;

/**
 * The Serphlet_Host plays the role of initialising the
 * environment for handling requests and responses in PHP.
 *
 * It will process the web.xml configuration file, create the
 * request and response objects and map the requests to the correct
 * object/classes in your application.
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
class Host
{
    /** Our HTTP Request/Response Objects */
    protected $request;
    protected $response;

    protected $basePath;

    protected $context;
    protected $config;
    protected $servlet;
    protected $configured;

    protected $performed;
    protected $log;

    /** Our servlet configuration files (web configs) */
    protected $configurationFiles = array();
    protected $cacheDirectory = null;

    /**
	 * Initialisation function
	 * @param $basePath
	 */
    protected function init($basePath = null)
    {
        // Configure the application (once)
        if($this->configured == true) return;

        // TODO: Configure the base path
        if(empty($basePath)) $this->basePath = dirname(__FILE__);
        else $this->basePath = (string) $basePath;

        // TODO: Check for our servlet configuration files
        if(empty($this->configurationFiles)) throw \Serphlet\Exception\UnavailableException('Missing servlet configuration. Please ensure application has added references to servlet configuration files (See Serphlet_Application::addServletConfigurationFile())');

//		self::setTimeMarker('INIT02 - Init Configuration');

        // TODO: Process the servlet configuration
        if (self::configFileExpired()) {
            // Process the configuration
            $digester = new Serphlet_Phigester_Digester();
            $digester->addRuleSet(new \Serphlet\Config\ApplicationRuleSet());

            $this->context = new \Serphlet\Config\ApplicationContext($this->basePath, null);
            $digester->push($this->context);
            $configFilePath = self::getRealPath(current($this->configurationFiles));
            $digester->parse($configFilePath);
            unset ($digester);

            // Cache the config
            if (is_writable(self::getCacheDirectory())) {
                $cacheFile = self::getRealPath(self::getCacheDirectory() . DIRECTORY_SEPARATOR . 'serphlet.data');
                $serialData = serialize($this->context);
                file_put_contents($cacheFile, $serialData);
            }
        } else {
            // Load the configuration
            $cacheFile = self::getRealPath(self::getCacheDirectory() . DIRECTORY_SEPARATOR . 'serphlet.data');
            $serialData = file_get_contents($cacheFile);
            $this->context = unserialize($serialData);
        }

        // Configure connectors
        $this->request = new \Symfony\Component\HttpFoundation\Request();
        $this->response = new \Symfony\Component\HttpFoundation\Response();

        // Configure the base dir
        $this->request->setAttribute(\Serphlet\Globals::BASE_PATH, $basePath);

        // Complete initialisation
        $configured = true;
    }

    /**
	 * Provides a real path to the provided path, prepending the basePath
	 * of the application if necessary.
	 *
	 * TODO: consider private/protected and get calls relative to context in modules
	 * @param unknown_type $path
	 */
    public function getRealPath($path)
    {
        return $this->basePath . (substr($path, 0, 1) == '/' ? $path : '/' . $path);
    }

    /**
	 * Determines whether the web.xml configuration cache has expired
	 */
    protected function configFileExpired()
    {
        // TODO: Determine if the same number of web configs are being used
        $cachePath = self::getRealPath(self::getCacheDirectory() . DIRECTORY_SEPARATOR . 'serphlet.data');
        if (!file_exists($cachePath)) {
            return true;
        }
        $cacheTime = filemtime($cachePath);

        // TODO: Look at multiple servlet configurations
        $filePath = self::getRealPath(current($this->configurationFiles)); // Pop the first
        $fileTime = filemtime($filePath);

        return $fileTime > $cacheTime;
    }

    /**
	 * Maps the current PHP request to the correct servlet configuration
	 */
    protected function map()
    {
        // TODO: Write a mapper class to fully implement matching

        // Look through servlet mappings and do a match
        $pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null;

        // Check for configured servlets
        $servlets = $this->context->getServlets();
        if(empty($servlets)) throw new \Serphlet\Exception\Unavailable('No servlets configured');

        // Attempt to match urls
        $urlMapped = array();
        foreach ($servlets as $servlet) {
            $urlMappings = $servlet->getMappings();
            if (!empty($urlMappings)) foreach ($urlMappings as $urlMapping) {
                // Is this a file extension method (but not a path method)
                if (!empty($pathInfo) && substr($urlMapping, 0, 2) == '*.') {
                    $suffix = substr($urlMapping, 1);
                    if (strpos($pathInfo, '.') > 0 && substr($pathInfo, (-1 * strlen($suffix))) == $suffix) {
                        $urlMapped[$urlMapping] = $servlet;
                    }
                }

                // /servlet/path/* method (or default /*), but not with an extension present
                if (substr($urlMapping, -2) == '/*' && (strpos($pathInfo, '.') === false || strlen($urlMapping) == 2)) {
                    $prefix = substr($urlMapping, 0, -1);
                    $ignoreTrailingSlashPathInfo = substr($pathInfo, -1) != '/' ? $pathInfo . '/' : $pathInfo;
                    if (strpos($ignoreTrailingSlashPathInfo, $prefix) === 0) {
                        // Prefix matches the path info
                        $urlMapped[$urlMapping] = $servlet;
                    }
                }
            }
        }

        // TODO: Consider the welcome files (when empty path info)

        // Match the 'best' (based on the most specific match)
        $pattern = '';
        if (count($urlMapped) == 1) {
            // Only one matched
            $pattern = key($urlMapped);
            $this->config = current($urlMapped);
        } else {
            // Match the most specific one
            $matches = array_keys($urlMapped);
            foreach($matches as $match) if(strlen($match) > strlen($pattern)) $pattern = $match;
            if(!empty($pattern)) $this->config = $urlMapped[$pattern];
        }

        // Check the result
        if(empty($this->config)) throw new \Serphlet\Exception\Unavailable('No servlets configured');
        $this->config->setServletMapping($pattern);

        // Setup the request paths
        $requestPathInfo = '';
        $requestServletPath = '';
        if (strpos($pattern, '.') > -1) {
            // Extension match, ditch the extension in the path info
            $requestPathInfo = substr($pathInfo, 0, (-1 * strlen(substr($pattern, 1))));
        } else {
            // Path pattern
            $requestServletPath = substr($pattern, 0, -2);
            $requestPathInfo = substr($pathInfo, strlen($requestServletPath));
        }
        if(substr($requestPathInfo, 0, 1) !== '/') $requestPathInfo = '/' . $requestPathInfo;
        $this->request->setPathInfo($requestPathInfo);
        $this->request->setServletPath($requestServletPath);

        // Factory the matching servlet classname for the request
        try {
            $className = $this->config->getServletClass();
            $servlet = \Serphlet\ClassLoader::newInstance($className, '\Serphlet\ServletInterface');
        } catch (Exception $e) {
            // TODO: Handle
            throw $e;
        }

        return $servlet;
    }

    /**
	 * The main function for processing the current request from PHP
	 */
    public function process($basePath = null)
    {
        try {
            ob_start();
//			$this->log = Serphlet_Util_Logger_Manager::getRootLogger();

            // Register a shutdown function
            register_shutdown_function(array('Serphlet_Host', 'shutdown'));
            set_error_handler(array('Serphlet_Host', 'defaultErrorHandler'));

            // Initialise
            self::init($basePath);

            // TODO: Identify the servlet
            $this->servlet = self::map();
            if(empty($this->servlet)) throw new \Serphlet\Exception\UnavailableException('Servlet not available for this request');

            // Initialise the servlet
            $this->servlet->init($this->config);

            // Configure the filter configurations
            $this->servlet->getServletConfig()->getServletContext()->filterStart();

            // Create a filter chain
            $filterFactory = \Serphlet\Application\FilterFactory::getInstance();
            $filterChain = $filterFactory->createFilterChain($this->request, $this->servlet);

            // Do filters prior to processing the modules
            if ($filterChain != null) {
                $filterChain->doFilter($this->request, $this->response);
            } else {
                \Serphlet\Application\FilterChain::servletProcess($this->request, $this->response, $this->servlet);
            }

            // Release filters
            $this->servlet->getServletConfig()->getServletContext()->filterStop();

            // TODO: Consider the location of this dispatch forward!
            if (!$this->response->isCommitted() && $this->response->isError()) {
                \Serphlet\Application\RequestDispatcherForward::commit($this->request, $this->response, $this->context);
            }

            // Flush the response
            $this->response->flushBuffer();

            // TODO: Shutdown

        } catch (Exception $e) {
            self::gracefulDie($e);
        }
    }

    /**
	 * A shutdown method for handling logging PHP_ERROR events to logging
	 */
    public function shutdown()
    {
        // Consider if a PHP Fatal error caused shutdown
        $error = error_get_last();
        $raiseTypes = array(E_ERROR);
        if ($error != null && in_array($error['type'], $raiseTypes) && !$this->performed) {
            $this->performed = true;
            @ob_end_clean();
//			if (!empty($this->log)) {
//				$this->log->error('PHP Error: '. $error['file'] . ':' . $error['line'] . ' ' . $error['message'] . ' LEVEL: ' . $error['type']);
//			}
            self::gracefulDie(new Exception('Encountered a PHP Fatal Error'));
            exit();
        }
    }

    /**
	 * The default handler for PHP errors, logging them to the logging object
	 */
    public function defaultErrorHandler($type, $message, $file, $line)
    {
        // Log some of the PHP Errors to the logger manager
        $raiseTypes = array(E_USER_ERROR, E_USER_WARNING);
        if (in_array($type, $raiseTypes) && !empty($this->log)) {
            switch ($type) {
                case E_USER_ERROR:
//                    $this->log->error('PHP Error (E_USER_ERROR): ' . $file . ':' . $line . ' ' . $message);
                    throw new \Exception($message);
                case E_USER_WARNING:
//                    $this->log->error('PHP Warning (E_USER_WARNING): ' . $file . ':' . $line . ' ' . $message);
                    break;
            }
        }
    }

    private function gracefulDie($exception = null)
    {
        // Attempt a graceful die
        @ob_end_clean();
        if(empty($exception)) $exception = new Exception('Unhandled PHP Exception encountered');
//        if (!empty($this->log)) {
//            $this->log->error('Caught exception ' . $exception->getMessage());
//            $this->log->debug($exception->getTraceAsString());
//        }
        try {
            if (!empty($this->response) && !empty($this->request) && !empty($this->context)) {
                $this->response->sendError(\Symfony\Component\HttpFoundation\Response::SC_INTERNAL_SERVER_ERROR, $exception->getMessage());
                $this->request->setAttribute(\Serphlet\Globals::ERROR_MESSAGE_ATTR, $exception->getMessage());
                $this->request->setAttribute(\Serphlet\Globals::EXCEPTION_ATTR, $exception);
                \Serphlet\Application\RequestDispatcherForward::commit($this->request, $this->response, $this->context);
                $this->response->flushBuffer();
            }
        } catch (Exception $e) {
//            if (!empty($this->log)) {
//                $this->log->error('Could not handle a graceful die ' . $e->getMessage());
//            }
        }
        exit();
    }

    /**
	 * Add the servlet configuration file to the application
	 * Note: Currently only supports 1 configuration file
	 * @param string $configuration
	 */
    public function addConfigurationFile($configuration)
    {
        // TODO: Add the configuration to the stack
        $this->configurationFiles = array($configuration);
    }

    /**
	 * Modify the cache directory for Serphlet to write cache files
	 * to as necessary
	 */
    public function setCacheDirectory($path)
    {
        $this->cacheDirectory = $path;
    }

    /**
	 * Obtain the directory to store the cache to
	 */
    public function getCacheDirectory()
    {
        return $this->cacheDirectory;
    }

    /**
	 * Obtain the current servlet configuration
	 * NOTE: This is likely to be removed in future versions, use local scope instead!
	 */
    public function getServletConfig()
    {
        return $this->config;
    }

    /**
	 * Obtain the reference to the request that the host has configured
	 * NOTE: This is likely to be removed in future versions, use local scope instead!
	 */
    public function getRequest()
    {
        return $this->request;
    }

    /**
	 * Obtain the reference to the response that the host has configured
	 * NOTE: This is likely to be removed in future versions, use local scope instead!
	 */
    public function getResponse()
    {
        return $this->response;
    }

    /**
	 * Obtain the current servlet that the host has configured
	 * NOTE: This is likely to be removed in future versions, use local scope instead!
	 */
    public function getServlet()
    {
        return $this->servlet;
    }
}
