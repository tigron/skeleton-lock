# skeleton-lock

## Description

Various locking mechanisms, providing atomic locks.

## Installation

Installation via composer:

    composer require tigron/skeleton-lock

## Configuration

In the default case, `skeleton-lock` assumes it is running an application
on a single node, and using an RDBMS which supports locks (e.g. MySQL/
MariaDB's `GET_LOCK()`).

However, this is not always supported, for example when running a
clustered database.

The following mechanisms are supported:

  * database (default)
  * file
  * memcache
  * memcached

This can be configured via `Config`:

    \Skeleton\Lock\Config::$locking = 'database';

Of course, each mechanism requires the supporting skeleton package to
be installed as well, and requires the settings for that specific
mechanism to be defined.

    \Skeleton\Object\Config::$cache_handler_config = [
    	'hostname' => '127.0.0.1', // memcache/memcached
    	'port' => '12211', // memcache/memcached
    	'dsn' => 'mysqli://user@database/table', // database
    	'path' => '/path/', // file
    	'expire' => 10, // not supported for database
    ];

Note that not all mechanisms support all features. For example,
database locks can not expire.

## Usage

For getting a lock, you can either call `obtain()` or `wait()`, where
the former will fail immediately if it can not get a lock, and the
latter will retry until the specified timeout expired.

Releasing a lock is done with `release()`.

### Examples

Get the current handler, and wait for 5 seconds to acquire `mylock`:

    \Skeleton\Lock\Handler::get()::wait('mylock', wait: 5);

Get the current handler, get `mylock` immediately and deal with
potential failure:

    try {
    	$lock = \Skeleton\Lock\Handler::get();
    	$lock::obtain('mylock');
    } catch (\Skeleton\Lock\Exception\Failed $e) {
    	echo 'Could not get the lock';
    }

Releasing the lock we got earlier:

    $lock::release('mylock');