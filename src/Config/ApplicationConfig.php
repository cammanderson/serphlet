<?php
namespace Serphlet\Config;

/**
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
class ApplicationConfig implements ServletConfigInterface
{
    private $servletName;
    private $servletContext;
    private $parameters;
    private $servletClass;

    private $mappings;
    private $servletMapping;

    public function setServletClass($servletClass)
    {
        $this->servletClass = $servletClass;
    }
    public function getServletClass()
    {
        return $this->servletClass;
    }
    public function getServletName()
    {
        return $this->servletName;
    }
    public function setServletName($servletName)
    {
        $this->servletName = $servletName;
    }

    public function getServletContext()
    {
        return $this->servletContext;
    }
    public function setServletContext($servletContext)
    {
        $this->servletContext = $servletContext;
    }

    /**
	 * Returns a string containing the value of the named initialization
	 * parameter, or null if the parameter does not exist.
	 *
	 * @param string $name The name of the initialization parameter
	 * @return string
	 */
    public function getInitParameter($name)
    {
        $name = ($name);
        if (is_array($this->parameters) && array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        } else {
            return null;
        }
    }

    /**
	 * Returns the names of the servlet's initialization parameters.
	 *
	 * @return array Returns an array of string, or an empty array if the
	 * servlet has no initialization parameters
	 */
    public function getInitParameterNames()
    {
        if(!empty($this->parameters))

            return array_keys($this->parameters);
        else
            return null;
    }

    /**
	 * Set an initialization parameter.
	 *
	 * @param string $name Name of the initialization parameter
	 * @param string $value Value of the initialization parameter
	 */
    public function setInitParameter($name, $value)
    {
        $name = (string) $name;
        $this->parameters[$name] = (string) $value;
    }

    /**
	 * Adds a possible mapping for mapping this servlet to the request
	 * @param unknown_type $mapping
	 */
    public function addMapping($mapping)
    {
        $this->mappings[] = $mapping;
    }

    /**
	 * Returns with all the configured mappings
	 */
    public function getMappings()
    {
        return $this->mappings;
    }

    /**
	 * Returns with the servlet mapping used to map this request
	 */
    public function getServletMapping()
    {
        return $this->servletMapping;
    }
    /**
	 * Sets the servlet mapping used to map this request
	 * @param string $value
	 */
    public function setServletMapping($value)
    {
        $this->servletMapping = $value;
    }
}
