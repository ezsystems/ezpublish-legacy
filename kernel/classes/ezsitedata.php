<?php
/**
 * File containing the eZSiteData class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class eZSiteData extends eZPersistentObject
{
    /**
     * Schema definition
     * eZPersistentObject implementation for ezsite_data table
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
     * Fetches a site data by name
     * @param string $name
     */
    public static function fetchByName( $name )
    {
        $result = parent::fetchObject( self::definition(), null, array( 'name' => $name ) );
        return $result;
    }

}

?>
