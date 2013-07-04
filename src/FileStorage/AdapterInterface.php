<?php
namespace FileStorage;

interface AdapterInterface
{

    public function save(FileInterface $file);

    public function open($key);

    public function delete($key);

}
