<?php
//
// Created on: <16-May-2002 14:17:46 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

?>

<h1>Fetching variables</h1>

<p>
Fetching post, get and session variables are normally done by accessing the
global HTTP_POST_VARS, _GET and HTTP_SESSION_VARS variables.
</p>

<h2>Fetching get/post variables</h2>
<p>
The first thing you do when you want to fetch variables is fetch a unique instance.
Then we check if the variable exists and fetch it.
</p>

<pre class="example">
$http =& eZHTTPTool::instance();
// Fetching a post variable
if ( $http->hasPostVariable( "variableA" ) )
    $variableA =& $http->postVariable( "variableA" );
// Fetching a get or post variable
if ( $http->hasVariable( "variableB" ) )
    $variableB =& $http->variable( "variableB" );
</pre>

<h2>Fetching session variables</h2>
<p>
Session variables can be both fetched and set and uses a syntax similar to post/get variables.
</p>

<pre class="example">
// Is the variable set?
if ( $http->hasSessionVariable( "variableC" ) )
    $variableC =& $http->sessionVariable( "variableC" );
else // If not we set it
    $http->setSessionVariable( "variableC", "data" );
</pre>
