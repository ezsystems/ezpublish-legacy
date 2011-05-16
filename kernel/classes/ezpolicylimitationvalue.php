<?php
/**
 * File containing the eZPolicyLimitationValue class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZPolicyLimitationValue ezpolicylimitationvalue.php
  \ingroup eZRole
  \brief Defines a limitation value for a policy in the permission system

*/
class eZPolicyLimitationValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZPolicyLimitationValue( $row )
    {
          $this->eZPersistentObject( $row );
    }


    static function definition()
    {
        static $definition = array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'limitation_id' => array( 'name' => 'LimitationID',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true,
                                                                   'foreign_class' => 'eZPolicyLimitation',
                                                                   'foreign_attribute' => 'id',
                                                                   'multiplicity' => '1..*' ),
                                         'value' => array( 'name' => 'Value',
                                                           'datatype' => 'text',
                                                           'default' => '',
                                                           'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "sort" => array( "value" => "asc" ),
                      "class_name" => "eZPolicyLimitationValue",
                      "name" => "ezpolicy_limitation_value" );
        return $definition;
    }


    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function createNew( $limitationID, $value )
    {
        $limitationValue = new eZPolicyLimitationValue( array() );
        $limitationValue->setAttribute( 'limitation_id', $limitationID );
        $limitationValue->setAttribute( 'value', $value );
        $limitationValue->store();

        return $limitationValue;
    }

    /*!
     \static
     Creates a new limitation value for the limitation \a $limitationID and returns it.
     \note The value is not stored.
    */
    static function create( $limitationID, $value )
    {
        $row = array( 'id' => null,
                      'limitation_id' => $limitationID,
                      'value' => $value );
        return new eZPolicyLimitationValue( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function copy( $limitationID )
    {
        $newValue = eZPolicyLimitationValue::createNew( $limitationID, $this->attribute( 'value' ) );
    }

    static function fetchList( $limitationID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZPolicyLimitationValue::definition(),
                                                    null,
                                                    array( 'limitation_id' => $limitationID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeByValue( $value, $policyID = false )
    {
        if ( $policyID )
        {
            $limitationIDList = array();
            $limitations = eZPolicyLimitation::fetchByPolicyID( $policyID, false );
            foreach ( $limitations as $limitationArray )
            {
                $limitationIDList[] = $limitationArray['id'];
            }
            if  ( count( $limitationIDList ) > 0 )
            {
                eZPersistentObject::removeObject( eZPolicyLimitationValue::definition(),
                                                  array( 'limitation_id' => array( $limitationIDList  ),
                                                         "value" => $value ) );
                return;
            }
        }
        eZPersistentObject::removeObject( eZPolicyLimitationValue::definition(),
                                          array( "value" => $value ) );
    }

}

?>
