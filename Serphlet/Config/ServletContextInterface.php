<?php
namespace Serphlet\Config;

/**
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
interface ServletContextInterface
{
    public function getContextPath();
    public function getContext($uriPath);
    public function getMajorVersion();
    public function getMinorVersion();
    public function getMimeType($file);
    public function getResourcePaths($path);
    public function getResource($path);
    public function getResourceAsStream($path);
    public function getRequestDispatcher($path);
    public function getNamedDispatcher($name);
    public function getServlet($name);
    public function getServlets();
    public function getServletNames();
    public function log($message, \Exception $throwable);
    public function getRealPath($path);
    public function getServerInfo();
    public function getInitParameter($name);
    public function getInitParameterNames();
    public function getAttribute($name);
    public function getAttributeNames();
    public function setAttribute($name, $object);
    public function removeAttribute($name);
    public function getServletContextName();

}
