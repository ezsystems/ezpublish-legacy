<?php
//
// Definition of eZClassFunctionCollection class
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

/*! \file ezclassfunctioncollection.php
*/

/*!
  \class eZClassFunctionCollection ezclassfunctioncollection.php
  \brief The class eZClassFunctionCollection does

*/

include_once( 'kernel/error/errors.php' );

class eZClassFunctionCollection
{
    /*!
     Constructor
    */
    function eZClassFunctionCollection()
    {
    }

    function &fetchClassList( $classFilter )
    {
        $contentClassList = array();
        if ( !is_array( $classFilter ) or
             count( $classFilter ) > 0 )
        {
            include_once( 'kernel/classes/ezcontentclass.php' );
            $contentClassList =& eZContentClass::fetchList( 0, true, false,
                                                            null, null,
                                                            $classFilter );
        }
        if ( $contentClassList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $contentClassList );
    }

    function &fetchLatestClassList( $offset, $limit )
    {
        $contentClassList = array();
        include_once( 'kernel/classes/ezcontentclass.php' );
        $limitData = null;
        if ( $limit )
            $limitData = array( 'offset' => $offset,
                                'length' => $limit );
        $contentClassList =& eZContentClass::fetchList( 0, true, false,
                                                        array( 'modified' => 'desc' ), null,
                                                        false, $limitData );
        return array( 'result' => $contentClassList );
    }

    function &fetchClassAttributeList( $classID )
    {
        include_once( 'kernel/classes/ezcontentclass.php' );
        $contentClassAttributeList =& eZContentClass::fetchAttributes( $classID );
        if ( $contentClassAttributeList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $contentClassAttributeList );
    }

    function &fetchOverrideTemplateList( $classID )
    {
        $class = eZContentClass::fetch( $classID );
        $classIdentifier = $class->attribute( 'identifier' );

        $result = array ();

        $ini =& eZINI::instance();

        $siteAccessArray = $ini->variable('SiteAccessSettings', 'AvailableSiteAccessList' );

        foreach ( $siteAccessArray as $siteAccess )
        {
            $overrides = eZTemplateDesignResource::overrideArray( $siteAccess );

            foreach( $overrides as $override )
            {
                if ( isset( $override['custom_match'] ) )
                {
                    foreach( $override['custom_match'] as $customMatch )
                    {
                        if( isset( $customMatch['conditions']['class_identifier'] ) &&
                            $customMatch['conditions']['class_identifier'] == $classIdentifier )
                        {
                            $result[] = array( 'siteaccess' => $siteAccess,
                                               'block'      => $customMatch['override_name'],
                                               'source'     => $override['template'],
                                               'target'     => $customMatch['match_file'] );
                        }

                        if( isset( $customMatch['conditions']['class'] ) &&
                            $customMatch['conditions']['class'] == $classID )
                        {

                            $result[] = array( 'siteaccess' => $siteAccess,
                                               'block'      => $customMatch['override_name'],
                                               'source'     => $override['template'],
                                               'target'     => $customMatch['match_file'] );
                        }
                    }
                }
            }

        }

        return array( 'result' => $result );
    }

}

?>
