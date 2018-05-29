<?php
/**
 * File containing the eZSiteData class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * eZPersistentObject implementation for ezsite_data table.
 * Enables you to store and fetch key/value pairs
 *
 * Class eZSiteData
 */
class eZSiteData extends eZPersistentObject
{
    /**
     * Schema definition
     * @see kernel/classes/ezpersistentobject.php
     * @return array
     */
    public static function definition()
    {
        return array( 'fields'       => array( 'name'               => array( 'name'     => 'name',
                                                                              'datatype' => 'string',
                                                                              'default'  => null,
                                                                              'required' => true ),

                                               'value'             => array( 'name'     => 'value',
                                                                             'datatype' => 'string',
                                                                             'default'  => null,
                                                                             'required' => true ),

                                            ),

                      'keys'                 => array( 'name' ),
                      'class_name'           => 'eZSiteData',
                      'name'                 => 'ezsite_data',
                      'function_attributes'  => array()
        );
    }

    /**
     * Constructs a new eZSiteData instance. You need to call 'store()'
     * in order to store it into the DB.
     *
     * @param string $key
     * @param string $value
     * @return eZSiteData
     */
    public static function create( $name, $value )
    {
        return new eZSiteData( array(
            'name' => $name,
            'value' => $value
        ) );
    }

    /**
     * Fetches a site data by name
     *
     * @param string $name
     * @return eZPersistentObject
     */
    public static function fetchByName( $name )
    {
        $result = parent::fetchObject( self::definition(), null, array( 'name' => $name ) );
        return $result;
    }

}

?>
