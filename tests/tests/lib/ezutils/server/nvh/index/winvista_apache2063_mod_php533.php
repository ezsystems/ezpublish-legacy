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
  'PHP_OS' => 'WINNT',
  'PHP_SAPI' => 'apache2handler',
  'php_uname' => 'Windows NT LAPTOP-2 6.0 build 6002 (Windows Vista Home Premium Edition Service Pack 2) i586',
  'DIRECTORY_SEPARATOR' => '\\',
  'PHP_SHLIB_SUFFIX' => 'dll',
  'PATH_SEPARATOR' => ';',
  'DEFAULT_INCLUDE_PATH' => '.;C:\\php5\\pear',
  'include_path' => '.;D:\\htdocs\\phpxmlrpc\\trunk\\xmlrpc\\lib;D:\\htdocs\\ezc\\releases\\ezcomponents-2009.2.1\\',
  'PHP_MAXPATHLEN' => 260,
  'PHP_EOL' => '
',
  'PHP_INT_MAX' => 2147483647,
  'PHP_INT_SIZE' => 4,
  'getcwd' => 'D:\\htdocs\\ezvarlogger',
  '_SERVER' =>
  array (
    'HTTP_HOST' => 'localhost',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.2.11) Gecko/20101012 Firefox/3.6.11 (.NET CLR 3.5.30729)',
    'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.8,it;q=0.5,fr;q=0.3',
    'HTTP_ACCEPT_ENCODING' => 'gzip,deflate',
    'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
    'HTTP_KEEP_ALIVE' => '115',
    'HTTP_CONNECTION' => 'keep-alive',
    'HTTP_COOKIE' => '',
    'PATH' => 'C:\\Program Files\\PC Connectivity Solution\\;C:\\Windows\\system32;C:\\Windows;C:\\Windows\\System32\\Wbem;D:\\UnxUtils\\usr\\local\\wbin;D:\\Perl\\site\\bin;D:\\Perl\\bin;C:\\Program Files\\MySQL\\MySQL Server 5.1\\bin;D:\\oracle\\product\\11.2.0\\db_1\\bin;D:\\oracle\\product\\10.2.0\\db_1\\bin;c:\\Program Files\\Microsoft SQL Server\\100\\Tools\\Binn\\;c:\\Program Files\\Microsoft SQL Server\\100\\DTS\\Binn\\;C:\\Program Files\\ImageMagick-6.6.4-Q16;D:\\usr\\bin;c:\\Program Files\\OpenOffice.org 3\\program\\;C:\\Program Files\\TortoiseSVN\\bin;C:\\Program Files\\TortoiseGit\\bin;C:\\Program Files\\CollabNet Subversion Client;C:\\Program Files\\CVSNT\\;C:\\Program Files\\QuickTime\\QTSystem\\;C:\\Program Files\\doxygen\\bin',
    'SystemRoot' => 'C:\\Windows',
    'COMSPEC' => 'C:\\Windows\\system32\\cmd.exe',
    'PATHEXT' => '.COM;.EXE;.BAT;.CMD;.VBS;.VBE;.JS;.JSE;.WSF;.WSH;.MSC',
    'WINDIR' => 'C:\\Windows',
    'SERVER_SIGNATURE' => '<address>Apache/2.0.63 (Win32) PHP/5.3.3 Server at localhost Port 80</address>
