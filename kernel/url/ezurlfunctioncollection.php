<?php
//
// Definition of eZURLFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
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

/*! \file ezcontentfunctioncollection.php
*/

/*!
  \class eZURLFunctionCollection ezurlfunctioncollection.php
  \brief The class eZURLFunctionCollection does

*/

include_once( 'kernel/error/errors.php' );

class eZURLFunctionCollection
{
    /*!
     Constructor
    */
    function eZURLFunctionCollection()
    {
    }

    function &fetchList( $isValid, $offset, $limit, $onlyPublished )
    {
        include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
        $parameters = array( 'is_valid' => $isValid,
                             'offset' => $offset,
                             'limit' => $limit,
                             'only_published' => $onlyPublished );
        $list =& eZURL::fetchList( $parameters );
        if ( $list === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$list );
    }

    function &fetchListCount( $isValid, $onlyPublished )
    {
        include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
        $parameters = array( 'is_valid' => $isValid,
                             'only_published' => $onlyPublished );
        $listCount =& eZURL::fetchListCount( $parameters );
        if ( $listCount === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$listCount );
    }

}

?>
