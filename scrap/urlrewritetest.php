<?php
//
// Definition of A class
//
// Created on: <21-Aug-2003 14:19:03 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.

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
