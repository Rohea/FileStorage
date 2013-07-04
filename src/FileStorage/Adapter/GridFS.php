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
//            'date' => new MongoDate(),
//            'name' => $file->getName(),
            'metadata' => $file->getMetadata(),
            'filename' => $key,
        );
        $id = $this->gridFS->storeBytes($file->getContent(), $gridMetadata);
        $gridFSFile = $this->gridFS->findOne(array('_id' => $id));
        if ($file instanceof GridFSFile) {
            $file->setGridFSFile($gridFSFile);
        }
//        $file->setTimestamp($gridFSFile->file['date']->sec);
        $file->setSize($gridFSFile->file['length']);
        $file->setChecksum($gridFSFile->file['md5']);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function open($key, $strict=false)
    {
        $gridFSFile = $this->gridFS->findOne(array('filename' => $key));
        if (isset($gridFSFile)) {
            //Existing file found
            $file = new GridFSFile($gridFSFile->file['filename'], $gridFSFile);
            //Set data for file (do not set content, it's lazy)
            if (isset($gridFSFile->file['metadata'])) {
                $file->setMetadata($gridFSFile->file['metadata']);
            }
//            $file->setTimestamp($gridFSFile->file['date']->sec);
            $file->setSize($gridFSFile->file['length']);
            $file->setChecksum($gridFSFile->file['md5']);

        } else {
            //File not found
            if ($strict) {
                //Cannot create a new one. Instead throw exception
                throw new FileNotFoundException();
            }
            //Let's create a new one
            $file = new GridFSFile($key);
        }
        return $file;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $gridfsFile = $this->find($key, array('_id'));

        return $gridfsFile && $this->gridFS->delete($gridfsFile->file['_id']);
    }

}
