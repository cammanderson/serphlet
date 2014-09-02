<?php
namespace Serphlet;

/**
 * A class containing global consts that are used in identifying
 * components in the application.
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
class Globals
{
    const BASE_PATH = 'Serphlet_BASE_PATH';

    /**
     * The request attribute under which we forward a PHP exception
     * (as an object of type Throwable) to an error page.
     */
    const EXCEPTION_ATTR = '\Serphlet\Exception\ATTR';

    /**
     * The request attribute under which we forward the request URI
     * (as an object of type String) of the page on which an error occurred.
     */
    const EXCEPTION_PAGE_ATTR = '\Serphlet\Exception\PAGE_ATTR';

      /**
     * The request attribute under which we forward a PHP exception type
     * (as an object of type Class) to an error page.
     */
    const EXCEPTION_TYPE_ATTR = "\Serphlet\Exception\TYPE_ATTR";

    /**
     * The request attribute under which we forward an HTTP status message
     * (as an object of type String) to an error page.
     */
    const ERROR_MESSAGE_ATTR = "Serphlet_ERROR_MESSAGE_ATTR";

    /**
     * The request attribute under which we forward an HTTP status code
     * (as an object of type Integer) to an error page.
     */
    const STATUS_CODE_ATTR = "Serphlet_STATUS_CODE_ATTR";
}
