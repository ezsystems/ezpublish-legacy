<?php
//
// Definition of eZContentClass class
//
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

//!! eZKernel
//! The class eZContentClassClassGroup
/*!

*/

class eZContentClassClassGroup extends eZPersistentObject
{
    function eZContentClassClassGroup( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "contentclass_id" => array( 'name' => "ContentClassID",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZContentClass',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
                                         "contentclass_version" => array( 'name' => "ContentClassVersion",
                                                                          'datatype' => 'integer',
                                                                          'default' => 0,
                                                                          'required' => true ),
                                         "group_id" => array( 'name' => "GroupID",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "group_name" => array( 'name' => "GroupName",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ) ),
                      "keys" => array( "contentclass_id", "contentclass_version", "group_id" ),
//                      "increment_key" => "id",
                      "class_name" => "eZContentClassClassGroup",
                      "sort" => array( "contentclass_id" => "asc" ),
                      "name" => "ezcontentclass_classgroup" );
    }

    static function create( $contentclass_id, $contentclass_version, $group_id, $group_name )
    {
        if( $contentclass_version == null )
        {
            $contentclass_version = 0;
        }
        $row = array("contentclass_id" => $contentclass_id,
                     "contentclass_version" => $contentclass_version,
                     "group_id" => $group_id,
                     "group_name" => $group_name);
        return new eZContentClassClassGroup( $row );
    }

    static function update( $contentclass_version, $group_id, $group_name )
    {
        if( $contentclass_version == null )
        {
            $row = array( 'group_id' => $group_id );
        }
        else
        {
            $row = array( 'contentclass_version' => $contentclass_version,
                          'group_id' => $group_id );
        }

        eZPersistentObject::updateObjectList( array( 'definition' => eZContentClassClassGroup::definition(),
                                                     'update_fields' => array( 'group_name' => $group_name ),
                                                     'conditions' => $row ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeGroup( $contentclass_id, $contentclass_version, $group_id )
    {
        if ( $contentclass_version == null )
        {
            eZPersistentObject::removeObject( eZContentClassClassGroup::definition(),
                                              array("contentclass_id" => $contentclass_id,
                                                    "group_id" => $group_id ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZContentClassClassGroup::definition(),
                                              array("contentclass_id" => $contentclass_id,
                                                    "contentclass_version" =>$contentclass_version,
                                                    "group_id" => $group_id ) );
        }
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeGroupMembers( $group_id )
    {
        eZPersistentObject::removeObject( eZContentClassClassGroup::definition(),
                                          array( "group_id" => $group_id ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeClassMembers( $contentclass_id, $contentclass_version )
    {
        eZPersistentObject::removeObject( eZContentClassClassGroup::definition(),
                                          array( "contentclass_id" =>$contentclass_id,
                                                 "contentclass_version" =>$contentclass_version ) );
    }

    static function fetch( $contentclass_id, $contentclass_version, $group_id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZContentClassClassGroup::definition(),
                                                null,
                                                array("contentclass_id" => $contentclass_id,
                                                      "contentclass_version" =>$contentclass_version,
                                                      "group_id" => $group_id ),
                                                $asObject );
    }

    static function fetchClassList( $contentclass_version, $group_id, $asObject = true, $orderByArray = array( 'name' ) )
    {
        $versionCond = '';
        $orderByClause = '';
        $group_id =(int) $group_id;
        $classNameSqlFilter = eZContentClassName::sqlEmptyFilter();

        if ( $contentclass_version !== null )
        {
            $contentclass_version =(int) $contentclass_version;
            $versionCond = "AND class_group.contentclass_version='$contentclass_version'
                            AND contentclass.version='$contentclass_version'\n";
        }

        if ( $orderByArray )
        {
            foreach( array_keys( $orderByArray ) as $key )
            {
                if ( strcasecmp( $orderByArray[$key], 'name' ) === 0 )
                {
                    $classNameSqlFilter = eZContentClassName::sqlAppendFilter( 'contentclass' );
                    $orderByArray[$key] = $classNameSqlFilter['orderBy'];
                }
            }

            $orderByClause = 'ORDER BY ' . implode( ', ', $orderByArray );
        }

        $db = eZDB::instance();
        $sql = "SELECT contentclass.* $classNameSqlFilter[nameField]
                FROM ezcontentclass  contentclass, ezcontentclass_classgroup class_group $classNameSqlFilter[from]
                WHERE contentclass.id=class_group.contentclass_id
                $versionCond
                AND class_group.group_id='$group_id' $classNameSqlFilter[where]
                $orderByClause";
        $rows = $db->arrayQuery( $sql );
        return eZPersistentObject::handleRows( $rows, "eZContentClass", $asObject );
    }

    static function fetchClassListByGroups( $contentclassVersion, $groupIDList, $asObject = true )
    {
        if ( is_array( $groupIDList ) )
        {
            $groupIDList = array( $groupIDList );
        }
        $classGroupList = eZPersistentObject::fetchObjectList( eZContentClassClassGroup::definition(),
                                                                array(),
                                                                array( "group_id" => $groupIDList,
                                                                       "contentclass_version" => $contentclassVersion ),
                                                                null,
                                                                null,
                                                                false,
                                                                false,
                                                                array( array( 'operation' => "distinct contentclass_id" ) ) );
        $classList = array();
        if ( $asObject )
        {
            foreach ( $classGroupList as $classGroup )
            {
                $classList[] = eZContentClass::fetch( $classGroup['contentclass_id'] );
            }
        }
        else
        {
            foreach ( $classGroupList as $classGroup )
            {
                $classList[] = $classGroup['contentclass_id'];
            }
        }
        return $classList;
    }

    static function fetchGroupList( $contentclass_id, $contentclass_version, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentClassClassGroup::definition(),
                                                    null,
                                                    array( "contentclass_id" => $contentclass_id,
                                                           "contentclass_version" => $contentclass_version ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    static function classInGroup( $contentclassID, $contentclassVersion, $groupID )
    {
        $rows = eZPersistentObject::fetchObjectList( eZContentClassClassGroup::definition(),
                                                     null,
                                                     array( 'group_id' => $groupID,
                                                            "contentclass_id" => $contentclassID,
                                                            "contentclass_version" => $contentclassVersion ),
                                                     null,
                                                     null,
                                                     false );
        return count( $rows ) > 0;
    }

    /// \privatesection
    public $ContentClassID;
    public $ContentClassVersion;
    public $GroupID;
    public $GroupName;
}

?>
