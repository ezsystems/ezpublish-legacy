<?php
//
// Definition of Example class
//
// Created on: <17-Apr-2002 15:01:32 amos>
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

/*!
 Parses the template text $data and replaces all %var% items with the corresponding
 template variables found in $vars.
 $vars is an associative array with the key being the template variable name(without the %)
 and the value the corresponding text.
 Returns the resulting text.
 Example.
 simpleParse( "this is a %my_var%", array( "my_var" => "test" ) );
*/
function simpleParse( $data, $vars )
{
    $match_vars = array();
    foreach( $vars as $key => $var )
    {
        $match_vars[] = "%$key%";
    }
    return str_replace( $match_vars, $vars, $data );
}

?>
