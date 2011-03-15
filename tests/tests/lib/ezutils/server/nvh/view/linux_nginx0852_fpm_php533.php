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
  'PHP_VERSION' => '5.3.3-1ubuntu9.1',
  'PHP_OS' => 'Linux',
  'PHP_SAPI' => 'fpm-fcgi',
  'php_uname' => 'Linux something-laptop 2.6.35-22-generic #35-Ubuntu SMP Sat Oct 16 20:45:36 UTC 2010 x86_64',
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
  'getcwd' => '/home/something/workspace/ezpublish',
  '_SERVER' =>
  array (
    'USER' => 'www-data',
    'HOME' => '/var/www',
    'FCGI_ROLE' => 'RESPONDER',
    'SCRIPT_FILENAME' => '/home/something/workspace/ezpublish/index.php',
    'PATH_INFO' => '/content/view/full/44',
    'QUERY_STRING' => 'get=value',
    'REQUEST_METHOD' => 'GET',
    'CONTENT_TYPE' => '',
    'CONTENT_LENGTH' => '',
    'SCRIPT_NAME' => '/ezpublish/index.php',
    'REQUEST_URI' => '/ezpublish/index.php/content/view/full/44?get=value',
    'DOCUMENT_URI' => '/ezpublish/index.php/content/view/full/44',
    'DOCUMENT_ROOT' => '/home/something/workspace',
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'GATEWAY_INTERFACE' => 'CGI/1.1',
    'SERVER_SOFTWARE' => 'nginx/0.8.52',
    'REMOTE_ADDR' => '127.0.0.1',
    'REMOTE_PORT' => '38388',
    'SERVER_ADDR' => '127.0.0.1',
    'SERVER_PORT' => '8080',
    'SERVER_NAME' => 'ws',
    'REDIRECT_STATUS' => '200',
    'HTTP_HOST' => 'ws:8080',
    'HTTP_CONNECTION' => 'keep-alive',
    'HTTP_ACCEPT' => 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.11 Safari/534.10',
    'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
    'HTTP_ACCEPT_LANGUAGE' => 'nb-NO,nb;q=0.8,no;q=0.6,nn;q=0.4,en-US;q=0.2,en;q=0.2,en-GB;q=0.2',
    'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
    'HTTP_COOKIE' => '',
    'ORIG_PATH_INFO' => '/ezpublish/index.php/content/view/full/44',
    'ORIG_SCRIPT_NAME' => '/ezpublish/index.php/content/view/full/44',
    'ORIG_SCRIPT_FILENAME' => '/home/something/workspace/ezpublish/index.php/content/view/full/44',
    'PATH_TRANSLATED' => '/home/something/workspace/content/view/full/44',
    'PHP_SELF' => '/ezpublish/index.php/content/view/full/44',
    'REQUEST_TIME' => 1287920664,
  ),
  '_ENV' =>
  array (
    'USER' => 'www-data',
    'HOME' => '/var/www',
    'FCGI_ROLE' => 'RESPONDER',
    'SCRIPT_FILENAME' => '/home/something/workspace/ezpublish/index.php',
    'PATH_INFO' => '/content/view/full/44',
    'QUERY_STRING' => 'get=value',
    'REQUEST_METHOD' => 'GET',
    'CONTENT_TYPE' => '',
    'CONTENT_LENGTH' => '',
    'SCRIPT_NAME' => '/ezpublish/index.php',
    'REQUEST_URI' => '/ezpublish/index.php/content/view/full/44?get=value',
    'DOCUMENT_URI' => '/ezpublish/index.php/content/view/full/44',
    'DOCUMENT_ROOT' => '/home/something/workspace',
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'GATEWAY_INTERFACE' => 'CGI/1.1',
    'SERVER_SOFTWARE' => 'nginx/0.8.52',
    'REMOTE_ADDR' => '127.0.0.1',
    'REMOTE_PORT' => '38388',
    'SERVER_ADDR' => '127.0.0.1',
    'SERVER_PORT' => '8080',
    'SERVER_NAME' => 'ws',
    'REDIRECT_STATUS' => '200',
    'HTTP_HOST' => 'ws:8080',
    'HTTP_CONNECTION' => 'keep-alive',
    'HTTP_ACCEPT' => 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.11 Safari/534.10',
    'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
    'HTTP_ACCEPT_LANGUAGE' => 'nb-NO,nb;q=0.8,no;q=0.6,nn;q=0.4,en-US;q=0.2,en;q=0.2,en-GB;q=0.2',
    'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
    'HTTP_COOKIE' => '',
    'ORIG_PATH_INFO' => '/ezpublish/index.php/content/view/full/44',
    'ORIG_SCRIPT_NAME' => '/ezpublish/index.php/content/view/full/44',
    'ORIG_SCRIPT_FILENAME' => '/home/something/workspace/ezpublish/index.php/content/view/full/44',
    'PATH_TRANSLATED' => '/home/something/workspace/content/view/full/44',
  ),
);
