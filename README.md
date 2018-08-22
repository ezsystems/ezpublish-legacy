Aplia APublish 1.0 (Fork of eZ Publish legacy)
=======================================================

[![Build Status](https://img.shields.io/travis/Aplia/apublish.svg?style=flat-square&branch=master)](https://travis-ci.org/Aplia/apublish)
[![Latest version](https://img.shields.io/github/release/aplia/apublish.svg?style=flat-square)](https://github.com/aplia/apublish/releases)
[![License](https://img.shields.io/github/license/aplia/apublish.svg?style=flat-square)](LICENSE)

What is Aplia Apublish ?
-------------------

Aplia Apublish is a fork of [eZ Publish legacy](https://github.com/ezsystems/ezpublish-legacy)
which includes a set for small improvements

* ```composer.json``` is clean (no circular dependencies)
* ```settings/override``` may be but inside project extension
* improved development utilities

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
