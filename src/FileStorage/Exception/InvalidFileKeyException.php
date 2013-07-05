<?php
namespace FileStorage\Exception;

use \InvalidArgumentException;
use \Exception;

/**
 * Exception indicates file with given key was not found in file storage
 *
 * @author Tomi Saarinen <tomi.saarinen@rohea.com>
 */
class InvalidFileKeyException extends InvalidArgumentException
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
