<?php
namespace Serphlet\Config;

/**
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
interface ServletConfigInterface
{
    public function getServletName();
    public function getServletContext();
    public function getInitParameter($name);
    public function getInitParameterNames();
}
