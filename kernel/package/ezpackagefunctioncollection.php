<?php
//
// Definition of eZPackageFunctionCollection class
//
// Created on: <11-Aug-2003 18:30:26 amos>
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

/*! \file ezpackagefunctioncollection.php
*/

/*!
  \class eZPackageFunctionCollection ezpackagefunctioncollection.php
  \brief The class eZPackageFunctionCollection does

*/

include_once( 'kernel/error/errors.php' );

class eZPackageFunctionCollection
{
    /*!
     Constructor
    */
    function eZPackageFunctionCollection()
    {
    }

    function &fetchList( $offset, $limit )
    {
        include_once( 'kernel/classes/ezpackage.php' );
        $packageList =& eZPackage::fetchPackages( array( 'offset' => $offset,
                                                         'limit' => $limit ) );
        if ( $packageList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $packageList );
    }

    function &fetchPackage( $packageName )
    {
        include_once( 'kernel/classes/ezpackage.php' );
        $package =& eZPackage::fetch( $packageName );
        if ( $package === false )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $package );
    }
}

?>
