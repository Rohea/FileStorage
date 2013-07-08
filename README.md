FileStorage
===========

FileStorage is an object oriented file storage abstraction library for PHP5. It allows you to easily read and write files to any supported file storage backends with a simple an consistent API. FileStorage also supports storing metadata if the storage backend supports it.

## Why FileStorage?

There are a number of different ways to store your files when you're building an application with PHP. There's the local file system of the server, distributed systems such as GridFS and MogileFS and of course there are cloud-based CDN solutions such as Amazon S3 and many more.

From application point of view, it's not optimal to deeply bind your implementation to any single storage backend, as there might be a need to be able to change that later. For example, you might first use a local filesystem when you start developing but the change to a more advanced solution when the application matures. This is when FileStorage becomes handy. Using the simple API of FileStorage you are able to change the storage backend without needing to change the code using it.

## Features

* Intuitive and very simple API for CRUD operations
* Fully PHP5 compatible with nice namespacing
* Consistent behavior with all supported storage backends.
* Support for metadata if storage backend supports it

Storage backends (feel free to contribute more, this is a work in progress :)

* Local filesystem
* GridFS (MongoDB)

## Usage

The very first thing you need to do is install this library and configure autoloading of code files. It's very easy if you can use Composer for installing and you're using a framework with PSR-0 compatible autoloader. If you're not, I hope you know how to do your magic otherwise :)

When the library is in, you need to prepare adapters with you application specific configurations and then pass the adapter to the ```FileStorage``` instance. The easiest example is for local filesystem adapter

```
use FileStorage\Adapter\Local;
use FileStorage\FileStorage;
...
$adapter = new Local("/tmp");
$storage = new FileStorage($adapter);

```
After that, you're ready to use the FileStorage

```
// Initialize a new file
$file = $storage->init("myKey");

// Set some content and save
$file->setContent('foobar content');
$storage->save($file);

// Load a file from storage
$file = $storage->load("myKey");
$file->getContent();

// Delete file
$storage->delete("myKey");

```
That's how simple it is. You just need to pick a key for your file and you're on. On the other hand, of course, there are also a bit more advanced ways to use the library. See examples below

```
// Load a file from storage and catch an exception if file is not found
use FileStorage\Exception\FileNotFoundException;
...
try {
    $file = $storage->load("myKey");
} catch(FileNotFoundException $e) {
	//do something
}
$file->getContent();

// Init a new file with a touch.
// This stores an empty file immediately ensuring the key is not available in the storage anymore.
$file = $storage->init("myKey", $touch = true);

```

## About choosing a 'key'
The FileStorage library doesn't force you to pick any particular key structure. You can use what you will. But here's our recommendation

```
format:
context/some/identifiers/filename.extension
example:
avatars/user/12345/myavatar.jpg
```

This kind of key structure helps you to avoid key collisions because of namespacing and it's suits well for most of the adapters because slashes convert to folders for local adapter and so on.


## Motivation

Firstly, I want to say that FileStorage is heavily inspired by the very nice and already quite well known [Gaufrette](https://github.com/KnpLabs/Gaufrette) library which also pretty much aims for the same goal as FileStorage. So why re-inventing the wheel? Whereas I really do like the approach of Gaufrette and I've actually taken many design choices almost 'as is' from it, I still think there are some other pieces of design I really don't like (probably they're partially legacy, but still). I've even tried to contribute to Gaufrette to solve them (in friendly co-operation with the maintainers) but Gaufrette is already so widely used and the necessary changes so fundamental that some major unwanted BC breaks would have been necessary. So instead of trying to push my different solution to an already established lib, I finally decided to make my own.
