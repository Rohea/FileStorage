<?php
namespace FileStorage\Adapter;

use FileStorage\FileInterface;
use FileStorage\AdapterInterface;
use FileStorage\File;
use FileStorage\File\GridFS as GridFSFile;
use FileStorage\Exception\FileNotFoundException;
use \MongoGridFS;
use \MongoDate;

/**
 * Adapter for the GridFS FileStorage on MongoDB database
 *
 * @author Tomi Saarinen <tomi.saarinen@rohea.com>
 */
class GridFS implements AdapterInterface
{
    protected $gridFS = null;

    /**
     * Constructor
     *
     * @param \MongoGridFS $gridFS
     */
    public function __construct(MongoGridFS $gridFS)
    {
        $this->gridFS = $gridFS;
    }

    /**
     * {@inheritDoc}
     */
    public function save(FileInterface $file)
    {
        $key = $file->getKey();
        $gridMetadata = array(
            'metadata' => $file->getMetadata(),
            'filename' => $key,
        );
        $id = $this->gridFS->storeBytes($file->getContent(), $gridMetadata);
        $gridFSFile = $this->gridFS->findOne(array('_id' => $id));
        if ($file instanceof GridFSFile) {
            $file->setGridFSFile($gridFSFile);
        }
        $file->setTimestamp($gridFSFile->file['uploadDate']->sec);
        $file->setSize($gridFSFile->file['length']);
        $file->setChecksum($gridFSFile->file['md5']);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function load($key)
    {
        $gridFSFile = $this->gridFS->findOne(array('filename' => $key));
        if (isset($gridFSFile)) {
            //Existing file found
            $file = new GridFSFile($gridFSFile->file['filename'], $gridFSFile);
            //Set data for file (do not set content, it's lazy)
            if (isset($gridFSFile->file['metadata'])) {
                $file->setMetadata($gridFSFile->file['metadata']);
            }
            $file->setTimestamp($gridFSFile->file['uploadDate']->sec);
            $file->setSize($gridFSFile->file['length']);
            $file->setChecksum($gridFSFile->file['md5']);

            return $file;
        }
        throw new FileNotFoundException($key, "File not found");
    }

    /**
     * {@inheritDoc}
     */
    public function init($key)
    {
        return new GridFSFile($key);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $gridFSFile = $this->gridFS->findOne($key, array('_id'));
        if (! isset($gridFSFile)) {
            //File already deleted
            return true;
        }

        $this->gridFS->delete($gridFSFile->file['_id']);
        return true;
    }

}
