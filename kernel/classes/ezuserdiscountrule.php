<?php
//
// Definition of eZUserDiscountRule class
//
// Created on: <27-Nov-2002 13:05:59 wy>
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

/*! \file ezdiscountrule.php
*/

/*!
  \class eZUserDiscountRule ezuserdiscountrule.php
  \brief The class eZUserDiscountRule does

*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezdiscountrule.php" );
include_once( "lib/ezutils/classes/ezsessioncache.php" );

class eZUserDiscountRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZUserDiscountRule( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "discountrule_id" => "DiscountRuleID",
                                         "contentobject_id" => "ContentobjectID"
                                         ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "relations" => array( "discountrule_id" => array( "class" => "ezdiscountrule",
                                                                         "field" => "id" ),
                                            "contentobject_id" => array( "class" => "ezcontentobject",
                                                                         "field" => "id" ) ),
                      "class_name" => "eZUserDiscountRule",
                      "name" => "ezuser_discountrule" );
    }

    function store()
    {
        eZSessionCache::expireSessions( EZ_SESSION_CACHE_USER_DISCOUNT_RULES );
        eZPersistentObject::store();
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZUserDiscountRule::definition(),
                                                null,
                                                array( "id" => $id
                                                      ),
                                                $asObject );
    }

    function &fetchByUserID( $userID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZUserDiscountRule::definition(),
                                                    null,
                                                    array( "contentobject_id" => $userID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function &fetchIDListByUserID( $userID )
    {
        $http =& eZHTTPTool::instance();

        $ruleArray = false;
        // check for cached version in sesssion
        if ( !eZSessionCache::isExpired( EZ_SESSION_CACHE_USER_DISCOUNT_RULES ) )
        {
            if ( $http->hasSessionVariable( 'eZUserDiscountRules' . $userID ) )
            {
                $ruleArray =& $http->sessionVariable( 'eZUserDiscountRules' . $userID );
            }
        }

        if ( !is_array( $ruleArray ) )
        {
            $db =& eZDB::instance();
            $query = "SELECT DISTINCT ezdiscountrule.id
                  FROM ezdiscountrule,
                       ezuser_discountrule
                  WHERE ezuser_discountrule.contentobject_id = '$userID' AND
                        ezuser_discountrule.discountrule_id = ezdiscountrule.id";
            $ruleArray =& $db->arrayQuery( $query );
            $http->setSessionVariable( 'eZUserDiscountRules' . $userID, $ruleArray );
            eZSessionCache::setIsValid( EZ_SESSION_CACHE_USER_DISCOUNT_RULES );
        }

        $rules = array();
        foreach ( $ruleArray as $ruleRow )
        {
            $rules[] = $ruleRow['id'];
        }
        return $rules;
    }

    function &fetchByUserIDArray( $idArray )
    {
        $db =& eZDB::instance();
        $groupString = implode( ',', $idArray );
        $query = "SELECT DISTINCT ezdiscountrule.id,
                                  ezdiscountrule.name
                  FROM ezdiscountrule,
                       ezuser_discountrule
                  WHERE ezuser_discountrule.contentobject_id IN ( $groupString ) AND
                        ezuser_discountrule.discountrule_id = ezdiscountrule.id";
        $ruleArray =& $db->arrayQuery( $query );

        $rules = array();
        foreach ( $ruleArray as $ruleRow )
        {
            $rules[] = new eZDiscountRule( $ruleRow );
        }
        return $rules;
    }

    function &fetchUserID( $discountRuleID )
    {
         $userList =& eZPersistentObject::fetchObjectList( eZUserDiscountRule::definition(),
                                              null,
                                              array( "discountrule_id" => $discountRuleID ),
                                              null,
                                              null,
                                              $asObject );
        $idArray = array();
        foreach ( $userList as $user )
        {
            $idArray[] = $user['contentobject_id'];
        }
        return $idArray;
    }

    function &fetchByRuleID( $discountRuleID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZUserDiscountRule::definition(),
                                                    null,
                                                    array( "discountrule_id" => $discountRuleID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function &create( $discountRuleID, $contentobjectID )
    {
        $row = array(
            "id" => null,
            "discountrule_id" => $discountRuleID,
            "contentobject_id" => $contentobjectID  );
        return new eZUserDiscountRule( $row );
    }

    function &removeUser( $userID )
    {
        eZPersistentObject::removeObject( eZUserDiscountRule::definition(),
                                          array( "contentobject_id" => $userID ) );
    }
    function &remove( $id )
    {
        eZPersistentObject::removeObject( eZUserDiscountRule::definition(),
                                          array( "id" => $id ) );
    }
}
?>
