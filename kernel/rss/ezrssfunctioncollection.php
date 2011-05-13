<?php
/**
 * File containing the eZRSSFunctionCollection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class eZRSSFunctionCollection
{
    /**
     * Checks if there is a valid RSS/ATOM Feed export for a node or not.
     *
     * @param int $nodeID
     * @return bool Return value is inside a array with return value on result, as this is used as template fetch function.
     */
    static function hasExportByNode( $nodeID )
    {
        if ( !$nodeID )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );

        $db = eZDB::instance();
        $res = $db->arrayQuery( "SELECT id FROM ezrss_export WHERE node_id='$nodeID' AND status=" . eZRSSExport::STATUS_VALID );

        return array( 'result' => isset( $res[0] ) ? true : false );
    }

    /**
     * Return valid eZRSSExport object for a specific node if it exists.
     *
     * @param int $nodeID
     * @return eZRSSExport|false Return value is inside a array with return value on result, as this is used as template fetch function.
     */
    static function exportByNode( $nodeID )
    {
        if ( !$nodeID )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );

        $rssExport = eZPersistentObject::fetchObject( eZRSSExport::definition(),
                                                null,
                                                array( 'node_id' => $nodeID,
                                                       'status' => eZRSSExport::STATUS_VALID ),
                                                true );

        return array( 'result' => $rssExport );
    }
}

?>
