<?php
//
// Created on: <01-Oct-2002 13:23:07 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file
  Contains all the basic kernel and kernel related error codes.
*/

class eZError
{

/*!
 Access denied to object or module.
*/
const KERNEL_ACCESS_DENIED = 1;
/*!
 The object could not be found.
*/
const KERNEL_NOT_FOUND = 2;
/*!
 The object is not available.
*/
const KERNEL_NOT_AVAILABLE = 3;
/*!
 The object is moved.
*/
const KERNEL_MOVED = 4;
/*!
 The language is not found.
*/
const KERNEL_LANGUAGE_NOT_FOUND = 5;

/*!
 The module could not be found.
*/
const KERNEL_MODULE_NOT_FOUND = 20;
/*!
 The module view could not be found.
*/
const KERNEL_MODULE_VIEW_NOT_FOUND = 21;
/*!
 The module or view is not enabled.
*/
const KERNEL_MODULE_DISABLED = 22;


/*!
 No database connection
*/
const KERNEL_NO_DB_CONNECTION = 50;

//Shop system error codes
const SHOP_OK = 0;
const SHOP_NOT_A_PRODUCT = 1;
const SHOP_BASKET_INCOMPATIBLE_PRODUCT_TYPE = 2;
const SHOP_PREFERRED_CURRENCY_DOESNOT_EXIST = 3;
const SHOP_PREFERRED_CURRENCY_INACTIVE = 4;


}

?>
