<?php
namespace Serphlet\Config;

/**
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Serphlet Contributor)
 */
class ErrorPage
{
    private $errorCode;
    private $exceptionType;
    private $location;
    private $reason;

    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function setExceptionType($exceptionType)
    {
        $this->exceptionType = $exceptionType;
    }

    public function getExceptionType()
    {
        return $this->exceptionType;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }
}
