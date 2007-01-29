<?php
//
// Definition of Test class
//
// Created on: <04-Nov-2002 12:12:53 amos>
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
