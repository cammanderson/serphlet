<?php
namespace Serphlet\Http;

/**
 * A HTTP Servlet that provides methods for handling a HTTP Request/Response
 *
 * Implement the corresponding request method (e.g. doGet/doHead etc)
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
abstract class Servlet extends \Serphlet\GenericServlet
{
    const METHOD_DELETE = "DELETE";
    const METHOD_HEAD = "HEAD";
    const METHOD_GET = "GET";
    const METHOD_OPTIONS = "OPTIONS";
    const METHOD_POST = "POST";
    const METHOD_PUT = "PUT";
    const METHOD_TRACE = "TRACE";

    const HEADER_IFMODSINCE = "If-Modified-Since";
    const HEADER_LASTMOD = "Last-Modified";

    protected function doGet(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        $protocol = $request->getProtocol();
        $message = 'method not supported';
        if (substr($protocol, -3) == '1.1') {
            $response->sendError(\Symfony\Component\HttpFoundation\Response::SC_METHOD_NOT_ALLOWED, $message);
        } else {
            $response->sendError(\Symfony\Component\HttpFoundation\Response::SC_BAD_REQUEST, $message);
        }
    }

    public function getLastModified(\Symfony\Component\HttpFoundation\Request $request)
    {
        return -1;
    }

    protected function doHead(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        $this->doGet($request, $response);
        // Set no body
        $response->resetBuffer();
        $response->setContentLength(0);
    }

    protected function doPost(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        $protocol = $request->getProtocol();
        $message = 'Request method not supported';
        if (substr($protocol, -3) == '1.1') {
            $response->sendError(\Symfony\Component\HttpFoundation\Response::SC_METHOD_NOT_ALLOWED, $message);
        } else {
            $response->sendError(\Symfony\Component\HttpFoundation\Response::SC_BAD_REQUEST, $message);
        }
    }

    protected function doPut(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        $protocol = $request->getProtocol();
        $message = 'Request method not supported';
        if (substr($protocol, -3) == '1.1') {
            $response->sendError(\Symfony\Component\HttpFoundation\Response::SC_METHOD_NOT_ALLOWED, $message);
        } else {
            $response->sendError(\Symfony\Component\HttpFoundation\Response::SC_BAD_REQUEST, $message);
        }
    }

    protected function doDelete(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
    $protocol = $request->getProtocol();
        $message = 'Request method not supported';
        if (substr($protocol, -3) == '1.1') {
            $response->sendError(\Symfony\Component\HttpFoundation\Response::SC_METHOD_NOT_ALLOWED, $message);
        } else {
            $response->sendError(\Symfony\Component\HttpFoundation\Response::SC_BAD_REQUEST, $message);
        }
    }

    protected function doOptions(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        // Use reflection to identify the get_class
        throw new \Serphlet\Exception('Method not implemented');
    }

    protected function doTrace(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        throw new \Serphlet\Exception('Method not implemented');
    }

    public function service(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response)
    {
        $method = $request->getMethod();
        if ($method == self::METHOD_GET) {
            $lastModified = $this->getLastModified($request);
            if ($lastModified == -1) {
                $this->doGet($request, $response);
            } else {
                $ifModifiedSince = $request->getDateHeader(self::HEADER_IFMODSINCE);
                if ($ifModifiedSince < ($lastModified / 1000 * 1000)) {
                    $this->maybeSetLastModified($response, $lastModified);
                    $this->doGet($request, $response);
                } else {
                    $response->setStatus(\Symfony\Component\HttpFoundation\Response::SC_NOT_MODIFIED);
                }
            }
        } elseif ($method == self::METHOD_HEAD) {
            $lastModified = $this->getLastModified($request);
            $this->maybeSetLastModified($response, $lastModified);
            $this->doHead($request, $response);
        } elseif ($method == self::METHOD_POST) {
            $this->doPost($request, $response);
        }
    }
}
