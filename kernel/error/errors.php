<?php
//
// Created on: <01-Oct-2002 13:23:07 amos>
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

/*! \file errors.php
  Contains all the basic kernel and kernel related error codes.
*/

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
