Aplia APublish 1.0 (Fork of eZ Publish legacy)
=======================================================

[![Build Status](https://img.shields.io/travis/Aplia/apublish.svg?style=flat-square&branch=master)](https://travis-ci.org/Aplia/apublish)
[![Latest Stable Version](https://img.shields.io/packagist/v/aplia/apublish.svg?style=flat-square)](https://packagist.org/packages/aplia/apublish)
[![Latest version](https://img.shields.io/github/release/aplia/apublish.svg?style=flat-square)](https://github.com/aplia/apublish/releases)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3-8892BF.svg?style=flat-square)](https://php.net/)
[![License](https://img.shields.io/github/license/aplia/apublish.svg?style=flat-square)](LICENSE)

What is Aplia Apublish ?
-------------------

Aplia Apublish is a fork of [eZ Publish legacy](https://github.com/ezsystems/ezpublish-legacy)
which includes a set for small improvements

* ```composer.json``` is clean (no circular dependencies)
* Define override folders for INI files, ie. ```settings/override``` may be inside your project extension. Same for site access folders.
* Improvements for developers
  * Disabling of error handler and shutdown handlers, to replace with external libraries
  * Redirection of `eZDebug` output to a `PSR` logger
  * Disable modifying of file permissions for created files, instead let the filesystem define permissions
* PHP 7+ fixes

#### How to merge with eZ Publish legacy

```
git co -b feature/201x.x.merge
git remote add ezsystems https://github.com/ezsystems/ezpublish-legacy
git fetch ezsystems
git merge ezsystems/master
git co master
git merge feature/201x.x.merge
```

Push and create a new release.
