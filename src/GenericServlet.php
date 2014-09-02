<?php
namespace Serphlet;

/**
 * The Generic Servlet provide a base implementation of the
 * \Serphlet\ServletInterface interface.
 *
 * Use the \Serphlet\Http\Servlet class to implment HTTP
 * servlet into your application environment.
 *
 * @see \Serphlet\Http\Servlet
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
class GenericServlet implements ServletInterface
{
    protected $servletConfig;

    /**
	 * The initialisation of the servlet, as configured in the web.xml file
	 *
	 * You are able to implement the init function to perform any initialisation
	 * functions that are used across any request to this Servlet.
	 */
    public function init(\Serphlet\Config\ServletConfigInterface $servletConfig)
    {
        $this->servletConfig = $servletConfig;
    }

    /**
	 * The \Serphlet\Config\ServletConfigInterface object will allow you
	 * to access domain specific configuration parameters you have configured
	 * in your web.xml file.
	 */
    public function getServletConfig()
    {
        return $this->servletConfig;
    }

    /**
	 * Returns the configuration of the context to the entire servlet
	 * environment. You can configure application configurations accessible
	 * for all servlet run in your application.
	 *
	 * @return \Serphlet\Config\ServletConfigInterface configuration of the context
	 */
    public function getServletContext()
    {
        return $this->getServletConfig()->getServletContext();
    }

    /**
	 * Used to service a request to build a response.
	 */
    public function service(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        // The class defines a specific request method
    }

    /**
	 * Return with information about the current servlet
	 * @TODO:
	 */
    public function getServletInfo()
    {
        return (null);
    }

    /**
	 * A function for implementing a destroy function in the servlet
	 * lifecycle
	 */
    public function destroy()
    {
        $this->servletConfig = null;
    }

}
