<?php
//
// Definition of kernel include functions
//
// Created on: <05-Mar-2003 10:02:29 amos>
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

/*! \file ezincludefunctions.php
 Contains some useful kernel include functions which are nice to use in extensions.
*/

/*!
*/
function kernel_include( $name )
{
    $include = "kernel/$name";
    return include_once( $include );
}

/*!
*/
function kernel_common( $name )
{
    $name = strtolower( $name );
    $include = "kernel/common/$name.php";
    return include_once( $include );
}

/*!
*/
function datatype_class( $datatype, $className )
{
    $className = strtolower( $className );
    $include = "kernel/classes/datatypes/$datatype/$className.php";
    return include_once( $include );
}

?>
