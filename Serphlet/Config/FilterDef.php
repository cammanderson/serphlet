<?php
namespace Serphlet\Config;

/**
 * Representation of a filter definition for a web application, as represented
 * in a <code>&lt;filter&gt;</code> element in the deployment descriptor.
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com>
 * @author Craig R. McClanahan
 */
class FilterDef
{
    // ------------------------------------------------------------- Properties


    /**
     * The description of this filter.
     */
    private $description = null;
    public function getDescription()
    {
        return ($this->description);
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * The display name of this filter.
     */
    private $displayName = null;
    public function getDisplayName()
    {
        return ($this->displayName);
    }
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * The fully qualified name of the Java class that implements this filter.
     */
    private $filterClass = null;
    public function getFilterClass()
    {
        return ($this->filterClass);
    }
    public function setFilterClass($filterClass)
    {
        $this->filterClass = $filterClass;
    }

    /**
     * The name of this filter, which must be unique among the filters
     * defined for a particular web application.
     */
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
     * The large icon associated with this filter.
     */
    private $largeIcon = null;
    public function getLargeIcon()
    {
        return ($this->largeIcon);
    }
    public function setLargeIcon($largeIcon)
    {
        $this->largeIcon = $largeIcon;
    }

    /**
     * The set of initialization parameters for this filter, keyed by
     * parameter name.
     */
    private $parameters = array();
    public function getParameterMap()
    {
        return ($this->parameters);
    }

    /**
     * The small icon associated with this filter.
     */
    private $smallIcon = null;

    public function getSmallIcon()
    {
        return ($this->smallIcon);
    }

    public function setSmallIcon($smallIcon)
    {
        $this->smallIcon = $smallIcon;
    }

    // --------------------------------------------------------- Public Methods


    /**
     * Add an initialization parameter to the set of parameters associated
     * with this filter.
     *
     * @param string name The initialization parameter name
     * @param string value The initialization parameter value
     */
    public function addInitParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Render a String representation of this object.
     */
    public function __toString()
    {
        $sb = "FilterDef[";
        $sb .= "filterName=";
        $sb .= $this->filterName;
        $sb .= ", filterClass=";
        $sb .= $this->filterClass;
        $sb .= "]";

        return ($sb);

    }

}
