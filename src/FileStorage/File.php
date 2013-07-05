<?php
namespace FileStorage;

class File implements FileInterface
{
    protected $key;
    protected $content;
    protected $checksum;
    protected $timestamp;
    protected $size;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
    }

    public function getChecksum()
    {
        return $this->checksum;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }

}
