<?php
namespace FileStorage;

interface AdapterInterface
{
    /**
     * Saves changes to file.
     *
     * @param FileInterface $file
     *
     * @return boolean success
     */
    public function save(FileInterface $file);

    /**
     * Loads a file from storage
     *
     * @param string $key
     *
     * @return FileInterface
     */
    public function load($key);

    /**
     * Initializes a new file
     *
     * @param string $key
     *
     * @return FileInterface
     */
    public function init($key);

    /**
     * Deletes file
     *
     * @param string $key
     *
     * @return boolean success
     */
    public function delete($key);

}
