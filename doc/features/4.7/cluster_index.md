# Revamped cluster index #

The cluster index mechanism has been almost completely rewritten in eZ Publish 4.7.

## Previous structure ##

Up until eZ Publish 4.6, serving of binary files was done through several files:

- a custom `index_cluster.php` (default name), that contained setup specific configuration
  constants
- the file `index_image.php`, included from `index_cluster.php`, that included
  `index_image_<specificbackend>.php`
- the `index_image_<specificbackend>.php` file, that actually contained the logic to serve
  the files

This structure was confusing, and added quite a few files at root level, something
we are trying to avoid

## Revamped structure ##

A unique file can now be found at document root level: `index_cluster.php`. This file will
be the one serving binary files, and must be the rewrite target.

Depending on the configuration (see below), this file will use a gateway class, specific
to each cluster backend, that can be found in kernel/clustering. Custom cluster index
gateways can be added my means of extensions.

### Configuration ###

Configuration is now done through the already existing `config.php`. As always with this file,
documentation can be found in the distributed `config.php-RECOMMENDED` file. For this rewrite, you will
find about a dozen new constants, all prefixed with `CLUSTER_`.
Optionaly, you can define those constants in a `config.cluster.php` file (needs to be created).
In that case, the constants will be only available for cluster environment.

Some of them are settings (database host, database, password, NFS path...), while some of them
are flags that can be enabled/disabled.

## New features ##

While this change makes the code structure way cleaner, it also comes up with added benefits:

- `eTag` & `IF-MODIFIED-SINCE` header support
- `HTTP RANGE` support, to support partial downloads (only for **DFS clusters**)
- unified error & debug handling