<?php
//
// Definition of eZSection class
//
// Created on: <27-Aug-2002 15:55:18 bf>
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

/*!
  \class eZSection ezsection.php
  \brief eZSection handles grouping of content in eZ publish

*/

include_once( "kernel/classes/ezpersistentobject.php" );

class eZSection extends eZPersistentObject
{
    /*!
    */
    function eZSection( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZCard class.
    */
    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => 0,
                                                          'required' => true ),
                                         "navigation_part_identifier" => array( 'name' => "NavigationPartIdentifier",
                                                                                'datatype' => 'string',
                                                                                'default' => 'ezcontentnavigationpart',
                                                                                'required' => true ),
                                         "locale" => array( 'name' => "Locale",
                                                            'datatype' => 'string',
                                                            'default' => '',
                                                            'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZSection",
                      "name" => "ezsection" );
    }

    /*!
     \return the section object with the given id.
    */
    function &fetch( $sectionID, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZSection::definition(),
                                                null,
                                                array( "id" => $sectionID
                                                      ),
                                                $asObject );
    }

    function &fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZSection::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    function &fetchByOffset( $offset, $limit, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZSection::definition(),
                                                    null,
                                                    null,
                                                    array( 'name' => 'ASC' ),
                                                    array( 'offset' => $offset, 'length' => $limit ),
                                                    $asObject );
    }

     /*!
     \return the number of active orders
    */
    function &sectionCount()
    {
        $db =& eZDB::instance();

        $countArray = $db->arrayQuery(  "SELECT count( * ) AS count FROM ezsection" );
        return $countArray[0]['count'];
    }


    function attribute( $attr )
    {
        return eZPersistentObject::attribute( $attr );
    }

    function hasAttribute( $attr )
    {
        return eZPersistentObject::hasAttribute( $attr );
    }

    /*!
     Makes sure the global section ID is propagated to the template override key.
    */
    function initGlobalID()
    {
        global $eZSiteBasics;
        $sessionRequired = $eZSiteBasics['session-required'];
        $sectionID = false;
        if ( $sessionRequired )
        {
            include_once( 'lib/ezutils/classes/ezhttptool.php' );
            $sectionArray = array();
            if ( eZHTTPTool::hasSessionVariable( 'eZGlobalSection' ) )
                $sectionArray = eZHTTPTool::sessionVariable( 'eZGlobalSection' );
            if ( !isset( $sectionArray['id'] ) )
            {
                return false;
            }
            $sectionID = $sectionArray['id'];
        }

        if ( $sectionID )
        {
            // eZTemplateDesignResource will read this global variable
            $GLOBALS['eZDesignKeys']['section'] = $sectionID;
            return true;
        }
        return false;
    }

    /*!
     Sets the current global section ID to \a $sectionID in the session and
     the template override key.
    */
    function setGlobalID( $sectionID )
    {
        include_once( 'lib/ezutils/classes/ezhttptool.php' );
        $sectionArray = array();
        if ( eZHTTPTool::hasSessionVariable( 'eZGlobalSection' ) )
            $sectionArray = eZHTTPTool::sessionVariable( 'eZGlobalSection' );
        $sectionArray['id'] = $sectionID;
        eZHTTPTool::setSessionVariable( 'eZGlobalSection', $sectionArray );

        // eZTemplateDesignResource will read this global variable
        $GLOBALS['eZDesignKeys']['section'] = $sectionID;
    }

    /*!
     \return the global section ID or \c null if it is not set yet.
    */
    function globalID()
    {
        include_once( 'lib/ezutils/classes/ezhttptool.php' );
        if ( eZHTTPTool::hasSessionVariable( 'eZGlobalSection' ) )
        {
            $sectionArray = eZHTTPTool::sessionVariable( 'eZGlobalSection' );
            if ( isset( $sectionArray['id'] ) )
                return $sectionArray['id'];
        }
        return null;
    }

    /*!
     Will remove the current section from the database.
    */
    function remove( )
    {
        $def =& $this->definition();

        eZPersistentObject::removeObject( $def, array( "id" => $this->ID ) );
    }

}

?>
