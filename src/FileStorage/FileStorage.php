<?php
namespace FileStorage;

use FileStorage\Exception\EmptyFileContentException;
use FileStorage\Exception\InvalidFileKeyException;

class FileStorage
{
    protected $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Saves changes to file
     *
     * @param FileInterface $file
     * @return boolean success
     */
    public function save(FileInterface $file)
    {
        if ($file->getContent() == null) {
            throw new EmptyFileContentException($file->getKey(), "Cannot save an empty file.");
        }
        return $this->adapter->save($file);
    }

    /**
     * Opens file for reading and modifying
     *
     * @param string $key
     * @param boolean $strict If true, throws exception instead of returning a new File when key is not available in storage
     * @return FileInterface $file;
     */
    public function open($key, $strict=false)
    {
        if (! isset($key) || strlen(trim($key)) == 0) {
            throw new InvalidFileKeyException($key, "File key cannot be empty");
        }

        //@todo: file key could be validated against a regular expression?

        return $this->adapter->open($key, $strict);
    }

    /**
     * Deletes file from FileStorage
     *
     * @param string key
     * @return boolean success
     * @throws FileNotFoundException if key does not match any file in storage
     */
    public function delete($key)
    {
        return $this->adapter->delete($key);
    }
}
