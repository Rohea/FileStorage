<?php
namespace FileStorage\File;

use FileStorage\File;
use \MongoGridFSFile;

/**
 * GridFS specific file object that decorates the \MongoGridFSFile object
 *
 * @author Tomi Saarinen <tomi.saarinen@rohea.com>
 */
class GridFS extends File
{
    protected $gridFSFile = null;

    /**
     * Constructor
     *
     * @param string $key
     * @param \MongoGridFSFile $gridFSFile
     */
    public function __construct($key, MongoGridFSFile $gridFSFile = null)
    {
        $this->key = $key;
        $this->gridFSFile = $gridFSFile;
    }

    public function getGridFSFile()
    {
        return $this->gridFSFile;
    }

    public function setGridFSFile($gridFSFile)
    {
        $this->gridFSFile = $gridFSFile;
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
        if (isset($this->gridFSFile)) {
            //This operation is lazy and should not be called before the bytes are actually needed in app.
            $content = $this->gridFSFile->getBytes();
            $this->setContent($content);

            return $content;
        }

        return null;
    }

}
