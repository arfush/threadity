<p align="center">
    <img src="resources/logotype_light.png#gh-dark-mode-only" alt="">
    <img src="resources/logotype_dark.png#gh-light-mode-only" alt="">
    <br>
    Utilities for concurrent programming of PocketMine-MP plugins
</p>

## Overview

Plugin that implements the [pthreads](https://github.com/pmmp/pthreads) channels and [Promise](https://en.wikipedia.org/wiki/Futures_and_promises), which are better than the defaults provided by PocketMine-MP.

## How to work with threaded channels

It is recommended to use `ReaderWriterCreator` to create a pthreads _channel_. This is done as follows

```php
[$reader, $writer] = ReaderWriterCreator::create();
```

To send some (non-scalar) through a channel, you first need to serialize it (because the Writer only allows you to enter a simple data type). I advise you to use binary serialization - [igbinary](https://www.php.net/manual/ru/book.igbinary.php).

```php
$obj = new stdClass();
$obj->successor = true;

$writer->write(igbinary_serialize($obj));
```

After that, you can read it (from another thread, after construct it with the Reader) like this

```php
$obj = igbinary_unserialize($reader->read());

if (!$obj->successor) {
    throw new RuntimeException("Operation failed");
}
```

## How to work with promises

This promise implementation is good because it contains two API classes: `PromiseResolver` and `Promise`. It allows you not to care about the fact that some drunk programmer decided that he can not only hang some functionality on the promise provided to him, but also resolver the promise, breaking your code. Don't thank me, I took this idea from PMMP ([@dktapps](https://github.com/dktapps) thanks!). 

And so, the first step is to create the _PromiseResolver_, indicating to it the closure signature for _fulfills_ and _rejects_. This is done as follows

```php
$resolver = new PromiseResolver(function (mixed $any): void {}, function (string $reason): void {});
```

After that, you can return the _Promise_, as in the example of this code

```php
public function requestPage(string $url): Promise
{
    $resolver = new PromiseResolver(function (string $body): void {}, function (string $error): void {});
    // It should be asynchronous, but it doesn't have to be, because this behavior was intended.
    // You can leave it for later by putting TODO.
    $result = Internet::getURL($url, err: $error)->getBody();
    if ($result instanceof InternetRequestResult) {
        $resolver->fulfill($result->getBody());
    } else {
        $resolver->reject($error);
    }
    
    return $resolver->getPromise();
}
```

## And finally... CAT!

<p align="center">
    <img src="https://cataas.com/cat/cute?height=300" alt="">
</p>
