<?php
//
// Definition of eZDBTool class
//
// Created on: <11-Dec-2002 15:07:25 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezdbtool.php
*/

/*!
  \class eZDBTool ezdbtool.php
  \brief The class eZDBTool does

*/

class eZDBTool
{
    /*!
     \return true if the database does not contain any relation objects.
     \note If db is not specified it will use eZDB::instance()
    */
    static function isEmpty( $db )
    {
        if ( $db === null )
            $db = eZDB::instance();
        $relationTypeMask = $db->supportedRelationTypeMask();
        $count = $db->relationCounts( $relationTypeMask );
        return $count == 0;
    }

    /*!
     Tries to remove all relation types from the database.
     \note If db is not specified it will use eZDB::instance()
    */
    static function cleanup( $db )
    {
        if ( $db === null )
            $db = eZDB::instance();
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
