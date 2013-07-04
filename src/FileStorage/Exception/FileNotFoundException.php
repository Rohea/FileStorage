<?php
namespace FileStorage\Exception;

use \InvalidArgumentException;

/**
 * Exception indicates file with given key was not found in file storage
 *
 * @author Tomi Saarinen <tomi.saarinen@rohea.com>
 */
class FileNotFoundException extends InvalidArgumentException
{

}
