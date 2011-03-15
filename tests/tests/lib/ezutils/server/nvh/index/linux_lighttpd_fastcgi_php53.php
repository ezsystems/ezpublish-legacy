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
  'PHP_VERSION' => '5.3.2-1ubuntu4.5',
  'PHP_OS' => 'Linux',
  'PHP_SAPI' => 'cgi-fcgi',
  'php_uname' => 'Linux fortress 2.6.32-24-generic-pae #43-Ubuntu SMP Thu Sep 16 15:30:27 UTC 2010 i686',
  'DIRECTORY_SEPARATOR' => '/',
  'PHP_SHLIB_SUFFIX' => 'so',
  'PATH_SEPARATOR' => ':',
  'DEFAULT_INCLUDE_PATH' => '.:/usr/share/php:/usr/share/pear',
  'include_path' => '.:/usr/share/php:/usr/share/pear',
  'PHP_MAXPATHLEN' => 4096,
  'PHP_EOL' => '
',
  'PHP_INT_MAX' => 2147483647,
  'PHP_INT_SIZE' => 4,
  'getcwd' => '/var/www',
  '_SERVER' =>
  array (
    'PATH' => '/sbin:/bin:/usr/sbin:/usr/bin',
    'SHELL' => '/bin/sh',
    'PHP_FCGI_CHILDREN' => '4',
    'PHP_FCGI_MAX_REQUESTS' => '10000',
    'FCGI_ROLE' => 'RESPONDER',
    'SERVER_SOFTWARE' => 'lighttpd/1.4.26',
    'SERVER_NAME' => 'fortress',
    'GATEWAY_INTERFACE' => 'CGI/1.1',
    'SERVER_PORT' => '80',
    'SERVER_ADDR' => '127.0.0.1',
    'REMOTE_PORT' => '60959',
    'REMOTE_ADDR' => '127.0.0.1',
    'SCRIPT_NAME' => '/index.php',
    'PATH_INFO' => '',
    'SCRIPT_FILENAME' => '/var/www/index.php',
    'DOCUMENT_ROOT' => '/var/www/',
    'REQUEST_URI' => '/index.php?get=value',
    'QUERY_STRING' => 'get=value',
    'REQUEST_METHOD' => 'GET',
    'REDIRECT_STATUS' => '200',
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'HTTP_HOST' => 'fortress',
    'HTTP_CONNECTION' => 'keep-alive',
    'HTTP_ACCEPT' => 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.11 Safari/534.10',
    'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
    'HTTP_ACCEPT_LANGUAGE' => 'fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4',
    'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
    'HTTP_COOKIE' => '',
    'PHP_SELF' => '/index.php',
    'REQUEST_TIME' => 1288107641,
  ),
  '_ENV' =>
  array (
  ),
);
