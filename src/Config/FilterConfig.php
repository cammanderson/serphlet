<?php
namespace Serphlet\Config;

/**
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com>
 * @author Craig R. McClanahan
 */
class FilterConfig
{
    /**
	 * Logging instance.
	 * @var Logger
	 */
    protected static $log = null;

    // ----------------------------------------------------------- Constructors


    /**
     * Construct a new ApplicationFilterConfig for the specified filter
     * definition.
     *
     * @param context The context with which we are associated
     * @param filterDef Filter definition for which a FilterConfig is to be
     *  constructed
     *
     * @exception ClassCastException if the specified class does not implement
     *  the <code>Serphlet_Filter</code> interface
     * @exception ClassNotFoundException if the filter class cannot be found
     * @exception IllegalAccessException if the filter class cannot be
     *  publicly instantiated
     * @exception InstantiationException if an exception occurs while
     *  instantiating the filter object
     * @exception ServletException if thrown by the filter's init() method
     */
    public function __construct(\Serphlet\Config\ApplicationContext $context, \Serphlet\Config\FilterDef $filterDef)
    {
        $this->context = $context;
        $this->setFilterDef($filterDef);
    }

    public function __wakeup()
    {
//        if (is_null(self::$log)) {
//            self::$log = Serphlet_Util_Logger_Manager::getLogger(__CLASS__);
//        }
    }

    // ----------------------------------------------------- Instance Variables


    /**
     * The Context with which we are associated.
     */
    private $context = null;

    /**
     * The application Filter we are configured for.
     */
    private $filter = null;

    /**
     * The <code>FilterDef</code> that defines our associated Filter.
     */
    private $filterDef = null;

    // --------------------------------------------------- FilterConfig Methods


    /**
     * Return the name of the filter we are configuring.
     */
    public function getFilterName()
    {
        return ($this->filterDef->getFilterName());
    }

    /**
     * Return a <code>String</code> containing the value of the named
     * initialization parameter, or <code>null</code> if the parameter
     * does not exist.
     *
     * @param string name Name of the requested initialization parameter
     */
    public function getInitParameter($name)
    {
        $map = $this->filterDef->getParameterMap();
        if (empty($map[$name]))
            return (null);
        else
            return $map[$name];
    }

    /**
     * Return an <code>array</code> of the names of the initialization
     * parameters for this Filter.
     */
    public function getInitParameterNames()
    {
        $map = $this->filterDef->getParameterMap();
        if (empty($map))
            return array();
        else
            return array_keys($map);
    }

    /**
     * Return the ServletContext of our associated web application.
     * @return ServletContext
     */
    public function getServletContext()
    {
        return ($this->context);

    }

    /**
     * Return a String representation of this object.
     */
    public function __toString()
    {
        $sb = "ApplicationFilterConfig[";
        $sb .= "name=";
        $sb .= $this->filterDef->getFilterName();
        $sb .= ", filterClass=";
        $sb .= $this->filterDef->getFilterClass();
        $sb .= "]";

        return ($sb);

    }

    // -------------------------------------------------------- Package Methods


    /**
     * Return the application Filter we are configured for.
     *
     * @exception ClassCastException if the specified class does not implement
     *  the <code>Serphlet_Filter</code> interface
     * @exception ClassNotFoundException if the filter class cannot be found
     * @exception IllegalAccessException if the filter class cannot be
     *  publicly instantiated
     * @exception InstantiationException if an exception occurs while
     *  instantiating the filter object
     * @exception ServletException if thrown by the filter's init() method
     * @return Filter
     */
    public function getFilter()
    {
        // Return the existing filter instance, if any
        if ($this->filter != null)
            return ($this->filter);

        // Identify the class loader we will be using
        $filterClass = $this->filterDef->getFilterClass();

        // Instantiate a new instance of this filter and return it
        $this->filter = \Serphlet\ClassLoader::newInstance($filterClass, '\Serphlet\Filter');
        $this->filter->init($this);

        return ($this->filter);

    }

    /**
     * Return the filter definition we are configured for.
     */
    public function getFilterDef()
    {
        return ($this->filterDef);
    }

    /**
     * Release the Filter instance associated with this FilterConfig,
     * if there is one.
     */
    public function release()
    {
        if ($this->filter != null) {
            $this->filter->destroy();
        }
        $this->filter = null;
     }

    /**
     * Set the filter definition we are configured for.  This has the side
     * effect of instantiating an instance of the corresponding filter class.
     *
     * @param filterDef The new filter definition
     *
     * @exception ClassCastException if the specified class does not implement
     *  the <code>Serphlet_Filter</code> interface
     * @exception ClassNotFoundException if the filter class cannot be found
     * @exception IllegalAccessException if the filter class cannot be
     *  publicly instantiated
     * @exception InstantiationException if an exception occurs while
     *  instantiating the filter object
     * @exception ServletException if thrown by the filter's init() method
     */
    public function setFilterDef(\Serphlet\Config\FilterDef $filterDef)
    {
        $this->filterDef = $filterDef;
        if ($this->filterDef == null) {
            if ($this->filter != null) {
                $this->filter->destroy();
            }
            $this->filter = null;
        } else {
            $this->filter = $this->getFilter();
        }
    }

    // -------------------------------------------------------- Private Methods


}
