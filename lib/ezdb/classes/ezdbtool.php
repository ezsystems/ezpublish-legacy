<?php
//
// Definition of eZDBTool class
//
// Created on: <11-Dec-2002 15:07:25 amos>
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

/*! \file ezdbtool.php
*/

/*!
  \class eZDBTool ezdbtool.php
  \brief The class eZDBTool does

*/

include_once( 'lib/ezdb/classes/ezdb.php' );

class eZDBTool
{
    /*!
     Constructor
    */
    function eZDBTool()
    {
    }

    /*!
     \return true if the database does not contain any relation objects.
     \note If db is not specified it will use eZDB::instance()
    */
    function isEmpty( &$db )
    {
        if ( $db === null )
            $db =& eZDB::instance();
        $relationTypeMask = $db->supportedRelationTypeMask();
        $count = $db->relationCounts( $relationTypeMask );
        return $count == 0;
    }

    /*!
     Tries to remove all relation types from the database.
     \note If db is not specified it will use eZDB::instance()
    */
    function cleanup( &$db )
    {
        if ( $db === null )
            $db =& eZDB::instance();
        $relationTypes = $db->supportedRelationTypes();
        $result = true;
        $defaultRegexp = "#^ez|tmp_notification_rule_s#";
        foreach ( $relationTypes as $relationType )
        {
            $relationItems = $db->relationList( $relationType );
            // This is the default regexp, unless the db driver provides one
            $matchRegexp = null;
            if ( method_exists( $db, 'relationMatchRegexp' ) )
            {
                $matchRegexp = $db->relationMatchRegexp( $relationType );
            }
            if ( $matchRegexp === null )
                $matchRegexp = $defaultRegexp;
            foreach ( $relationItems as $relationItem )
            {
                // skip relations that shouldn't be touched
                if ( $matchRegexp !== false and
                     !preg_match( $matchRegexp, $relationItem ) )
                    continue;

                if ( !$db->removeRelation( $relationItem, $relationType ) )
                {
                    $result = false;
                    break;
                }
            }
            if ( !$result )
                break;
        }
        return $result;
    }
}

?>
