<?php
//
// Definition of A class
//
// Created on: <21-Aug-2003 14:19:03 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
// 
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
// 
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
// 
// 
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

include_once( 'lib/ezutils/classes/ezhttptool.php' );

$urls = array();
// $urls[] = 'http://ez.no';
// $urls[] = 'http://ez.no:81';
// $urls[] = 'http://ez.no/index.php';
// $urls[] = 'http://ez.no/index.php/some/other/path//';
// $urls[] = 'http://ez.no/my/path/index.php';
// $urls[] = 'http://ez.no/my/path/index.php/extra/path';
// $urls[] = 'http://amos@ez.no/';
// $urls[] = 'http://amos:mofser@ez.no/';
// $urls[] = 'http://www.cortexttranslation.com/guide/index.php';
// $urls[] = 'http://www.cortexttranslation.com/guide/index.php/';
// $urls[] = 'http://www.cortexttranslation.com/guide/index.php/http://www.cortexttranslation.com/guide/index.php';

$params = array();
$params[] = array( 'override_host' => 'mysite.com' );
$params[] = array( 'override_port' => '8080' );
$params[] = array( 'override_username' => 'bf' );
$params[] = array( 'override_password' => 'abc' );
$params[] = array( 'override_username' => 'bf',
                   'override_password' => 'abc' );

foreach ( $urls as $url )
{
    $newURL = eZHTTPTool::createRedirectURL( $url );
    print( "$url => $newURL\n" );
    $i = 0;
    foreach ( $params as $param )
    {
        $newURL = eZHTTPTool::createRedirectURL( $url, $param );
        print( "param" . $i . ": $url => $newURL\n" );
        ++$i;
    }
}

?>
