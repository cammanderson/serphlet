<?php
namespace Serphlet\Config;

/**
 * Representation of a filter mapping for a web application, as represented
 * in a <code>&lt;filter-mapping&gt;</code> element in the deployment
 * descriptor.  Each filter mapping must contain a filter name plus either
 * a URL pattern or a servlet name.
 *
 * @author Cameron Manderson
 * @author Craig R. McClanahan
 */
class FilterMap
{
    // ------------------------------------------------------------- Properties


    /**
     * The name of this filter to be executed when this mapping matches
     * a particular request.
     */
    const ERROR = 1;
    const FORWARD = 2;
    const FORWARD_ERROR = 3;
    const INCLUDE_ = 4;
    const INCLUDE_ERROR = 5;
    const INCLUDE_ERROR_FORWARD = 6;
    const INCLUDE_FORWARD = 7;
    const REQUEST = 8;
    const REQUEST_ERROR = 9;
    const REQUEST_ERROR_FORWARD = 10;
    const REQUEST_ERROR_FORWARD_INCLUDE = 11;
    const REQUEST_ERROR_INCLUDE = 12;
    const REQUEST_FORWARD = 13;
    const REQUEST_INCLUDE = 14;
    const REQUEST_FORWARD_INCLUDE = 15;

    // represents nothing having been set. This will be seen
    // as equal to a REQUEST
    const NOT_SET = -1;

    private $dispatcherMapping = \Serphlet\Config\FilterMap::NOT_SET;

    private $filterName = null;
    public function getFilterName()
    {
        return ($this->filterName);
    }
    public function setFilterName($filterName)
    {
        $this->filterName = $filterName;
    }

    /**
     * The servlet name this mapping matches.
     */
    private $servletName = null;
    public function getServletName()
    {
        return ($this->servletName);
    }
    public function setServletName($servletName)
    {
        $this->servletName = $servletName;
    }

    /**
     * The URL pattern this mapping matches.
     */
    private $urlPattern = null;
    public function getURLPattern()
    {
        return ($this->urlPattern);
    }
    public function setURLPattern($urlPattern)
    {
//        $this->urlPattern = RequestUtil::URLDecode(urlPattern);
        $this->urlPattern = $urlPattern;
    }

    /**
     *
     * This method will be used to set the current state of the \Serphlet\Config\FilterMap
     * representing the state of when filters should be applied:
     *
     *        ERROR
     *        FORWARD
     *        FORWARD_ERROR
     *        INCLUDE_               INCLUDE_ERROR        INCLUDE_ERROR_FORWARD
     * REQUEST        REQUEST_ERROR        REQUEST_ERROR_INCLUDE
     * REQUEST_ERROR_FORWARD_INCLUDE        REQUEST_INCLUDE
     * REQUEST_FORWARD,        REQUEST_FORWARD_INCLUDE
     *
     */
    public function setDispatcher($dispatcherString)
    {
        $dispatcher = strtoupper($dispatcherString);
        if ($dispatcher == "FORWARD") {

            // apply FORWARD to the global $this->dispatcherMapping.
            switch ($this->dispatcherMapping) {
                case NOT_SET  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::FORWARD; break;
                case ERROR : $this->dispatcherMapping = \Serphlet\Config\FilterMap::FORWARD_ERROR; break;
                case INCLUDE_  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::INCLUDE_FORWARD; break;
                case INCLUDE_ERROR  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::INCLUDE_ERROR_FORWARD; break;
                case REQUEST : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_FORWARD; break;
                case REQUEST_ERROR : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR_FORWARD; break;
                case REQUEST_ERROR_INCLUDE : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR_FORWARD_INCLUDE; break;
                case REQUEST_INCLUDE : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_FORWARD_INCLUDE; break;
            }
        } elseif ($dispatcher == "INCLUDE") {
            // apply INCLUDE to the global $this->dispatcherMapping.
            switch ($this->dispatcherMapping) {
                case NOT_SET  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::INCLUDE_; break;
                case ERROR : $this->dispatcherMapping = \Serphlet\Config\FilterMap::INCLUDE_ERROR; break;
                case FORWARD  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::INCLUDE_FORWARD; break;
                case FORWARD_ERROR  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::INCLUDE_ERROR_FORWARD; break;
                case REQUEST : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_INCLUDE; break;
                case REQUEST_ERROR : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR_INCLUDE; break;
                case REQUEST_ERROR_FORWARD : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR_FORWARD_INCLUDE; break;
                case REQUEST_FORWARD : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_FORWARD_INCLUDE; break;
            }
        } elseif ($dispatcher == "REQUEST") {
            // apply REQUEST to the global $this->dispatcherMapping.
            switch ($this->dispatcherMapping) {
                case NOT_SET  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST; break;
                case ERROR : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR; break;
                case FORWARD  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_FORWARD; break;
                case FORWARD_ERROR  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR_FORWARD; break;
                case INCLUDE_  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_INCLUDE; break;
                case INCLUDE_ERROR  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR_INCLUDE; break;
                case INCLUDE_FORWARD : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_FORWARD_INCLUDE; break;
                case INCLUDE_ERROR_FORWARD : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR_FORWARD_INCLUDE; break;
            }
        } elseif ($dispatcher == "ERROR") {
            // apply ERROR to the global $this->dispatcherMapping.
            switch ($this->dispatcherMapping) {
                case NOT_SET  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::ERROR; break;
                case FORWARD  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::FORWARD_ERROR; break;
                case INCLUDE_  :  $this->dispatcherMapping = \Serphlet\Config\FilterMap::INCLUDE_ERROR; break;
                case INCLUDE_FORWARD : $this->dispatcherMapping = \Serphlet\Config\FilterMap::INCLUDE_ERROR_FORWARD; break;
                case REQUEST : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR; break;
                case REQUEST_INCLUDE : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR_INCLUDE; break;
                case REQUEST_FORWARD : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR_FORWARD; break;
                case REQUEST_FORWARD_INCLUDE : $this->dispatcherMapping = \Serphlet\Config\FilterMap::REQUEST_ERROR_FORWARD_INCLUDE; break;
            }
        }
    }

    public function getDispatcherMapping()
    {
        // per the SRV.6.2.5 absence of any dispatcher elements is
        // equivelant to a REQUEST value
        if ($this->dispatcherMapping == \Serphlet\Config\FilterMap::NOT_SET) return \Serphlet\Config\FilterMap::REQUEST;
        else return $this->dispatcherMapping;
    }
    public function setDispatcherMapping($mapping)
    {
        $this->dispatcherMapping = $mapping;
    }

    // --------------------------------------------------------- Public Methods


    /**
     * Render a String representation of this object.
     */
    public function __toString()
    {
        $sb = "FilterMap[";
        $sb .= "filterName=";
        $sb .= $this->filterName;
        if ($this->servletName != null) {
            $sb .= ", servletName=";
            $sb .= $this->servletName;
        }
        if ($this->urlPattern != null) {
            $sb .= ", urlPattern=";
            $sb .= $this->urlPattern;
        }
        $sb .= "]";

        return $sb;
    }
}
