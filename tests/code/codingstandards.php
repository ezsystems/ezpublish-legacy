<?php
//
// Definition of Test class
//
// Created on: <04-Nov-2002 12:12:53 amos>
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

/*! \file test.php
*/

/*!
  \class Test test.php
  \brief The class Test does

*/


foreach ( $a as $b ) // #1 correct
{
}

foreach( $a as $b ) // #2
{
}

foreach  ( $a as $b ) // #3
{
}

foreach ($a as $b) // #4
{
}

foreach ( $a as $b ) { // #5
}

foreach ( $a_b as $c_d ) // #6 correct
{
}

/* #7 correct */ foreach ( $a as $b )
{
}

foreach ( array_keys( $assignedNodes ) as $key ) // #8 correct
{
}

foreach ( $tpl_vars as $tpl_var_name => $tpl_var_value ) // #9 correct
{
}

foreach ( $currentVersion->attributes() as $attribute ) // #10 correct
{
}

foreach ( $this->attribute( 'policies' ) as $policy ) // #11 correct
{
}

foreach ( $this->attribute( 'policies', $abc, 'asdfasdf' ) as $policy ) // #12 correct
{
}

?>
