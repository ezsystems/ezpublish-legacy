<?php
//
// Definition of eZNotificationRule class
//
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

//!! eZNotification
//! The class eZNotificationRule
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( 'kernel/notification/eznotificationrule.php' );

class eZNotificationRule extends eZPersistentObject
{
    function eZNotificationRule( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "type" => "Type",
                                         "contentclass_name" => "ContentClassName",
                                         "path" => "Path",
                                         "keyword" => "Keyword",
                                         "has_constraint" => "HasConstraint"),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZNotificationRule",
                      "name" => "eznotification_rule" );
    }

    function &create( $contentclass_name, $type, $path, $keyword, $hasConstraint )
    {
        $row = array( "id" => null,
                      "type" => $type,
                      "contentclass_name" => $contentclass_name,
                      "path" => $path,
                      "keyword" => $keyword,
                      "has_constraint" => $hasConstraint );
        return new eZNotificationRule( $row );
    }


    /*!
     Return defined notification rule type.
    */
    function &ruleType()
    {
        $typeString = 'ez' . $this->Type;
        return eZNotificationRuleType::create( $typeString );
    }

    /*!
     \return true if the attribute \a $attr exists in this object.
    */
    function hasAttribute( $attr )
    {
        return $attr == "rule_type" or
            eZPersistentObject::hasAttribute( $attr ) ;
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case "rule_type":
            {
                return $this->ruleType();
            }break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
    }

    /*!
     Remove the rule object has \a $id
    */
    function &remove( $id )
    {
        eZPersistentObject::removeObject( eZNotificationRule::definition(),
                                          array( "id" => $id ) );
    }

    /*!
     Fetch the rule object has \a $id
    */
    function &fetch( $id,  $as_object = true  )
    {
        return eZPersistentObject::fetchObject( eZNotificationRule::definition(),
                                                null,
                                                array("id" => $id ),
                                                $as_object );
    }

    /*!
     Fetch one rule object specified by \a $condition
    */
    function &fetchOne( $condition,  $asObject = true  )
    {
        return eZPersistentObject::fetchObject( eZNotificationRule::definition(),
                                                null,
                                                $condition,
                                                $asObject );
    }

    /*!
     Fetch all rules satisfy \a $condition
    */
    function &fetchList( $condition, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationRule::definition(),
                                                    null,
                                                    $condition,
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /// \privatesection
    var $ID;
    var $Type;
    var $ContentClassName;
    var $Path;
    var $Keyword;
    var $HasConstraint;
}

?>
