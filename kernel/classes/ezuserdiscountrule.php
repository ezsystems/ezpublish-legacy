<?php
/**
 * File containing the eZUserDiscountRule class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZUserDiscountRule ezuserdiscountrule.php
  \brief The class eZUserDiscountRule does

*/

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
        if ( $this->ContentobjectID == eZUser::anonymousId() )
        {
            eZUser::purgeUserCacheByAnonymousId();
        }
        else
        {
            eZExpiryHandler::registerShutdownFunction();
            $handler = eZExpiryHandler::instance();
            $handler->setTimestamp( 'user-discountrules-cache', time() );
            $handler->store();
        }
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
        if ( $userID == eZUser::anonymousId() )
        {
                $userCache = eZUSer::getUserCacheByAnonymousId();
                $ruleArray = $userCache['discount_rules'];
        }
        else
        {
            $http = eZHTTPTool::instance();

            eZExpiryHandler::registerShutdownFunction();
            $handler = eZExpiryHandler::instance();
            $expiredTimeStamp = 0;
            if ( $handler->hasTimestamp( 'user-discountrules-cache' ) )
                $expiredTimeStamp = $handler->timestamp( 'user-discountrules-cache' );

            $ruleTimestamp =& $http->sessionVariable( 'eZUserDiscountRulesTimestamp' );

            $ruleArray = false;
            // check for cached version in session
            if ( $ruleTimestamp > $expiredTimeStamp )
            {
                if ( $http->hasSessionVariable( 'eZUserDiscountRules' . $userID ) )
                {
                    $ruleArray =& $http->sessionVariable( 'eZUserDiscountRules' . $userID );
                }
            }

            if ( !is_array( $ruleArray ) )
            {
                $ruleArray = self::generateIDListByUserID( (int) $userID );
                $http->setSessionVariable( 'eZUserDiscountRules' . $userID, $ruleArray );
                $http->setSessionVariable( 'eZUserDiscountRulesTimestamp', time() );
            }
        }

        $rules = array();
        foreach ( $ruleArray as $ruleRow )
        {
            $rules[] = $ruleRow['id'];
        }
        return $rules;
    }

    /**
     * Get raw list of discount rules
     *
     * @internal
     * @param int $userId
     * @return array
     */
    static public function generateIDListByUserID( $userId )
    {
        $db = eZDB::instance();
        $query = "SELECT DISTINCT ezdiscountrule.id
              FROM ezdiscountrule,
                   ezuser_discountrule
              WHERE ezuser_discountrule.contentobject_id = $userId AND
                    ezuser_discountrule.discountrule_id = ezdiscountrule.id";
        return $db->arrayQuery( $query );
    }

    /**
     * Fetches the eZDiscountRules matching an array of eZUserID
     *
     * @param array(eZUserID) $idArray Array of user ID
     *
     * @return array(eZDiscountRule)
     */
    static function &fetchByUserIDArray( $idArray )
    {
        $db = eZDB::instance();
        $inString = $db->generateSQLINStatement( $idArray, 'ezuser_discountrule.contentobject_id', false, false, 'int' );
        $query = "SELECT DISTINCT ezdiscountrule.id,
                                  ezdiscountrule.name
                  FROM ezdiscountrule,
                       ezuser_discountrule
                  WHERE $inString AND
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
