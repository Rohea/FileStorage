<?php
namespace FileStorage\Exception;

use \RuntimeException;

/**
 * Exception indicates file already exists
 *
 * @author Tomi Saarinen <tomi.saarinen@rohea.com>
 */
class FileAlreadyExistsException extends RuntimeException
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
