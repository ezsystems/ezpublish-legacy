<?php
//
// Definition of eZContentClass class
//
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

//!! eZKernel
//! The class eZContentClassClassGroup
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentclassgroup.php" );

class eZContentClassClassGroup extends eZPersistentObject
{
    function eZContentClassClassGroup( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "contentclass_id" => array( 'name' => "ContentClassID",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
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

    function &create( $contentclass_id, $contentclass_version, $group_id, $group_name )
    {
        $row = array("contentclass_id" => $contentclass_id,
                     "contentclass_version" => $contentclass_version,
                     "group_id" => $group_id,
                     "group_name" => $group_name);
        return new eZContentClassClassGroup( $row );
    }

    function &remove( $contentclass_id, $contentclass_version, $group_id )
    {
        eZPersistentObject::removeObject( eZContentClassClassGroup::definition(),
                                          array("contentclass_id" => $contentclass_id,
                                                "contentclass_version" =>$contentclass_version,
                                                "group_id" => $group_id ) );
    }

    function &removeGroupMembers( $group_id )
    {
        eZPersistentObject::removeObject( eZContentClassClassGroup::definition(),
                                          array( "group_id" => $group_id ) );
    }

    function &removeClassMembers( $contentclass_id, $contentclass_version )
    {
        eZPersistentObject::removeObject( eZContentClassClassGroup::definition(),
                                          array( "contentclass_id" =>$contentclass_id,
                                                 "contentclass_version" =>$contentclass_version ) );
    }

    function &fetch( $contentclass_id, $contentclass_version, $group_id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZContentClassClassGroup::definition(),
                                                null,
                                                array("contentclass_id" => $contentclass_id,
                                                      "contentclass_version" =>$contentclass_version,
                                                      "group_id" => $group_id ),
                                                $asObject );
    }

    function &fetchClassList( $contentclass_version, $group_id, $asObject = true )
    {
        include_once( 'kernel/classes/ezcontentclassclassgroup.php' );
        $classIDList =& eZContentClassClassGroup::fetchClassListByGroups( 0, array( 1,3 ) );
        $versionCond = '';
        if ( $contentclass_version !== null )
        {
            $versionCond = "AND class_group.contentclass_version='$contentclass_version'
                            AND contentclass.version='$contentclass_version'\n";
        }

        $db =& eZDB::instance();
        $sql = "SELECT contentclass.*
                FROM ezcontentclass  contentclass, ezcontentclass_classgroup class_group
                WHERE contentclass.id=class_group.contentclass_id
                $versionCond
                AND class_group.group_id='$group_id'";
        $rows =& $db->arrayQuery( $sql );
        return eZPersistentObject::handleRows( $rows, "eZContentClass", $asObject );
    }

    function &fetchClassListByGroups( $contentclassVersion, $groupIDList, $asObject = true )
    {
//         $db =& eZDB::instance();
//         $sql = 'SELECT contentclass_id, contentclass_version, group_id, group_name
// FROM   ezcontentclass_classgroup
// WHERE  contentclass_version='0' AND group_id IN ( '1', '3' )
// ORDER BY contentclass_id ASC';
        if ( is_array( $groupIDList ) )
        {
            $groupIDList = array( $groupIDList );
        }
        $classGroupList =& eZPersistentObject::fetchObjectList( eZContentClassClassGroup::definition(),
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

    function &fetchGroupList( $contentclass_id, $contentclass_version, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentClassClassGroup::definition(),
                                                    null,
                                                    array( "contentclass_id" => $contentclass_id,
                                                           "contentclass_version" => $contentclass_version ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function &classInGroup( $contentclassID, $contentclassVersion, $groupID )
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
    var $ContentClassID;
    var $ContentClassVersion;
    var $GroupID;
    var $GroupName;
}

?>
