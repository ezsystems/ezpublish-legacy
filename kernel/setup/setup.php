<?php
//
// Created on: <06-May-2002 16:43:35 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//


$Module->setTitle( "Setting up eZ publish" );

ob_start();

print( '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
<title>Setting up eZ publish</title>
<body bgcolor="#4987bc" topmargin="0" marginheight="0" leftmargin="0" marginwidth="0">

<table width="100%">

' );

print( '<form enctype="multipart/form-data" method="post" action="' . $Module->uri() . '">
' );

print( "<tr><td>This is your first time starting eZ publish.
 Below you'll find a list of items which needs to be resolved.</td></tr>" );

include_once( "lib/ezutils/classes/ezhttptool.php" );

$http =& eZHttpTool::instance();
$mysql_sel = "";
$postgresql_sel = "";
$oracle_sel = "";

eZDebug::writeNotice( $GLOBALS["HTTP_POST_VARS"] );

if ( $http->hasPostVariable( "DBType" ) )
{
    switch ( $http->postVariable( "DBType" ) )
    {
        case "mysql":
        {
            $mysql_sel = "selected";
        } break;
        case "postgresql":
        {
            $postgresql_sel = "selected";
        } break;
        case "oracle":
        {
            $oracle_sel = "selected";
        } break;
        default:
        {
            $mysql_sel = "selected";
        } break;
    }
}
else
{
    $mysql_sel = "selected";
}

$db_name = "";
$db_user = "";
$db_pwd = "";
if ( $http->hasPostVariable( "DBName" ) )
    $db_name = $http->postVariable( "DBName" );
if ( $http->hasPostVariable( "DBUser" ) )
    $db_user = $http->postVariable( "DBUser" );
if ( $http->hasPostVariable( "DBPassword" ) )
    $db_pwd = $http->postVariable( "DBPassword" );


print( "<tr><td><h1>Database</h1></td></tr>" );
print( "<tr><td>Please select a database, the user and password then press initialize</td></tr>" );
print( '<tr><td><table cellspacing="0" cellpadding="4">
<tr><td>Type</td><td><select name="DBType">
<option value="mysql" ' . $mysql_sel . '>MySQL</option>
<option value="postgresql" ' . $postgresql_sel . '>PostgreSQL</option>
<option value="oracle" ' . $oracle_sel . '>Oracle</option>
</select></td></tr>
<tr><td>DBName</td><td><input type="text" name="DBName" size="25" value="' . $db_name . '" maxlength="60"></td></tr>
<tr><td>DBUser</td><td><input type="text" name="DBUser" size="25" value="' . $db_user . '" maxlength="60"></td></tr>
<tr><td>DBPassword</td><td><input type="password" name="DBPassword" size="25" value="' . $db_pwd . '" maxlength="60"></td></tr>
<tr><td>&nbsp;</td><td><input class="stdbutton" type="submit" Name="DBInit" value="Initialize"></td></tr>

</table></td></tr>' );

print( "</form>" );

print( '
</table>

<br />
<font color="white">
<center>
<a href="http://developer.ez.no">eZ publish&trade;</a> copyright &copy; 1999-2002 <a href="http://ez.no">eZ systems as</a>
</center>
</font>
<br />

</body>
</html>' );


$Result = array();
$Result["content"] = ob_get_contents();
$Result["pagelayout"] = false;

ob_end_clean();

?>
