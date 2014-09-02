<?php
namespace Serphlet\Request;

/**
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
interface DispatcherInterface {
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function doForward(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response);
    public function doInclude(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Reseponse $response);
}