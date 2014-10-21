<?php
namespace FileStorage;

interface FileUrlAwareInterface
{
    /**
     * Get full url for the file
     *
     * @return string
     */
    public function getUrl();

}
