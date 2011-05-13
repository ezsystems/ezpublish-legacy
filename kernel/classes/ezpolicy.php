<?php
/**
 * File containing the eZPolicy class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZPolicy ezpolicy.php
  \ingroup eZRole
  \brief Defines a policy in the permission system

*/

class eZPolicy extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZPolicy( $row )
    {
          $this->eZPersistentObject( $row );
          $this->NodeID = 0;
    }

    static function definition()
    {
        static $definition = array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'role_id' => array( 'name' => 'RoleID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZRole',
                                                             'foreign_attribute' => 'id',
                                                             'multiplicity' => '1..*' ),
                                         'module_name' => array( 'name' => 'ModuleName',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'function_name' => array( 'name' => 'FunctionName',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                         'original_id' => array( 'name' => 'OriginalID',
                                                                 'datatype' => 'integer',
                                                                 'default' => null,
                                                                 'required' => false ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'limitations' => 'limitationList',
                                                      'role' => 'role',
                                                      'limit_identifier' => 'limitIdentifier',
                                                      'limit_value' => 'limitValue',
                                                      'user_role_id' => 'userRoleID' ),
                      'increment_key' => 'id',
                      'sort' => array( 'id' => 'asc' ),
                      'class_name' => 'eZPolicy',
                      'name' => 'ezpolicy' );
        return $definition;
    }

    function limitIdentifier()
    {
        return $this->LimitIdentifier;
    }

    function limitValue()
    {
        return $this->LimitValue;
    }

    function userRoleID()
    {
        return $this->UserRoleID;
    }

    function setAttribute( $attr, $val )
    {
        switch( $attr )
        {
            case 'limit_identifier':
            {
                if ( !$this->LimitIdentifier )
                {
                    $this->LimitIdentifier = $val;
                }
            } break;

            case 'limit_value':
            {
                if ( !$this->LimitValue )
                {
                    $this->LimitValue = $val;
                }
            } break;
            case 'user_role_id':
            {
                if ( !$this->UserRoleID )
                {
                    $this->UserRoleID = $val;
                }
            } break;

            default:
            {
                eZPersistentObject::setAttribute( $attr, $val );
            } break;
        }
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function createNew( $roleID , $params = array() )
    {
        $policy = new eZPolicy( array( 'id' => null ) );
        $policy->setAttribute( 'role_id', $roleID );
        if ( array_key_exists( 'ModuleName', $params ))
        {
            $policy->setAttribute( 'module_name', $params['ModuleName'] );
        }
        if ( array_key_exists( 'FunctionName', $params ))
        {
            $policy->setAttribute( 'function_name', $params['FunctionName'] );
        }
        $policy->store();

        return $policy;
    }

    /*!
     \static
     Creates a new policy assigned to the role identified by ID \a $roleID  and returns it.
     \note The policy is not stored.
     \param $module Which module to give access to or \c true to give access to all modules.
     \param $function Which function to give access to or \c true to give access to all functions.
     \param $limitations An associative array with limitations and their values, use an empty array for no limitations.
    */
    static function create( $roleID, $module, $function )
    {
        if ( $module === true )
            $module = '*';
        if ( $function === true )
            $function = '*';
        $row = array( 'id' => null,
                      'role_id' => $roleID,
                      'module_name' => $module,
                      'function_name' => $function );
        $policy = new eZPolicy( $row );
        return $policy;
    }

    /*!
     Appends a new policy limitation to the current policy and returns it.
     \note The limitation and it's values will be stored to the database before returning.
     \param $identifier The identifier for the limitation, e.g. \c 'Class'
     \param $values Array of values to store for limitation.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function appendLimitation( $identifier, $values )
    {
        $limitation = eZPolicyLimitation::create( $this->ID, $identifier );

        $db = eZDB::instance();
        $db->begin();
        $limitation->store();
        $limitationID = $limitation->attribute( 'id' );
        $limitations = array();
        foreach ( $values as $value )
        {
            $limitationValue = eZPolicyLimitationValue::create( $limitationID, $value );
            $limitationValue->store();
            if ( isset( $limitation->Values ) )
            {
                $limitation->Values[] = $limitationValue;
            }
        }
        $db->commit();

        if ( isset( $this->Limitations ) )
        {
            $this->Limitations[] = $limitation;
        }
        return $limitation;
    }

    /**
     * Copies the policy and its limitations to another role
     *
     * @param int $roleID the ID of the role to copy to
     * @return eZPolicy the created eZPolicy copy
     */
    function copy( $roleID )
    {
        $params = array();
        $params['ModuleName'] = $this->attribute( 'module_name' );
        $params['FunctionName'] = $this->attribute( 'function_name' );

        $db = eZDB::instance();
        $db->begin();
        $newPolicy = eZPolicy::createNew( $roleID, $params  );
        foreach ( $this->attribute( 'limitations' ) as $limitation )
        {
            $limitation->copy( $newPolicy->attribute( 'id' ) );
        }
        $db->commit();

        return $newPolicy;
    }

    /*!
     \sa removeThis
    */
    static function removeByID( $id )
    {
        $policy = eZPolicy::fetch( $id );
        if ( !$policy )
        {
            return null;
        }
        $policy->removeThis();
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeThis( $id = false )
    {
        $db = eZDB::instance();
        $db->begin();
        foreach ( $this->attribute( 'limitations' ) as $limitation )
        {
            $limitation->removeThis();
        }
        $db->query( "DELETE FROM ezpolicy
                     WHERE id='" . $db->escapeString( $this->attribute( 'id' ) ) . "'" );
        $db->commit();
    }

    /*!
     Generate access array from this policy.

     return access array
    */
    function accessArray( $ignoreLimitIdentifier = false )
    {
        $limitations = $this->limitationList( true, $ignoreLimitIdentifier );
        if ( $this->Disabled === true )
        {
            return array();
        }

        if ( !$limitations )
        {
            return array( $this->attribute( 'module_name' ) => array ( $this->attribute( 'function_name' ) => array( '*' => '*' ) ) );
        }

        $limitArray = array();

        foreach( array_keys( $limitations ) as $limitKey )
        {
            $limitArray = array_merge_recursive( $limitArray, $limitations[$limitKey]->limitArray() );
        }

        $policyName = 'p_' . $this->attribute( 'id' ) . ( isset($this->UserRoleID) ? ( '_' . $this->UserRoleID ) : '' );

        return array( $this->attribute( 'module_name' ) => array ( $this->attribute( 'function_name' ) => array( $policyName => $limitArray ) ) );
    }

    /*!
     Fetch limitation array()

     \param use limitation cache, true by default.
    */
    function limitationList( $useCache = true, $ignoreLimitIdentifier = false )
    {
        if ( !isset( $this->Limitations ) || !$useCache )
        {

            $limitations = eZPersistentObject::fetchObjectList( eZPolicyLimitation::definition(),
                                                                 null, array( 'policy_id' => $this->attribute( 'id') ), null, null,
                                                                 true );

            eZDebugSetting::writeDebug( 'kernel-policy-limitation', $limitations, "before policy limitations " . $this->ID );
            eZDebugSetting::writeDebug( 'kernel-policy-limitation', $this, "policy itself before before limitations check"  );

            if ( $ignoreLimitIdentifier === false  && isset( $this->LimitIdentifier ) && $this->LimitIdentifier )
            {
                $limitIdentifier =  $this->attribute( 'limit_identifier' );
                $limitValue = $this->attribute( 'limit_value' );
                $limitationTouched = false;
                $checkEmptyLimitation = true;
                foreach ( $limitations as $limitation )
                {
                    if ( $limitation->attribute( 'identifier' ) == $limitIdentifier )
                    {
                        if ( $limitIdentifier == 'Subtree' )
                        {
                            $limitationTouched = true;

                            $values = $limitation->attribute( 'values' );

                            foreach ( $values as $limitationValue )
                            {
                                $value = $limitationValue->attribute( 'value' );
                                if ( strpos( $value, $limitValue ) === 0 )
                                {
                                    $checkEmptyLimitation = false;
                                    eZDebugSetting::writeDebug( 'kernel-policy-limitation', $value,
                                                                "Limitationvalue has been left in the limitation [limitValue=$limitValue]" );
                                }
                                else if ( strpos( $limitValue, $value ) === 0 )
                                {
                                    $checkEmptyLimitation = false;
                                    $limitationValue->setAttribute( 'value', $limitValue );
                                    eZDebugSetting::writeDebug(  'kernel-policy-limitation',
                                                                 $value,
                                                                 "Limitationvalue has been exchanged to the value from cond assignment [limitValue=$limitValue]" );
                                }
                                else
                                {
                                    eZDebugSetting::writeDebug(  'kernel-policy-limitation',  $value,
                                                                 "Limitationvalue has been removed from limitation [limitValue=$limitValue]" );
                                    //exlude limitation value from limitation..
                                    unset( $limitationValue );
                                }
                            }
                            if ( $checkEmptyLimitation )
                            {
                                eZDebugSetting::writeDebug( 'kernel-policy-limitation', $this, 'The policy has been disabled' );
                                $this->Disabled = true;
                                $this->Limitations = array();
                                return $this->Limitations;
                            }
                        }
                    }
                }

                if ( !$limitationTouched )
                {
                    $policyLimitation = new eZPolicyLimitation( array ( 'id' => -1,
                                                                        'policy_id' => $this->attribute( 'id' ),
                                                                        'identifier' => $this->attribute( 'limit_identifier' ) ) );
                    $policyLimitation->setAttribute( 'limit_value', $this->attribute( 'limit_value' ) );

                    $limitations[] = $policyLimitation;
                }
            }
            eZDebugSetting::writeDebug( 'kernel-policy-limitation', $limitations, "policy limitations " . $this->ID );

            $this->Limitations = $limitations;
        }
        return $this->Limitations;
    }

    function role()
    {
        if ( $this->ID )
        {
            return eZPersistentObject::fetchObject( eZRole::definition(),
                                                    null, array( 'id' => $this->RoleID ), true );
        }

        return false;
    }

    /**
     * Fetches a policy by ID
     * @param int $policyID Policy ID
     * @return eZPolicy
     */
    static function fetch( $policyID )
    {
        return eZPersistentObject::fetchObject( self::definition(),
            null, array( 'id' => $policyID ), true );
    }

    /**
     * Fetches the temporary copy of a policy
     * @param int $policyID The original policy ID
     * @return eZPolicy
     */
    public static function fetchTemporaryCopy( $policyID )
    {
        $policy = eZPersistentObject::fetchObject( self::definition(),
            null, array( 'original_id' => $policyID ), true );

        if ( $policy instanceof eZPolicy )
        {
            return $policy;
        }
        // The temporary copy does not exist yet, create it
        else
        {
            $policy = self::fetch( $policyID );
            if ( $policy === null )
                return false;
            else
            {
                return $policy->createTemporaryCopy();
            }
        }
    }

    /**
     * Creates a temporary copy for this policy so that it can be edited. The policies will be linked to the copy
     * @return eZPolicy the temporary copy
     * @since 4.4
     */
    public function createTemporaryCopy()
    {
        if ( $this->attribute( 'original_id' ) === 0 )
            throw new Exception( 'eZPolicy #' . $this->attribute( 'id' ) . ' is already a temporary item (original: #'. $this->attribute( 'original_id' ) . ')' );

        $policyCopy = self::copy( $this->attribute( 'role_id' ) );
        $policyCopy->setAttribute( 'original_id', $this->attribute( 'id' ) );
        $policyCopy->store();

        return $policyCopy;
    }

    /**
     * Saves a temporary limitation created with {@link createTemporaryCopy()}
     *
     * @throws Exception The policy isn't a temporary one
     * @return void
     */
    public function saveTemporary()
    {
        if ( $this->attribute( 'original_id' ) === 0 )
            throw new Exception( __METHOD__ . ' can only be used on a temporary policy' );

        // 1. Remove the original policy
        $originalPolicy = eZPolicy::fetch( $this->attribute( 'original_id' ) );
        $originalPolicy->removeThis();

        // 2. Remove the original ID in the temporary policy (make it final)
        $this->setAttribute( 'original_id', 0 );
        $this->store();

        return $this;
    }

    // Used for assign based limitations.
    public $Disabled = false;
    public $LimitValue;
    public $LimitIdentifier;
    public $UserRoleID;

}

?>
