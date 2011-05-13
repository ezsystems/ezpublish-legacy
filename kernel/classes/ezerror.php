<?php
/**
 * File containing the eZError class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
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
