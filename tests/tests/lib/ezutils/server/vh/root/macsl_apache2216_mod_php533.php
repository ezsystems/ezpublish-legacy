<?php
/**
 * General test data for httpd server unit testing
 *
 * Provides data for testing routing, encoding, virtual host mode detection & more
 * on most httpd servers with different configurations.
 *
 * More info available on:
 * http://github.com/ezsystems/ezpublish/tree/master/tests/tests/lib/ezutils/server
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 * @subpackage lib
 *
 */

return array (
  'PHP_VERSION' => '5.3.3',
  'PHP_OS' => 'Darwin',
  'PHP_SAPI' => 'apache2handler',
  'php_uname' => 'Darwin somemac.local 10.4.0 Darwin Kernel Version 10.4.0: Fri Apr 23 18:28:53 PDT 2010; root:xnu-1504.7.4~1/RELEASE_I386 i386',
  'DIRECTORY_SEPARATOR' => '/',
  'PHP_SHLIB_SUFFIX' => 'so',
  'PATH_SEPARATOR' => ':',
  'DEFAULT_INCLUDE_PATH' => '.:/opt/local/lib/php',
  'include_path' => '.:/opt/local/lib/php',
  'PHP_MAXPATHLEN' => 1024,
  'PHP_EOL' => '
',
  'PHP_INT_MAX' => 9223372036854775807,
  'PHP_INT_SIZE' => 8,
  'getcwd' => '/Users/oms/dev/ez/ezp/bugfix/trunk',
  '_SERVER' =>
  array (
    'SCRIPT_URL' => '/',
    'SCRIPT_URI' => 'http://trunk/',
    'HTTP_USER_AGENT' => 'curl/7.21.1 (x86_64-apple-darwin10.4.0) libcurl/7.21.1 OpenSSL/1.0.0a zlib/1.2.5 libidn/1.19',
    'HTTP_HOST' => 'trunk',
    'HTTP_ACCEPT' => '*/*',
    'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
    'SERVER_SIGNATURE' => '<address>Apache/2.2.16 (Unix) DAV/2 PHP/5.3.3 Server at trunk Port 80</address>
',
    'SERVER_SOFTWARE' => 'Apache/2.2.16 (Unix) DAV/2 PHP/5.3.3',
    'SERVER_NAME' => 'trunk',
    'SERVER_ADDR' => '127.0.0.1',
    'SERVER_PORT' => '80',
    'REMOTE_ADDR' => '127.0.0.1',
    'DOCUMENT_ROOT' => '/Users/oms/dev/ez/ezp/bugfix/trunk',
    'SERVER_ADMIN' => 'something@localhost',
    'SCRIPT_FILENAME' => '/Users/oms/dev/ez/ezp/bugfix/trunk/index.php',
    'REMOTE_PORT' => '64188',
    'GATEWAY_INTERFACE' => 'CGI/1.1',
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'REQUEST_METHOD' => 'GET',
    'QUERY_STRING' => 'get=value',
    'REQUEST_URI' => '/?get=value',
    'SCRIPT_NAME' => '/',
    'PHP_SELF' => '/',
    'REQUEST_TIME' => 1288100278,
  ),
  '_ENV' =>
  array (
  ),
);
