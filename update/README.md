Upgrading a lovestack installation
==
The lovestack is a fork of the eZ Publish legacy
project. This document describes the process of
upgrade from an eZ Publish legacy version to the
latest lovestack version.
If you already use the lovestack and just want
to upgrade to the latest version you should look
at _Upgrading to the latest version of the lovestack_

General notes
--
Consider making a backup of your installation. That
includes your current code version, your database content
and your content in the var folder (assets like images etc).


Upgrading from an eZ Publish legacy version
==
Before you can use the lovestack version you need
upgrade your eZ Publish legacy installation to the version 5.4.x

The upgrade path is documented until version 5.2. You can
find the documentation here: https://doc.ez.no/eZ-Publish/Upgrading

Further upgrade from 5.2 to 5.4 is documented here:
https://doc.ez.no/display/EZP/Updating

You only have to upgrade your eZ Publish legacy installation.
There is no need to setup the eZ Publish "new stack".

If you don't have access to the ezp releases and use
the code from the git repository you want to make sure
to upgrade until you reach the 2017.08.1.1 release version:
https://github.com/ezsystems/ezpublish-legacy/releases/tag/v2017.08.1.1
Do not upgrade to a newer eZ Publish release.

After this upgrade you need to follow the instructions of
_Upgrading to the latest version of the lovestack_.

Upgrading to the latest version of the lovestack
--

This instruction assumes that you have an existing ezp
publish installation on your system (for example under
_/var/www/ezp_).
It also assumes that you developed all your customizations
in a custom eZ Publish extension and that all changes to settings
are under the _override_ folder or under a _siteaccess_ folder.


Clone the lovestack code repository in a separate directory
```
cd /tmp
git clone https://github.com/mugoweb/ezpublish-legacy.git
```

Override your installation with the new code version
```
rsync -av /tmp/ezpublish-legacy/* /var/www/ezp
```

Run the DB update script to make sure you have the
latest DB schema version. The script needs the correct
parameters in order to know how to connect to your DB.
```
cd /var/www/ezp
php update/run.php #In order to get the help screen
php update/run.php <parameters> #provide the necessary parameters
```

Now rebuild the autoload map and clear the cache
```
cd /var/www/ezp
php bin/php/ezpgenerateautoloads.php -e
php bin/php/ezcache.php --clear-all
```

The upgrade process is now done. In case your installation is
connected to a code repository, you can now commit the changes
to that repository.

Store the commit hash in eZ Publish
--
It will allow youto always check what code version of the lovestack you're
running. You get the commit hash with the command `git rev-parse HEAD`.
That hash string needs to be added to `lib/version.php`.

```
cd /tmp/ezpublish-legacy
sed -i "/const GIT_COMMIT_HASH = '.*';/c\    const GIT_COMMIT_HASH = '$(git rev-parse HEAD)';" /var/www/ezp/lib/version.php
```

Notes about zeta components
--
eZ Publish decided to rely on the zeta components being installed
in the vendor directory. That is the case if you install/upgrade
the eZ Publish version with composer. Previous versions of eZ Publish
supported a zeta component installation in different locations on
the system.
Upgrading from older versions of eZ Publish probably will require you
to run composer to install the zeta components into the vendor directory
```
composer.phar update
```

Notes about legacy_2018 extension
--
The lovestack fork has an eZ Publish extension called _legacy_2018_.
It contains features that are considered old and unwanted in the
core system of eZ Publish ( _kernel_ or _lib_ feature). In most cases
you don't want to enable this extension unless your project depends
on the feature that is now located in the extension. See the extension
README.md file for more details
