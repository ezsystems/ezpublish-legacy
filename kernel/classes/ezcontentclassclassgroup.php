<?php
//
// Definition of eZContentClass class
//
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
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
        return array( "fields" => array( "contentclass_id" => "ContentClassID",
                                         "contentclass_version" => "ContentClassVersion",
                                         "group_id" => "GroupID",
                                         "group_name" => "GroupName"),
                      "keys" => array( "contentclass_id", "contentclass_version", "group_id" ),
                      "increment_key" => "id",
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
        return eZPersistentObject::fetchObjectList( eZContentClassClassGroup::definition(),
                                                    null,
                                                    array( "contentclass_version" =>$contentclass_version,
                                                           "group_id" => $group_id ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    function &fetchGroupList( $contentclass_id, $contentclass_version, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentClassClassGroup::definition(),
                                                    null,
                                                    array( "contentclass_id" => $contentclass_id,
                                                           "contentclass_version" =>$contentclass_version ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    /// \privatesection
    var $ContentClassID;
    var $ContentClassVersion;
    var $GroupID;
    var $GroupName;
}

?>
