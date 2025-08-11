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

### database

No additional configuration required.

### memcache / memcached

These handlers both need a hostname and port:

    \Skeleton\Lock\Config::$locking_handler_config = [
    	'hostname' => '127.0.0.1',
    	'port' => 11211,
    ];

### file

The file handler needs a path to a lockfile:

    \Skeleton\Lock\Config::$locking_handler_config = [
    	'path' => '/path/to/lockfile/directory',
    ];
