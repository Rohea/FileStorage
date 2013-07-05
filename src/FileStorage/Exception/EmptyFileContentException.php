<?php
namespace FileStorage\Exception;

use \Exception;

/**
 * Exception indicates file content is empty
 *
 * @author Tomi Saarinen <tomi.saarinen@rohea.com>
 */
class EmptyFileContentException extends Exception
{
    protected $key;

    public function __construct($key, $message, $code = 0, Exception $previous = null) {
        $this->key = $key;
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    public function getKey()
    {
        return $this->key;
    }
}
