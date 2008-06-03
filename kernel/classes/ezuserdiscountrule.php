<?php
//
// Definition of eZUserDiscountRule class
//
// Created on: <27-Nov-2002 13:05:59 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*! \file ezdiscountrule.php
*/

/*!
  \class eZUserDiscountRule ezuserdiscountrule.php
  \brief The class eZUserDiscountRule does

*/

//include_once( "kernel/classes/ezpersistentobject.php" );
//include_once( "kernel/classes/ezdiscountrule.php" );

class eZUserDiscountRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZUserDiscountRule( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "discountrule_id" => array( 'name' => "DiscountRuleID",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZDiscountRule',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
                                         "contentobject_id" => array( 'name' => "ContentobjectID",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZContentObject',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '1..*' ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "relations" => array( "discountrule_id" => array( "class" => "eZDiscountRule",
                                                                         "field" => "id" ),
                                            "contentobject_id" => array( "class" => "eZContentObject",
                                                                         "field" => "id" ) ),
                      "class_name" => "eZUserDiscountRule",
                      "name" => "ezuser_discountrule" );
    }

    function store( $fieldFilters = null )
    {
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-discountrules-cache', time() );
        $handler->store();
        eZPersistentObject::store( $fieldFilters );
    }

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZUserDiscountRule::definition(),
                                                null,
                                                array( "id" => $id
                                                      ),
                                                $asObject );
    }

    static function fetchByUserID( $userID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZUserDiscountRule::definition(),
                                                    null,
                                                    array( "contentobject_id" => $userID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    static function fetchIDListByUserID( $userID )
    {
        $http = eZHTTPTool::instance();

        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $expiredTimeStamp = 0;
        if ( $handler->hasTimestamp( 'user-discountrules-cache' ) )
            $expiredTimeStamp = $handler->timestamp( 'user-discountrules-cache' );

        $ruleTimestamp =& $http->sessionVariable( 'eZUserDiscountRulesTimestamp' );

        $ruleArray = false;
        // check for cached version in sesssion
        if ( $ruleTimestamp > $expiredTimeStamp )
        {
            if ( $http->hasSessionVariable( 'eZUserDiscountRules' . $userID ) )
            {
                $ruleArray =& $http->sessionVariable( 'eZUserDiscountRules' . $userID );
            }
        }

        if ( !is_array( $ruleArray ) )
        {
            $userID = (int)$userID;
            $db = eZDB::instance();
            $query = "SELECT DISTINCT ezdiscountrule.id
                  FROM ezdiscountrule,
                       ezuser_discountrule
                  WHERE ezuser_discountrule.contentobject_id = $userID AND
                        ezuser_discountrule.discountrule_id = ezdiscountrule.id";
            $ruleArray = $db->arrayQuery( $query );
            $http->setSessionVariable( 'eZUserDiscountRules' . $userID, $ruleArray );
            $http->setSessionVariable( 'eZUserDiscountRulesTimestamp', time() );
        }

        $rules = array();
        foreach ( $ruleArray as $ruleRow )
        {
            $rules[] = $ruleRow['id'];
        }
        return $rules;
    }

    static function &fetchByUserIDArray( $idArray )
    {
        $db = eZDB::instance();
        $groupString = $db->implodeWithTypeCast( ',', $idArray, 'int' );
        $query = "SELECT DISTINCT ezdiscountrule.id,
                                  ezdiscountrule.name
                  FROM ezdiscountrule,
                       ezuser_discountrule
                  WHERE ezuser_discountrule.contentobject_id IN ( $groupString ) AND
                        ezuser_discountrule.discountrule_id = ezdiscountrule.id";
        $ruleArray = $db->arrayQuery( $query );

        $rules = array();
        foreach ( $ruleArray as $ruleRow )
        {
            $rules[] = new eZDiscountRule( $ruleRow );
        }
        return $rules;
    }

    static function &fetchUserID( $discountRuleID )
    {
         $userList = eZPersistentObject::fetchObjectList( eZUserDiscountRule::definition(),
                                              null,
                                              array( "discountrule_id" => $discountRuleID ),
                                              null,
                                              null,
                                              false );
        $idArray = array();
        foreach ( $userList as $user )
        {

            $idArray[] = $user['contentobject_id'];
        }
        return $idArray;
    }

    static function &fetchByRuleID( $discountRuleID, $asObject = true )
    {
        $objectList = eZPersistentObject::fetchObjectList( eZUserDiscountRule::definition(),
                                                            null,
                                                            array( "discountrule_id" => $discountRuleID ),
                                                            null,
                                                            null,
                                                            $asObject );
        return $objectList;
    }

    static function create( $discountRuleID, $contentobjectID )
    {
        $row = array(
            "id" => null,
            "discountrule_id" => $discountRuleID,
            "contentobject_id" => $contentobjectID  );
        return new eZUserDiscountRule( $row );
    }

    static function removeUser( $userID )
    {
        eZPersistentObject::removeObject( eZUserDiscountRule::definition(),
                                          array( "contentobject_id" => $userID ) );
    }
    function removeByID( $id )
    {
        eZPersistentObject::removeObject( eZUserDiscountRule::definition(),
                                          array( "id" => $id ) );
    }
}
?>
