<?php
//
// This is the index_webdav.php file. Manages WebDAV sessions.
//
// Created on: <15-Aug-2003 15:15:15 bh>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "lib/ezwebdav/classes/ezwebdavcontentserver.php" );


// Check if the username & password actually contain someting, proceed
// only if empty values or if they are invalid (can't login):
if ( ( !isset( $PHP_AUTH_USER ) ) || ( !isset($PHP_AUTH_PW ) ) ||
     ( !ezuser::loginUser( $PHP_AUTH_USER, $PHP_AUTH_PW ) ) )

{
    header('HTTP/1.0 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="eZ publish WebDAV Admin interface [-*ALPHA!*-]"');
    echo( 'Authorization required!' );
}
// Else: non-empty & valid values were supplied: login successful!
else
{
    $testServer = new eZWebDAVContentServer ();
    $testServer->processClientRequest ();
}



// Without auth:
// $testServer = new eZWebDAVContentServer ();
// $testServer->processClientRequest ();


?>
