FileStorage (WIP, do not use)
===========

FileStorage is an object oriented file storage abstraction library for PHP5 that aims to support metadata and efficient querying for storage backends supporting them.

## Features

FileStorage library...

* is fully PHP5 compatible with nice namespacing
* behaves consistently with all supported storage backends.
* has an intuitive and very simple API
* supports metadata if storage backend supports it
* supports URI awareness to make operating with CDN storages easier.

## Usage

```php

// Open storage (this example is for MongoDB GridFS)
$mongoGridFS = new \MongoGridFS(…connection parameters here…);
$adapter = new Adapter\GridFS($mongoGridFS);
$storage = new FileStorage($adapter);

// Load a file from storage, throws an exception if file is not found
try {
    $file = $storage->load('myFileKey');
} catch(FileNotFoundException $e) {
	//do something
}
$file->getContent();

// Create a new file. Also reserve key and set timestamp by touching the file immediately
$file = $storage->open('myFileKey', $touch = true);
$file->setContent('foobar content');
$storage->save($file);

```

## Motivation

Firstly, I want to say that FileStorage is heavily inspired by the very nice and already quite well known [Gaufrette](https://github.com/KnpLabs/Gaufrette) library which also pretty much aims for the same goal as FileStorage. So why re-inventing the wheel? Whereas I really do like the approach of Gaufrette and I've actually taken many design choices almost 'as is' from it, I still think there are some other pieces of design I really don't like (probably partially legacy, but still). I've even tried to contribute to Gaufrette to solve them (in friendly co-operation with the maintainers) but Gaufrette is already so widely used and the necessary changes so fundamental that some major unwanted BC breaks would have been necessary. So instead of trying to push my slightly but significantly different solution to an already existing lib, I finally decided to make my own.
