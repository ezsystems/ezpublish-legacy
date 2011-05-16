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
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
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
  'getcwd' => '/Users/oms/Sites/ezp44ee_final',
  '_SERVER' =>
  array (
    'HTTP_USER_AGENT' => 'curl/7.21.1 (x86_64-apple-darwin10.4.0) libcurl/7.21.1 OpenSSL/1.0.0a zlib/1.2.5 libidn/1.19',
    'HTTP_HOST' => 'localhost:8080',
    'HTTP_ACCEPT' => '*/*',
    'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
    'SERVER_SIGNATURE' => '<address>Apache/2.2.16 (Unix) DAV/2 PHP/5.3.3 Server at localhost Port 8080</address>
',
    'SERVER_SOFTWARE' => 'Apache/2.2.16 (Unix) DAV/2 PHP/5.3.3',
    'SERVER_NAME' => 'localhost',
    'SERVER_ADDR' => '127.0.0.1',
    'SERVER_PORT' => '8080',
    'REMOTE_ADDR' => '127.0.0.1',
    'DOCUMENT_ROOT' => '/opt/local/apache2/htdocs',
    'SERVER_ADMIN' => 'madeup@ez.no',
    'SCRIPT_FILENAME' => '/Users/oms/Sites/ezp44ee_final/index.php',
    'REMOTE_PORT' => '59861',
    'GATEWAY_INTERFACE' => 'CGI/1.1',
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'REQUEST_METHOD' => 'GET',
    'QUERY_STRING' => 'get=value',
    'REQUEST_URI' => '/~oms/ezp44ee_final/index.php?get=value',
    'SCRIPT_NAME' => '/~oms/ezp44ee_final/index.php',
    'PHP_SELF' => '/~oms/ezp44ee_final/index.php',
    'REQUEST_TIME' => 1288255333,
  ),
  '_ENV' =>
  array (
  ),
);
