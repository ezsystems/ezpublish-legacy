<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  Contains all the basic kernel and kernel related error codes.
  /deprecated Use eZError class constants instead
*/

eZDebug::writeWarning( "All the constants in " . __FILE__ . " are deprecated, use eZError class constants instead" );

/*!
 Access denied to object or module.
*/
define( 'EZ_ERROR_KERNEL_ACCESS_DENIED', 1 );
/*!
 The object could not be found.
*/
define( 'EZ_ERROR_KERNEL_NOT_FOUND', 2 );
/*!
 The object is not available.
*/
define( 'EZ_ERROR_KERNEL_NOT_AVAILABLE', 3 );
/*!
 The object is moved.
*/
define( 'EZ_ERROR_KERNEL_MOVED', 4 );
/*!
 The language is not found.
*/
define( 'EZ_ERROR_KERNEL_LANGUAGE_NOT_FOUND', 5 );

/*!
 The module could not be found.
*/
define( 'EZ_ERROR_KERNEL_MODULE_NOT_FOUND', 20 );
/*!
 The module view could not be found.
*/
define( 'EZ_ERROR_KERNEL_MODULE_VIEW_NOT_FOUND', 21 );
/*!
 The module or view is not enabled.
*/
define( 'EZ_ERROR_KERNEL_MODULE_DISABLED', 22 );


/*!
 No database connection
*/
define( 'EZ_ERROR_KERNEL_NO_DB_CONNECTION', 50 );

?>
