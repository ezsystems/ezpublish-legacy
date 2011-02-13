===============================================
General test data for httpd server unit testing
===============================================

Intro
-----
This folder contains most important server variables available to php on different
httpd servers and setup with different configuration.
At time of writing: Apache, IIS, Nginx and Lighttpd on several OS's and with different conf.

It is in a php array format for easy use in php applications for unit/regression testing,
and its available in same license as eZ Publish (GPL v2), for other license options
contact eZ Systems (ez.no).

If you would like to contribute tests for some server, see end.


Use cases
---------
At the time of writing virtual host detection, routing and utf8 url encoding comes to mind.
But should be able to be used for any unit/regression tests for PHP applications that want to
test httpd server / OS differences.


PHP Versions
------------
Dataset only contains data for php versions above PHP 5.2.1 as this is the minimum requirements
for eZ Publish. For IIS, only 5.3+ is available since that is the minimum version supported by MS.


Variables
---------
Variables available is the result of the following php code:
(should work on all PHP 5.x versions, but have only been tested on 5.2+)
::
 if ( isset( $_GET['get'] ) && $_GET['get'] === 'value' )
 {
     header( 'Content-Type: text/php; charset=utf-8' );
     echo "<?php\n\nreturn " . var_export( array(
         'PHP_VERSION' => PHP_VERSION,
         'PHP_OS' => PHP_OS,
         'PHP_SAPI' => PHP_SAPI,
         'php_uname' => php_uname(),
         'DIRECTORY_SEPARATOR' => DIRECTORY_SEPARATOR,
         'PHP_SHLIB_SUFFIX' => PHP_SHLIB_SUFFIX,
         'PATH_SEPARATOR' => PATH_SEPARATOR,
         'DEFAULT_INCLUDE_PATH' => DEFAULT_INCLUDE_PATH,
         'include_path' => ini_get( 'include_path' ),
         'PHP_MAXPATHLEN' => defined('PHP_MAXPATHLEN') ? PHP_MAXPATHLEN : null,
         'PHP_EOL' => PHP_EOL,
         'PHP_INT_MAX' => PHP_INT_MAX,
         'PHP_INT_SIZE' => PHP_INT_SIZE,
         'getcwd' => getcwd(),
         '_SERVER' => $_SERVER,
         '_ENV' => isset( $_ENV ) ? $_ENV : null,
     ), true ) . ";\n";
     exit;
 }


Structure
---------
Dataset has the following folder structure:

::
  nvh
  \|-index
  \|-root
  \|-utf8
  \|-view
  
  vh
  \|-root
  \|-utf8
  \|-view

vh: Virtual host mode (aka no 'index.php' as normally accomplished with rewrite rules)

nvh: Non virtual host mode (aka has index.php in url)


index: "/index.php" specific to nvh mode.

root:  "/" without index.php in url on both vh and nvh test

utf8:  "[/index.php]/News/Blåbær-Øl-med-d'or-新闻军事社会体育中超" where [/index.php] is only added in nvh mode

view:  "[/index.php]/content/view/full/44" Same as utf8, but with a simpler url


Contribute
----------
Contributions is done with Githubs fork -> change -> pull request workflow.
More on that can be found on: <TODO: add ezp + git contribution link when live>

Using code from 'Variables' in your index.php file (or if on a running eZ Publish install you can place it temporary in your config.php file), should allow you to generate data.
The most important types of tests are root and utf8 and they are generated with the
following paths:

mode/type: vh/root
  http://<domain>/?get=value
mode/type: vh/utf8
  http://<domain>/News/Blåbær-Øl-med-d'or-新闻军事社会体育中超?get=value

mode/type: nvh/root
  http://localhost[/path]/?get=value
mode/type: nvh/utf8
  http://localhost[/path]/index.php/News/Blåbær-Øl-med-d'or-新闻军事社会体育中超?get=value

path: This is optional, some parts of the dataset contains sub paths, but not all!


File name and path to store them in is:
  server/<mode>/<type>/<os>_<httpd><httpd_version>_<php_mode>_php<php_version>.php

php_mode: The mode php is running in, either 'mod' (mod_php), 'fpm' or 'fastcgi'.

Eg:
  server/nvh/utf8/win7_iis75_fastcgi_php533.php

  server/vh/root/linux_nginx0852_fpm_php533.php


 
