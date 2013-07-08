<?php
namespace FileStorage;

interface AdapterInterface
{

    public function save(FileInterface $file);

    public function init($key, $touch = false);

    public function delete($key);

}