',
    'SERVER_SOFTWARE' => 'Apache/2.0.63 (Win32) PHP/5.3.3',
    'SERVER_NAME' => 'localhost',
    'SERVER_ADDR' => '127.0.0.1',
    'SERVER_PORT' => '80',
    'REMOTE_ADDR' => '127.0.0.1',
    'DOCUMENT_ROOT' => 'D:/htdocs/',
    'SERVER_ADMIN' => 'madeup@ez.no',
    'SCRIPT_FILENAME' => 'D:/htdocs/ezvarlogger/index.php',
    'REMOTE_PORT' => '34654',
    'GATEWAY_INTERFACE' => 'CGI/1.1',
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'REQUEST_METHOD' => 'GET',
    'QUERY_STRING' => 'get=value',
    'REQUEST_URI' => '/ezvarlogger/index.php?get=value',
    'SCRIPT_NAME' => '/ezvarlogger/index.php',
    'PHP_SELF' => '/ezvarlogger/index.php',
    'REQUEST_TIME' => 1287779998,
  ),
  '_ENV' =>
  array (
    'ALLUSERSPROFILE' => 'C:\\ProgramData',
    'APPDATA' => 'C:\\Windows\\system32\\config\\systemprofile\\AppData\\Roaming',
    'asl_log' => 'Destination=file;OnFirstLog=command,environment',
    'CLASSPATH' => '.;C:\\Program Files\\Java\\jre6\\lib\\ext\\QTJava.zip',
    'commonfiles' => 'C:\\Program Files\\Common Files',
    'CommonProgramFiles' => 'C:\\Program Files\\Common Files',
    'COMPUTERNAME' => 'LAPTOP-2',
    'ComSpec' => 'C:\\Windows\\system32\\cmd.exe',
    'configsetroot' => 'C:\\Windows\\ConfigSetRoot',
    'DFSTRACINGON' => 'FALSE',
    'FP_NO_HOST_CHECK' => 'NO',
    'GIT_SSH' => 'C:\\Program Files\\TortoiseSVN\\bin\\TortoisePlink.exe',
    'LANG' => 'en',
    'LOCALAPPDATA' => 'C:\\Windows\\system32\\config\\systemprofile\\AppData\\Local',
    'mydocuments' => 'C:\\Windows\\system32\\config\\systemprofile\\Desktop',
    'NUMBER_OF_PROCESSORS' => '2',
    'OPENSSL_CONF' => 'D:\\OpenSSL\\bin\\openssl.cfg',
    'OS' => 'Windows_NT',
    'Path' => 'C:\\Program Files\\PC Connectivity Solution\\;C:\\Windows\\system32;C:\\Windows;C:\\Windows\\System32\\Wbem;D:\\UnxUtils\\usr\\local\\wbin;D:\\Perl\\site\\bin;D:\\Perl\\bin;C:\\Program Files\\MySQL\\MySQL Server 5.1\\bin;D:\\oracle\\product\\11.2.0\\db_1\\bin;D:\\oracle\\product\\10.2.0\\db_1\\bin;c:\\Program Files\\Microsoft SQL Server\\100\\Tools\\Binn\\;c:\\Program Files\\Microsoft SQL Server\\100\\DTS\\Binn\\;C:\\Program Files\\ImageMagick-6.6.4-Q16;D:\\usr\\bin;c:\\Program Files\\OpenOffice.org 3\\program\\;C:\\Program Files\\TortoiseSVN\\bin;C:\\Program Files\\TortoiseGit\\bin;C:\\Program Files\\CollabNet Subversion Client;C:\\Program Files\\CVSNT\\;C:\\Program Files\\QuickTime\\QTSystem\\;C:\\Program Files\\doxygen\\bin',
    'PATHEXT' => '.COM;.EXE;.BAT;.CMD;.VBS;.VBE;.JS;.JSE;.WSF;.WSH;.MSC',
    'PERL5LIB' => 'D:\\oracle\\product\\10.2.0\\db_1\\perl\\5.8.3\\lib\\MSWin32-x86;D:\\oracle\\product\\10.2.0\\db_1\\perl\\5.8.3\\lib;D:\\oracle\\product\\10.2.0\\db_1\\perl\\5.8.3\\lib\\MSWin32-x86;D:\\oracle\\product\\10.2.0\\db_1\\perl\\site\\5.8.3;D:\\oracle\\product\\10.2.0\\db_1\\perl\\site\\5.8.3\\lib;D:\\oracle\\product\\10.2.0\\db_1\\sysman\\admin\\scripts;D:\\oracle\\product\\10.2.0\\db_1\\perl\\lib\\5.8.3\\MSWin32-x86;D:\\oracle\\product\\10.2.0\\db_1\\perl\\lib\\5.8.3;D:\\oracle\\product\\10.2.0\\db_1\\perl\\5.8.3\\lib\\MSWin32-x86-multi-thread;D:\\oracle\\product\\10.2.0\\db_1\\perl\\site\\5.8.3;D:\\oracle\\product\\10.2.0\\db_1\\perl\\site\\5.8.3\\lib;D:\\oracle\\product\\10.2.0\\db_1\\sysman\\admin\\scripts',
    'PHP_INI_SCAN_DIR' => 'd:\\php5\\extras',
    'PROCESSOR_ARCHITECTURE' => 'x86',
    'PROCESSOR_IDENTIFIER' => 'x86 Family 6 Model 23 Stepping 6, GenuineIntel',
    'PROCESSOR_LEVEL' => '6',
    'PROCESSOR_REVISION' => '1706',
    'progfiles' => 'C:\\Program Files',
    'ProgramData' => 'C:\\ProgramData',
    'ProgramFiles' => 'C:\\Program Files',
    'PUBLIC' => 'C:\\Users\\Public',
    'QTJAVA' => 'C:\\Program Files\\Java\\jre6\\lib\\ext\\QTJava.zip',
    'SVN_SSH' => '"C:\\\\Program Files\\\\TortoiseSVN\\\\bin\\\\TortoisePlink.exe"',
    'systemdir' => 'C:\\Windows\\system32',
    'SystemDrive' => 'C:',
    'SystemRoot' => 'C:\\Windows',
    'TEMP' => 'C:\\Windows\\TEMP',
    'TMP' => 'C:\\Windows\\TEMP',
    'TRACE_FORMAT_SEARCH_PATH' => '\\\\NTREL202.ntdev.corp.microsoft.com\\4F18C3A5-CA09-4DBD-B6FC-219FDD4C6BE0\\TraceFormat',
    'USERDOMAIN' => 'WORKGROUP',
    'USERNAME' => 'LAPTOP-2$',
    'USERPROFILE' => 'C:\\Windows\\system32\\config\\systemprofile',
    'VS90COMNTOOLS' => 'C:\\Program Files\\Microsoft Visual Studio 9.0\\Common7\\Tools\\',
    'windir' => 'C:\\Windows',
    'AP_PARENT_PID' => '6084',
  ),
);
