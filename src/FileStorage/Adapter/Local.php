<?php
namespace FileStorage\Adapter;

use FileStorage\FileInterface;
use FileStorage\AdapterInterface;
use FileStorage\File;
use FileStorage\File\Local as LocalFile;
use FileStorage\Exception\FileNotFoundException;

/**
 * Adapter for the Local filesystem
 *
 * @author Tomi Saarinen <tomi.saarinen@rohea.com>
 */
class Local implements AdapterInterface
{
    protected $rootDir = null;

    /**
     * Constructor
     *
     * @param string $rootDir root directory for the file storage
     */
    public function __construct($rootDir = "/tmp")
    {
        if (! is_dir($rootDir)) {
            throw new \RuntimeException(sprintf('Root directory "%s" must be created before using the Local adapter.', $rootDir));
        }
        //Remove trailing slash from root dir (if it exists)
        if(substr($rootDir, -1) == '/') {
            $rootDir = substr($rootDir, 0, -1);
        }

        $this->rootDir = $rootDir;
    }

    /**
     * {@inheritDoc}
     */
    public function save(FileInterface $file)
    {
        $key = $file->getKey();

        $path = $this->rootDir.'/'.$this->normalize($key);
        //Create all directories recursively on path
        if(! file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        //Write file
        file_put_contents($path, $file->getContent());
        //Set file data
        $file->setTimestamp(filemtime($path));
        $file->setSize(mb_strlen($file->getContent(), '8bit'));
        $file->setChecksum(md5_file($path));

        return $file;
    }

    /**
     * {@inheritDoc}
     */
    public function load($key)
    {
        $path = $this->rootDir.'/'.$this->normalize($key);
        if (is_file($path)) {
            //Existing file found
            $file = new LocalFile($key, $path);
            //Set file data
            $file->setContent(file_get_contents($path));
            $file->setTimestamp(filemtime($path));
            $file->setSize(mb_strlen($file->getContent(), '8bit'));
            $file->setChecksum(md5_file($path));
            //TODO: Set contentType and name.

            return $file;
        }
        throw new FileNotFoundException($key, "File not found");
    }

    /**
     * {@inheritDoc}
     */
    public function init($key)
    {
        $path = $this->rootDir.'/'.$this->normalize($key);

        return new LocalFile($key, $path);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $path = $this->rootDir.'/'.$this->normalize($key);
        if (is_file($path)) {
            unlink($path);
            //@todo: recursively delete empty folders
        }
        return true;
    }


    /**
     * Normalizes the given path
     *
     * Based on the implementation by Antoine Hérault in Gaufrette library
     *
     * @param string $path
     *
     * @return string
     */
    private function normalize($path)
    {
        $path   = str_replace('\\', '/', $path);

        preg_match('|^(?P<prefix>([a-zA-Z]:)?/)|', $path, $matches);
        $prefix = "";
        if (! empty($matches['prefix'])) {
            $prefix = strtolower($matches['prefix']);
        }

        $path   = substr($path, strlen($prefix));
        $parts  = array_filter(explode('/', $path), 'strlen');
        $tokens = array();

        foreach ($parts as $part) {
            switch ($part) {
                case '.':
                    continue;
                case '..':
                    if (0 !== count($tokens)) {
                        array_pop($tokens);
                        continue;
                    } elseif (!empty($prefix)) {
                        continue;
                    }
                default:
                    $tokens[] = $part;
            }
        }

        $path = $prefix . implode('/', $tokens);
        //Remove leading and trailing slashes
        $path = ltrim($path, '/');

        return $path;
    }

}
