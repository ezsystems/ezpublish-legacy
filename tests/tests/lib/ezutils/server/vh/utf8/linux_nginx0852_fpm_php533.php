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
    'QUERY_STRING' => 'get=value',
    'REQUEST_METHOD' => 'GET',
    'CONTENT_TYPE' => '',
    'CONTENT_LENGTH' => '',
    'SCRIPT_NAME' => '/News/Blåbær-Øl-med-d\'or-新闻军事社会体育中超',
    'REQUEST_URI' => '/News/Bl%C3%A5b%C3%A6r-%C3%98l-med-d\'or-%E6%96%B0%E9%97%BB%E5%86%9B%E4%BA%8B%E7%A4%BE%E4%BC%9A%E4%BD%93%E8%82%B2%E4%B8%AD%E8%B6%85?get=value',
    'DOCUMENT_URI' => '/News/Blåbær-Øl-med-d\'or-新闻军事社会体育中超',
    'DOCUMENT_ROOT' => '/home/something/workspace/ezpublish',
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'GATEWAY_INTERFACE' => 'CGI/1.1',
    'SERVER_SOFTWARE' => 'nginx/0.8.52',
    'REMOTE_ADDR' => '127.0.0.1',
    'REMOTE_PORT' => '42934',
    'SERVER_ADDR' => '127.0.0.1',
    'SERVER_PORT' => '8080',
    'SERVER_NAME' => 'ezpublish',
    'REDIRECT_STATUS' => '200',
    'HTTP_HOST' => 'ezpublish:8080',
    'HTTP_CONNECTION' => 'keep-alive',
    'HTTP_CACHE_CONTROL' => 'max-age=0',
    'HTTP_ACCEPT' => 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.18 Safari/534.10',
    'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
    'HTTP_ACCEPT_LANGUAGE' => 'nb-NO,nb;q=0.8,no;q=0.6,nn;q=0.4,en-US;q=0.2,en;q=0.2,en-GB;q=0.2',
    'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
    'HTTP_COOKIE' => '',
    'PHP_SELF' => '/News/Blåbær-Øl-med-d\'or-新闻军事社会体育中超',
    'REQUEST_TIME' => 1288259398,
  ),
  '_ENV' =>
  array (
    'USER' => 'www-data',
    'HOME' => '/var/www',
    'FCGI_ROLE' => 'RESPONDER',
    'SCRIPT_FILENAME' => '/home/something/workspace/ezpublish/index.php',
    'QUERY_STRING' => 'get=value',
    'REQUEST_METHOD' => 'GET',
    'CONTENT_TYPE' => '',
    'CONTENT_LENGTH' => '',
    'SCRIPT_NAME' => '/News/Blåbær-Øl-med-d\'or-新闻军事社会体育中超',
    'REQUEST_URI' => '/News/Bl%C3%A5b%C3%A6r-%C3%98l-med-d\'or-%E6%96%B0%E9%97%BB%E5%86%9B%E4%BA%8B%E7%A4%BE%E4%BC%9A%E4%BD%93%E8%82%B2%E4%B8%AD%E8%B6%85?get=value',
    'DOCUMENT_URI' => '/News/Blåbær-Øl-med-d\'or-新闻军事社会体育中超',
    'DOCUMENT_ROOT' => '/home/something/workspace/ezpublish',
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'GATEWAY_INTERFACE' => 'CGI/1.1',
    'SERVER_SOFTWARE' => 'nginx/0.8.52',
    'REMOTE_ADDR' => '127.0.0.1',
    'REMOTE_PORT' => '42934',
    'SERVER_ADDR' => '127.0.0.1',
    'SERVER_PORT' => '8080',
    'SERVER_NAME' => 'ezpublish',
    'REDIRECT_STATUS' => '200',
    'HTTP_HOST' => 'ezpublish:8080',
    'HTTP_CONNECTION' => 'keep-alive',
    'HTTP_CACHE_CONTROL' => 'max-age=0',
    'HTTP_ACCEPT' => 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.18 Safari/534.10',
    'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
    'HTTP_ACCEPT_LANGUAGE' => 'nb-NO,nb;q=0.8,no;q=0.6,nn;q=0.4,en-US;q=0.2,en;q=0.2,en-GB;q=0.2',
    'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
    'HTTP_COOKIE' => '',
  ),
);
