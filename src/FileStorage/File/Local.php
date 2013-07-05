<?php
namespace FileStorage\File;

use FileStorage\File;
use FileStorage\FileMetadataInterface;

/**
 * Local fs specific file object
 *
 * @author Tomi Saarinen <tomi.saarinen@rohea.com>
 */
class Local extends File
{
    protected $path;

    /**
     * Constructor
     *
     * @param string $key
     * @param string $path
     */
    public function __construct($key, $path)
    {
        $this->key = $key;
        $this->path = $path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the content
     *
     * @return string content bytes
     */
    public function getContent()
    {
        if (isset($this->content)) {
            return $this->content;
        }
        if (isset($this->path)) {
            //This operation is lazy and should not be called before the bytes are actually needed in app.
            $content = file_get_contents($this->path);
            $this->setContent($content);

            return $content;
        }

        return null;
    }

}
