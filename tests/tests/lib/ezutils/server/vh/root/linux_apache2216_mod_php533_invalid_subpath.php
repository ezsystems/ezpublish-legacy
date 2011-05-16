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

/**
 * This specifc test, testing invalid $index.php and path in url
 * making sure code does not use it as basis for determin www dir and resulting RequestURI
 *
 * To accomplish that, this test case specifes what expected values should be in the end
 */
return array (
  'PHP_VERSION' => '5.3.3-1ubuntu9.1',
  'PHP_OS' => 'Linux',
  'PHP_SAPI' => 'apache2handler',
  'php_uname' => 'Linux andre-laptop 2.6.35-22-generic #35-Ubuntu SMP Sat Oct 16 20:45:36 UTC 2010 x86_64',
  'DIRECTORY_SEPARATOR' => '/',
  'PHP_SHLIB_SUFFIX' => 'so',
  'PATH_SEPARATOR' => ':',
  'DEFAULT_INCLUDE_PATH' => '.:/usr/share/php:/usr/share/pear',
  'include_path' => '.:/usr/share/php:/usr/share/pear',
  'PHP_MAXPATHLEN' => 4096,
  'PHP_EOL' => '
',
  'PHP_INT_MAX' => 9223372036854775807,
  'PHP_INT_SIZE' => 8,
  'getcwd' => '/home/andre/workspace/ezpublish',
  '_SERVER' =>
  array (
    'SCRIPT_URL' => '/SOMETHING/index.php',
    'SCRIPT_URI' => 'http://ezpublish/SOMETHING/index.php',
    'HTTP_HOST' => 'ezpublish',
    'HTTP_CONNECTION' => 'keep-alive',
    'HTTP_ACCEPT' => 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.18 Safari/534.10',
    'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
    'HTTP_ACCEPT_LANGUAGE' => 'nb-NO,nb;q=0.8,no;q=0.6,nn;q=0.4,en-US;q=0.2,en;q=0.2,en-GB;q=0.2',
    'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
    'HTTP_COOKIE' => 'adminNavigationTab=view; contentStructureMenu=2/61/5/12/14/94/44/95/43/87/74/100/107/13/109',
    'PATH' => '/usr/local/bin:/usr/bin:/bin',
    'SERVER_SIGNATURE' => '<address>Apache/2.2.16 (Ubuntu) Server at ezpublish Port 80</address>
',
    'SERVER_SOFTWARE' => 'Apache/2.2.16 (Ubuntu)',
    'SERVER_NAME' => 'ezpublish',
    'SERVER_ADDR' => '127.0.0.1',
    'SERVER_PORT' => '80',
    'REMOTE_ADDR' => '127.0.0.1',
    'DOCUMENT_ROOT' => '/home/andre/workspace/ezpublish/',
    'SERVER_ADMIN' => 'webmaster@ezpublish',
    'SCRIPT_FILENAME' => '/home/andre/workspace/ezpublish/index.php',
    'REMOTE_PORT' => '32866',
    'GATEWAY_INTERFACE' => 'CGI/1.1',
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'REQUEST_METHOD' => 'GET',
    'QUERY_STRING' => 'get=value',
    'REQUEST_URI' => '/SOMETHING/index.php?get=value',
    'SCRIPT_NAME' => '/SOMETHING/index.php',
    'PHP_SELF' => '/SOMETHING/index.php',
    'REQUEST_TIME' => 1288391507,
  ),
  '_ENV' =>
  array (
    'APACHE_RUN_DIR' => '/var/run/apache2',
    'APACHE_PID_FILE' => '/var/run/apache2.pid',
    'PATH' => '/usr/local/bin:/usr/bin:/bin',
    'APACHE_LOCK_DIR' => '/var/lock/apache2',
    'LANG' => 'C',
    'APACHE_RUN_GROUP' => 'www-data',
    'APACHE_RUN_USER' => 'www-data',
    'APACHE_LOG_DIR' => '/var/log/apache2',
    'PWD' => '/',
  ),
  // vaiables needed for specific edge cases where we want code to ignore invalid data like in this one
  '__out' =>
  array (
    'WWWDir' => '',
    'RequestURI' => '/SOMETHING/index.php',
  ),
);
