<?php
namespace Serphlet\Config;

/**
 * Representation of a filter mapping for a web application, as represented
 * in a <code>&lt;filter-mapping&gt;</code> element in the deployment
 * descriptor.  Each filter mapping must contain a filter name and any
 * number of URL patterns and servlet names.
 */
class FilterMaps
{
    private $urlPatterns = array();
    private $servletNames = array();
    private $filterName;
    private $fmap;

    public function __construct()
    {
        $this->fmap = new \Serphlet\Config\FilterMap();
    }

    // ------------------------------------------------------------ Properties

    public function setFilterName($filterName)
    {
        $this->filterName = $filterName;
    }

    public function getFilterName()
    {
        return $this->filterName;
    }

    public function addServletName($servletName)
    {
        $this->servletNames[] = $servletName;
    }

    public function getServletNames()
    {
        return $this->servletNames;
    }

    public function addURLPattern($urlPattern)
    {
        $this->urlPatterns[] = $urlPattern;
    }

    public function getURLPatterns()
    {
        return $this->urlPatterns;
    }

    public function setDispatcher($dispatcherString)
    {
        $this->fmap->setDispatcher($dispatcherString);
    }

    public function getDispatcherMapping()
    {
        return $this->fmap->getDispatcherMapping();
    }
}
