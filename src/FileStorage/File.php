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

    public function getChecksum()
    {
        return $this->checksum;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getSize()
    {
        return $this->size;
    }

}
