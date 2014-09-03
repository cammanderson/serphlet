<?php
namespace Serphlet\Config;

/**
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
class ServletMap
{
    private $servletName = '';
    private $urlPatterns = array();

    public function setServletName($servletName)
    {
        $this->servletName = $servletName;
    }
    public function getServletName()
    {
        return $this->servletName;
    }
    public function addUrlPattern($urlPattern)
    {
        $this->urlPatterns[] = $urlPattern;
    }
    public function getUrlPatterns()
    {
        return $this->urlPatterns;
    }
}
