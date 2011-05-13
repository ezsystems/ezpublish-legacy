<?php
/**
 * File containing the eZDiscountSubRule class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZDiscountSubRule ezdiscountsubrule.php
  \brief The class eZDiscountSubRule does

*/

class eZDiscountSubRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZDiscountSubRule( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "discountrule_id" => array( 'name' => "DiscountRuleID",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZDiscountRule',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
                                         "discount_percent" => array( 'name' => "DiscountPercent",
                                                                      'datatype' => 'float',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "limitation" => array( 'name' => "Limitation",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZDiscountSubRule",
                      "name" => "ezdiscountsubrule" );
    }

    function setAttribute( $attr, $val )
    {
        switch( $attr )
        {
            case 'discount_percent':
            {
                $locale = eZLocale::instance();

                $val = $locale->internalNumber( $val );
                if ( $val < 0.0 )
                    $val = 0.0;
                if ( $val > 100.0 )
                    $val = 100.0;
                eZPersistentObject::setAttribute( $attr, $val );
            } break;

            default:
            {
                eZPersistentObject::setAttribute( $attr, $val );
            } break;
        }
    }

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZDiscountSubRule::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    static function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRule::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    static function fetchByRuleID( $discountRuleID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRule::definition(),
                                                    null,
                                                    array( "discountrule_id" => $discountRuleID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    static function create( $discountRuleID )
    {
        $row = array(
            "id" => null,
            "name" => ezpI18n::tr( 'kernel/shop/discountgroup', "New Discount Rule" ),
            "discountrule_id" => $discountRuleID,
            "discount_percent" => "",
            "limitation" => "*" );
        return new eZDiscountSubRule( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function remove ( $id = null, $dumb = null )
    {
        eZPersistentObject::removeObject( eZDiscountSubRule::definition(),
                                          array( "id" => $id ) );
    }
}
?>
