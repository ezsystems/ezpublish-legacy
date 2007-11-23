<?php
//
// Created on: <07-Apr-2005 13:17:57 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file errors.php
  Contains all the error codes for the shop module.
  /deprecated Use eZError class constants instead
*/

eZDebug::writeWarning( "All the constants in " . __FILE__ . " are deprecated, use eZError class constants instead" );

/*!
 The object is not a product.
*/
define( 'EZ_ERROR_SHOP_OK', 0 );
define( 'EZ_ERROR_SHOP_NOT_A_PRODUCT', 1 );
define( 'EZ_ERROR_SHOP_BASKET_INCOMPATIBLE_PRODUCT_TYPE', 2 );
define( 'EZ_ERROR_SHOP_PREFERRED_CURRENCY_DOESNOT_EXIST', 3 );
define( 'EZ_ERROR_SHOP_PREFERRED_CURRENCY_INACTIVE', 4 );
?>
