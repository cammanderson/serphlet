<?php
namespace Serphlet;

/**
 * An interface used for servlets
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
interface ServletInterface
{
    public function init(\Serphlet\Config\ServletConfigInterface $servletConfig);
    public function getServletConfig();
    public function service(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response);
    public function getServletInfo();
    public function destroy();
}
