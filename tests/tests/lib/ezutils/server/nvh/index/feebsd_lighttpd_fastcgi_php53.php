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
  'PHP_VERSION' => '5.2.12',
  'PHP_OS' => 'FreeBSD',
  'PHP_SAPI' => 'cgi-fcgi',
  'php_uname' => 'FreeBSD something.something.net 7.2-RELEASE-p1 FreeBSD 7.2-RELEASE-p1 #3: Sun Jun 14 17:55:23 CEST 2009     root@luthien.ethiel.net:/usr/src/sys/i386/compile/BEREN i386',
  'DIRECTORY_SEPARATOR' => '/',
  'PHP_SHLIB_SUFFIX' => 'so',
  'PATH_SEPARATOR' => ':',
  'DEFAULT_INCLUDE_PATH' => '.:/usr/local/share/pear',
  'include_path' => '.:/usr/local/share/pear',
  'PHP_MAXPATHLEN' => NULL,
  'PHP_EOL' => '
',
  'PHP_INT_MAX' => 2147483647,
  'PHP_INT_SIZE' => 4,
  'getcwd' => '/usr/local/www/something.something.net/data',
  '_SERVER' =>
  array (
    'PHP_FCGI_MAX_REQUESTS' => '1000',
    'PHP_FCGI_CHILDREN' => '5',
    'USER' => 'www',
    'PATH' => '/sbin:/bin:/usr/sbin:/usr/bin:/usr/games:/usr/local/sbin:/usr/local/bin',
    'FCGI_ROLE' => 'RESPONDER',
    'SERVER_SOFTWARE' => 'lighttpd/1.4.26',
    'SERVER_NAME' => 'something.something.net',
    'GATEWAY_INTERFACE' => 'CGI/1.1',
    'SERVER_PORT' => '80',
    'SERVER_ADDR' => '127.0.0.1',
    'REMOTE_PORT' => '60991',
    'REMOTE_ADDR' => '127.0.0.1',
    'SCRIPT_NAME' => '/index.php',
    'PATH_INFO' => '',
    'SCRIPT_FILENAME' => '/usr/local/www/something.something.net/data/index.php',
    'DOCUMENT_ROOT' => '/usr/local/www/something.something.net/data/',
    'REQUEST_URI' => '/index.php?get=value',
    'QUERY_STRING' => 'get=value',
    'REQUEST_METHOD' => 'GET',
    'REDIRECT_STATUS' => '200',
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'HTTP_HOST' => 'something.something.net',
    'HTTP_CONNECTION' => 'keep-alive',
    'HTTP_ACCEPT' => 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.11 Safari/534.10',
    'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
    'HTTP_ACCEPT_LANGUAGE' => 'fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4',
    'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
    'HTTP_COOKIE' => '',
    'PHP_SELF' => '/index.php',
    'REQUEST_TIME' => 1288107924,
  ),
  '_ENV' =>
  array (
  ),
);
