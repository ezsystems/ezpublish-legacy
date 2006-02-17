<?php
//
// Definition of eZContentClassOperations class
//
// Created on: <23-Jan-2006 13:25:46 vs>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file ezcontentclassoperations.php
*/

/*!
  \class eZContentClassOperations ezcontentclassoperations.php
  \brief The class eZContentClassOperations is a place where
         content class operations are encapsulated.
  We move them out from eZContentClass because they may content code
  which is not directly related to content classes (e.g. clearing caches, etc).
*/

include_once( 'kernel/classes/ezcontentclass.php' );

class eZContentClassOperations
{
    /*!
     Removes content class and all data associated with it.
     \static
    */
    function remove( $classID )
    {
        $contentClass = eZContentClass::fetch( $classID );

        if ( !$contentClass->isRemovable() )
            return;

        // Remove all objects of this class, fething them partially to avoid memory exhaustion.
        $db =& eZDB::instance();
        $classID = $contentClass->attribute( 'id' );
        while ( true )
        {
            $resArray = $db->arrayQuery( "SELECT ezcontentobject.id FROM ezcontentobject WHERE ezcontentobject.contentclass_id='$classID'", array( 'length' => 50 ) );
            if( !$resArray )
                break;

            foreach( $resArray as $row )
            {
                $objectID = $row['id'];
                include_once( 'kernel/classes/ezcontentobjectoperations.php' );
                eZContentObjectOperations::remove( $objectID );
            }
        }

        eZContentClassClassGroup::removeClassMembers( $classID, 0 );
        eZContentClassClassGroup::removeClassMembers( $classID, 1 );

        // Fetch real version and remove it
        $contentClass->remove( true );

        // Fetch temp version and remove it
        $tempDeleteClass = eZContentClass::fetch( $classID, true, 1 );
        if ( $tempDeleteClass != null )
            $tempDeleteClass->remove( true, 1 );

    }
}


?>