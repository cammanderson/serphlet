<?php
namespace Serphlet\Application;

/**
 * A class used for createing Filter chains. Filter chains
 * allow multiple filters to be 'chained' together and wrap one
 * another. Chains are built based on those filters that match
 * the current request.
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
class FilterFactory
{
    private static $filterFactory = null; // For us of our singleton

    private function __constuct() {}

    public static function getInstance()
    {
        if(empty(self::$filterFactory)) self::$filterFactory = new \Serphlet\Application\FilterFactory();

        return self::$filterFactory;
    }

    public function createFilterChain(\Symfony\Component\HttpFoundation\Request $request, \Serphlet\ServletInterface $servlet)
    {
        // Create and initialize a filter chain object
        $filterChain = null;
        $filterMaps = $servlet->getServletConfig()->getServletContext()->findFilterMaps();

        // If there are no filter mappings, we are done
        if (($filterMaps == null) || (count($filterMaps) == 0))
        return (null);

        // Get the path
//		$requestPath = $request->getParameter($servlet->getServletContext()->getServletConfig()->getPathParam());
        $requestPath = $request->getPathInfo();

        // Add the relevant path-mapped filters to this filter chain
        $n = 0;
        foreach ($filterMaps as $filterMap) {
            if (!$this->matchFiltersURL($filterMap, $requestPath, true))
            continue;
            try {
                $filterConfig = $servlet->getServletContext()->findFilterConfig($filterMap->getFilterName());
                if ($filterConfig == null) {
                    throw new Exception('Could not locate the filter config for the supplied filter name ' . $filterMap->getFilterName());
                }

                if ($filterChain == null)
                $filterChain = $this->internalCreateFilterChain($request, $servlet);
                $filterChain->addFilterConfig($filterConfig);
                $n++;

            } catch (Exception $e) {
//                $log = Serphlet_Util_Logger_Manager::getLogger(__CLASS__);
//                $log->error('createFilterChain() caused exception ' . $e->getMessage());
                continue;
            }
        }

        // Add filters that match on servlet name second
        foreach ($filterMaps as $filterMap) {
            if(!$this->matchFiltersServlet($filterMap, $servlet->getServletConfig()->getServletName()))
            continue;
            $filterConfig = $servlet->getServletContext()->findFilterConfig($filterMap->getFilterName());
            if ($filterConfig == null) {
//                $log = Serphlet_Util_Logger_Manager::getLogger(__CLASS__);
//                $log->error('createFilterChain() caused exception ' . $e->getMessage());
                continue;
            }
            if ($filterChain == null)
            $filterChain = $this->internalCreateFilterChain($request, $servlet);
            $filterChain->addFilterConfig($filterConfig);
            $n++;
        }

        // Return the completed filter chain
        return ($filterChain);
    }

    private function matchFiltersURL(\Serphlet\Config\FilterMap $filterMap, $requestPath, $caseSensitiveMapping = true)
    {
        if ($requestPath == null)
            return (false);

        // Match on context relative request path
        $testPath = $filterMap->getURLPattern();
        if ($testPath == null)
        return (false);

        if (!$caseSensitiveMapping) {
            $requestPath = strtolower($requestPath);
            $testPath = strtolower($testPath);
        }

        // Case 1 - Exact Match
        if ($testPath == $requestPath)
            return (true);

        // Case 2 - Path Match ("/.../*")
        if ($testPath == '/*') return (true);
        if (preg_match('/\/\*$/', $testPath)) {
            if (substr($testPath, 0, strlen($testPath) -2) == substr($requestPath, 0, strlen($testPath) - 2)) {
                if (strlen($requestPath) == (strlen($testPath) - 2)) {
                    return (true);
                } elseif ('/' == substr($requestPath, strlen($testPath) -2, 1)) {
                    return (true);
                }
            }

            return (false);
        }

        // Case 3 - Extension Match
        if (preg_match('/^\*\./', $testPath)) {
            $slash = strrpos($requestPath, '/');
            $period = strrpos($requestPath, '.');
            if (($slash >= 0) && ($period > $slash)
            && ($period != (strlen($requestPath) - 1))
            && ((strlen($requestPath) - $period)
            == (strlen($testPath) - 1))) {
                return (substr($testPath, 2) == substr($requestPath, $period));
            }
        }

        // Case 4 - "Default" Match
        return (false); // NOTE - Not relevant for selecting filters

    }

    private function matchFiltersServlet(\Serphlet\Config\FilterMap $filterMap, $servletName)
    {
        if ($servletName == null) {
            return (false);
        } else {
            if ($servletName == $filterMap->getServletName() || $filterMap->getServletName() == '*') {
                return (true);
            } else {
                return false;
            }
        }
    }

    private function internalCreateFilterChain(\Symfony\Component\HttpFoundation\Request $request, \Serphlet\ServletInterface $servlet)
    {
        $filterChain = new \Serphlet\Application\FilterChain();
        $filterChain->setServlet($servlet);

        return $filterChain;
    }
}
