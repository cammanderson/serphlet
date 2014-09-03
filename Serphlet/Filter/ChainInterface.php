<?php
namespace Serphlet\Filter;

/**
 * An interface for defining the required methods of a filter chain
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
interface ChainInterface
{
    public function doFilter(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response);
}
