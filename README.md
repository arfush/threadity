<p align="center">
    <img src="resources/logotype_light.png#gh-dark-mode-only" alt="">
    <img src="resources/logotype_dark.png#gh-light-mode-only" alt="">
    <br>
    Utilities for concurrent programming of PocketMine-MP plugins
</p>

## Overview

Plugin that implements the [pthreads](https://github.com/pmmp/pthreads) channels and in the future, [promises](https://en.wikipedia.org/wiki/Futures_and_promises) (which are better than the defaults provided by PocketMine-MP) and even [await](https://en.wikipedia.org/wiki/Async/await).

## How to work with threaded channels

Creating a channel can be done literally in one line, as shown below

```php
$channel = new Channel();
```

Also, this implementation of threaded channels allows you to split one channel object into several other classes, such as: `Reader`, `Writer` and `Closer`. 
This allows you as a developer to give your plugin modules only what they need (For example, `DiscordMessageSenderThread` only needs to read from a channel). Here's how it's done

```php
$reader = $channel->getReader();
$writer = $channel->getWriter();
$closer = $channel->getCloser();

(new DiscordMessageSenderThread($reader))->start();
```

You can just as easily write data to the channel (did you have any doubts?). But keep in mind that 
if you need to pass some object, then the first thing you need to do is serialize it. To do this, I advise 
you to use binary serialization - [igbinary](https://php.net/igbinary). Like this.

```php
$obj = new stdClass();
$obj->message = "Hello, World!";

$writer->write(igbinary_serialize($obj));
```

To read the data, use the `Reader::read()` (or `Channel::read()`) method. Please note that the method signature contains the variable _$wait_, which 
is responsible for whether the thread will be blocked until there is data in the channel, or whether it will 
throw `NoDataException` exception.

```php
try {
    $obj = igbinary_unserialize($reader->read(wait: true));
    // some code to send message to a Discord chat...
} catch (ClosedException) {
    break; // If the channel is closed, then stop the thread loop
}
```

And with non-blocking read...

```php
try {
    $obj = igbinary_unserialize($reader->read(wait: false));
    // some code to send message to a Discord chat...
} catch (NoDataException) {
    continue;
} catch (ClosedException) {
    break; // If the channel is closed, then stop the thread loop
}
```

Upon completion of work, you can close the channel using the `Closer::close()` or `Channel::close()`. 
Then the next time your code try to write/read the _ClosedException_ exception will be thrown. Look at example.

```php
protected function onDisable(): void
{
    $this->closer->close();
}
```

## And finally... CAT!

<p align="center">
    <img src="https://cataas.com/cat" alt="">
</p>
