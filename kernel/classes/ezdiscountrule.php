<?php
/**
 * File containing the eZDiscountRule class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/*! \file
*/

/*!
  \class eZDiscountRule ezdiscountrule.php
  \brief The class eZDiscountRule does

*/

class eZDiscountRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZDiscountRule( $row )
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
                                                         'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZDiscountRule",
                      "name" => "ezdiscountrule" );
    }

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZDiscountRule::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    static function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountRule::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    static function create()
    {
        $row = array(
            "id" => null,
            "name" => ezpI18n::tr( "kernel/shop/discountgroup", "New discount group" ) );
        return new eZDiscountRule( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeByID( $id )
    {
        eZPersistentObject::removeObject( eZDiscountRule::definition(),
                                          array( "id" => $id ) );
    }
}
?>
