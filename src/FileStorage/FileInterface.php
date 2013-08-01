<?php
namespace FileStorage;

interface FileInterface
{
    /**
     * Get key
     *
     * @return string
     */
    public function getKey();

    /**
     * Get checksum
     *
     * @return string
     */
    public function getChecksum();

    /**
     * Get content bytes
     *
     * @return bytes
     */
    public function getContent();

    /**
     * Get created/updated timestamp of the file
     *
     * @return integer
     */
    public function getTimestamp();

    /**
     * Get size of the file
     *
     * @return integer
     */
    public function getSize();

    /**
     * Get content type of the file
     *
     * @return string
     */
    public function getContentType();

}
