<?php
/**
 * eZPersistentObject definition for ezpending_actions table
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @licence http://ez.no/licences/gnu_gpl GNU GPLv2
 * @author Jerome Vieilledent
 */

class eZPendingActions extends eZPersistentObject
{
    /**
     * Schema definition
     * eZPersistentObject implementation for ezpending_actions table
     * @see kernel/classes/ezpersistentobject.php
     * @return array
     */
    public static function definition()
    {
        return array( 'fields'       => array( 'action'               => array( 'name'     => 'action',
                                                                                'datatype' => 'string',
                                                                                'default'  => null,
                                                                                'required' => true ),

                                               'created'             => array( 'name'     => 'created',
                                                                               'datatype' => 'integer',
                                                                               'default'  => null,
                                                                               'required' => false ),

                                               'param'               => array( 'name'     => 'param',
                                                                               'datatype' => 'string',
                                                                               'default'  => null,
                                                                               'required' => false )
                                            ),

                      'keys'                 => array( 'action', 'created' ),
                      'class_name'           => 'eZPendingActions',
                      'name'                 => 'ezpending_actions',
                      'function_attributes'  => array()
        );
    }

    /**
     * Fetches a pending actions list by action name
     * @param string $action
     * @param array $aCreationDateFilter Created date filter array (default is empty array). Must be a 2 entries array.
     *                                   First entry is the filter token (can be '=', '<', '<=', '>', '>=')
     *                                   Second entry is the filter value (timestamp)
     * @return array|null Array of eZPendingActions or null if no entry has been found
     */
    public static function fetchByAction( $action, array $aCreationDateFilter = array() )
    {
        $filterConds = array( 'action' => $action );

        // Handle creation date filter
        if( !empty( $aCreationDateFilter ) )
        {
            if( count( $aCreationDateFilter ) != 2 )
            {
                eZDebug::writeError( __CLASS__.'::'.__METHOD__.' : Wrong number of entries for Creation date filter array' );
                return null;
            }

            list( $filterToken, $filterValue ) = $aCreationDateFilter;
            $aAuthorizedFilterTokens = array( '=', '<', '>', '<=', '>=' );
            if( !is_string( $filterToken ) || !in_array( $filterToken, $aAuthorizedFilterTokens ) )
            {
                eZDebug::writeError( __CLASS__.'::'.__METHOD__.' : Wrong filter type for creation date filter' );
                return null;
            }

            $filterConds['created'] = array( $filterToken, $filterValue );
        }

        $result = parent::fetchObjectList( self::definition(), null, $filterConds );

        return $result;
    }

    /**
     * Remove entries by action
     * @param string $action
     * @param array $filterConds Additional filter conditions, as supported by {@link eZPersistentObject::fetchObjectList()} ($conds param).
     *                           For consistency sake, if an 'action' key is set here, it won't be taken into account
     */
    public static function removeByAction( $action, array $filterConds = array() )
    {
        parent::removeObject( self::definition(), array( 'action' => $action ) + $filterConds );
    }
}

?>
