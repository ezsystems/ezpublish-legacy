<?php
//
// Definition of eZContentClass class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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
//! The class eZContentClass does
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );
include_once( "kernel/classes/ezcontentclassclassgroup.php" );

define( "EZ_CLASS_VERSION_STATUS_TEMPORARY", 0 );
define( "EZ_CLASS_VERSION_STATUS_DEFINED", 1 );
define( "EZ_CLASS_VERSION_STATUS_MODIFED", 2 );

class eZContentClass extends eZPersistentObject
{
    function eZContentClass( $row )
    {
        if ( is_array( $row ) )
        {
            $this->eZPersistentObject( $row );
            $this->VersionCount = false;
            $this->InGroups = null;
            $this->AllGroups = null;
            if ( isset( $row["version_count"] ) )
                $this->VersionCount = $row["version_count"];
        }
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
//                                         "contentclass_id" => "ID",
                                         "version" => "Version",
                                         "name" => "Name",
                                         "identifier" => "Identifier",
                                         "contentobject_name" => "ContentObjectName",
                                         "creator_id" => "CreatorID",
                                         "modifier_id" => "ModifierID",
                                         "created" => "Created",
                                         "modified" => "Modified" ),
                      "keys" => array( "id", "version" ),
                      "increment_key" => "id",
                      "class_name" => "eZContentClass",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentclass" );
    }

    function &create( $user_id )
    {
        include_once( "lib/ezlocale/classes/ezdatetime.php" );
        $date_time = eZDateTime::currentTimeStamp();
        $row = array(
            "id" => null,
//            "contentclass_id" => null,
            "version" => 1,
            "name" => "",
            "identifier" => "",
            "contentobject_name" => "",
            "creator_id" => $user_id,
            "modifier_id" => $user_id,
            "created" => $date_time,
            "modified" => $date_time );
        // We need it because we don't know the id of class until we've created it.
        $tempClass = new eZContentClass( $row );
//        $tempClass->setAttribute( "contentclass_id", $tempClass->attribute( "id" ) );
//        $tempClass->store();
        return $tempClass;
    }

    function hasAttribute( $attr )
    {
        return ( $attr == "version_status" or $attr == "version_count" or
                 $attr == "creator" or $attr == "modifier" or
                 $attr == "ingroup_list" or  $attr == "group_list" or
                 $attr == "defined_list" or $attr == "mixed_list" or $attr == "temporary_list" or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function attribute( $attr )
    {
        switch( $attr )
        {
            case "version_count":
            {
                return $this->VersionCount;
            } break;
            case "version_status":
            {
                return $this->versionStatus();
            } break;
            case "creator":
            {
                $user_id = $this->CreatorID;
            } break;
            case "modifier":
            {
                $user_id = $this->ModifierID;
            } break;
            case "ingroup_list":
            {
                $this->InGroups =& eZContentClassClassGroup::fetchGroupList( $this->attribute("id"),
                                                                             $this->attribute("version"),
                                                                             $as_object = true);
                return $this->InGroups;
            } break;
            case "group_list":
            {
                $this->AllGroups =& eZContentClassGroup::fetchList();
                return $this->AllGroups;
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        $user =& eZUser::fetch( $user_id );
        return $user;
    }

    function remove( $remove_childs = false )
    {
        if ( is_array( $remove_childs ) or $remove_childs )
        {
            if ( is_array( $remove_childs ) )
            {
                $attributes =& $remove_childs;
                for ( $i = 0; $i < count( $attributes ); ++$i )
                {
                    $attribute =& $attributes[$i];
                    $attribute->remove();
                }
            }
            else
            {
                eZPersistentObject::removeObject( eZContentClassAttribute::definition(),
                                                  array( "contentclass_id" => $this->ID,
                                                         "version" => $this->Version ) );
            }
        }
        eZPersistentObject::remove();
    }

    function removeAttributes( $attributes = false, $id = false, $version = false )
    {
        if ( is_array( $attributes ) )
        {
            for ( $i = 0; $i < count( $attributes ); ++$i )
            {
                $attribute =& $attributes[$i];
                $attribute->remove();
            }
        }
        else
        {
            if ( $version === false )
                $version = $this->Version;
            if ( $id === false )
                $id = $this->ID;
            eZPersistentObject::removeObject( eZContentClassAttribute::definition(),
                                              array( "contentclass_id" => $id,
                                                     "version" => $version ) );
        }
    }

    function adjustAttributePlacements( &$attributes )
    {
        if ( !is_array( $attributes ) )
            return;
        for ( $i = 0; $i < count( $attributes ); ++$i )
        {
            $attribute =& $attributes[$i];
            $attribute->setAttribute( "placement", $i + 1 );
        }
    }

    function store( $store_childs = false )
    {
        if ( is_array( $store_childs ) or $store_childs )
        {
            if ( is_array( $store_childs ) )
                $attributes =& $store_childs;
            else
                $attributes =& $this->fetchAttributes();
            for ( $i = 0; $i < count( $attributes ); ++$i )
            {
                $attribute =& $attributes[$i];
                $attribute->store();
            }
        }
        eZPersistentObject::store();
    }

    function storeDefined( &$attributes )
    {
        eZContentClass::removeAttributes( false, $this->attribute( "id" ), 0 );
        eZContentClass::removeAttributes( false, $this->attribute( "id" ), 1 );
        $this->remove( false );
        $this->setVersion( 0, $attributes );
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        $user =& eZUser::currentUser();
        $user_id = $user->attribute( "contentobject_id" );
        $this->setAttribute( "modifier_id", $user_id );
        $this->setAttribute( "modified", eZDateTime::currentTimeStamp() );
        $this->adjustAttributePlacements( $attributes );

        for ( $i = 0; $i < count( $attributes ); ++$i )
        {
            $attribute =& $attributes[$i];
            $attribute->storeDefined();
        }
        eZPersistentObject::store();
    }

    function setVersion( $version, $set_childs = false )
    {
        if ( is_array( $set_childs ) or $set_childs )
        {
            if ( is_array( $set_childs ) )
                $attributes =& $set_childs;
            else
                $attributes =& $this->fetchAttributes();
            for ( $i = 0; $i < count( $attributes ); ++$i )
            {
                $attribute =& $attributes[$i];
                $attribute->setAttribute( "version", $version );
            }
        }
        eZPersistentObject::setAttribute( "version", $version );
    }

    function &fetch( $id, $as_object = true, $version = 0, $user_id = false ,$parent_id = null )
    {
        $conds = array( "id" => $id,
                        "version" => $version );
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        $version_sort = "desc";
        if ( $version == 0 )
            $version_sort = "asc";
        $rows =& eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                      null,
                                                      $conds,
                                                      array( "version" => $version_sort ),
                                                      array( "offset" => 0,
                                                             "length" => 2 ),
                                                      false );
        $row =& $rows[0];
        $row["version_count"] = count( $rows );
        return new eZContentClass( $row );
    }

    /*!
     \static
    */
    function &fetchList( $version = 0, $as_object = true, $user_id = false,
                         $sorts = null, $fields = null )
    {
        $conds = array();
        if ( is_numeric( $version ) )
            $conds["version"] = $version;
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        return eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                    $fields, $conds, $sorts, null,
                                                    $as_object);
    }

    function &fetchAttributes( $id = false, $as_object = true, $version = 0 )
    {
        if ( $id === false )
        {
            if ( isset( $this ) and
                 get_class( $this ) == "ezcontentclass" )
            {
                $id = $this->ID;
                $version = $this->Version;
            }
            else
                return null;
        }

        return eZContentClassAttribute::fetchFilteredList( array( "contentclass_id" => $id,
                                                                  "version" => $version ) );
    }

    function versionStatus()
    {
        if ( $this->VersionCount == 1 )
        {
            if ( $this->Version == 1 )
                return EZ_CLASS_VERSION_STATUS_TEMPORARY;
            else
                return EZ_CLASS_VERSION_STATUS_DEFINED;
        }
        else
            return EZ_CLASS_VERSION_STATUS_MODIFED;
    }

    /*!
     Will generate a name for the content object based on the class
     settings for content object.
    */
    function contentObjectName( &$contentObject )
    {
        $contentObjectName = $this->ContentObjectName;
        $dataMap =& $contentObject->dataMap();

        eZDebug::writeNotice( $dataMap, "data map" );

        // get all tags to replace
        preg_match_all( "|<[^>]+>|U",
                        $contentObjectName,
                        $tagMatchArray );

        eZDebug::writeNotice( $tagMatchArray );
        foreach ( $tagMatchArray[0] as $tag )
        {
            $tagName = str_replace( "<", "", $tag );
            $tagName = str_replace( ">", "", $tagName );

            // get the value of the attribute to use in name
            if ( isset( $dataMap[$tagName] ) )
            {
                $namePart =& $dataMap[$tagName]->title();
            }
            // replace tag with object name part
            $contentObjectName =& str_replace( $tag, $namePart, $contentObjectName );
        }
        return $contentObjectName;
    }

    /// \privatesection
    var $ID;
    var $Name;
    var $Identifier;
    var $ContentObjectName;
    var $Version;
    var $VersionCount;
    var $CreatorID;
    var $ModifierID;
    var $Created;
    var $Modified;
    var $InGroups;
    var $AllGroups;
}

?>
