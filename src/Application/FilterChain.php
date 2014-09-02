<?php
namespace Serphlet\Application;

/**
 * A concrete implementation of a Filter Chain for use in the appication
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
class FilterChain implements \Serphlet\Filter\Chain
{
    private $filterConfigs = array();
    private $currentPosition = 0;

    private $servlet = null;

    public function doFilter(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        $this->internalDoFilter($request, $response);
    }

    private function internalDoFilter(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        // For each filter wrap until at the end, invoke, and then return back through each filter
        if ($this->currentPosition < count($this->filterConfigs)) {
            $filterConfig = $this->filterConfigs[$this->currentPosition++];
            try {
                $filter = $filterConfig->getFilter();
                $filter->doFilter($request, $response, $this);
            } catch (\Exception $e) {
//                $log = Serphlet_Util_Logger_Manager::getLogger(__CLASS__);
//                $log->error('internalDoFilter caused exception ' . $e->getMessage());
                throw $e;
            }

            return;
        }

        // At the end of chaining, invoke the process
        \Serphlet\Application\FilterChain::servletProcess($request, $response, $this->servlet);
    }

    public function setServlet(\Serphlet\ServletInterface $servlet)
    {
        $this->servlet = $servlet;
    }

    public function addFilterConfig(\Serphlet\Config\FilterConfig $filterConfig)
    {
        $this->filterConfigs[] = $filterConfig;
    }

    public function servletProcess(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response, \Serphlet\ServletInterface $servlet)
    {
        $servlet->service($request, $response);
    }

}
