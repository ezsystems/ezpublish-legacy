<?php
//
// Created on: <28-Oct-2002 12:46:33 bf>
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

$baseURI = $Params["base_uri"];
$docName = $Params["part"];

$error = false;

$docFile = "sdk/tutorials/$docName.php";

if ( $docFile == "sdk/tutorials/tutorials.php" )
{
    $content = "PHP self include detected, avoiding recursive includes.";
}
else
{
    ob_start();
    if ( file_exists( $docFile ) )
        include( $docFile );
    $content = ob_get_contents();
    ob_end_clean();

    if ( isset( $DocResult ) and
         is_array( $DocResult ) )
    {
        if ( isset( $DocResult["title"] ) )
            $docName = $DocResult["title"];
    }
}

$Result = array();
$Result["title"] = "Document $docName";
$Result["content"] = $content;
$Result["pagelayout"] = false;
$Result["external_css"] = true;

?>
