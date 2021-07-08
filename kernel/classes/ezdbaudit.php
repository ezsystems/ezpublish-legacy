<?php
/**
 * File containing the eZDBAudit class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class eZDBAudit extends eZPersistentObject
{
    /**
     * Creates a new db audit object.
     */
    protected function eZDBAudit( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    /**
     * Returns the definition array for this class
     *
     * @static
     * @return array
     */
    public static function definition()
    {
        return array(
            'fields' => array(
                'id' => array(
                    'name'     => 'ID',
                    'datatype' => 'integer',
                    'default'  => 0,
                    'required' => true ),
                'user_id' => array(
                    'name'     => 'UserID',
                    'datatype' => 'integer',
                    'default'  => 0,
                    'required' => true ),
                'user_login' => array(
                    'name'     => 'UserLogin',
                    'datatype' => 'string',
                    'default'  => '',
                    'required' => true ),
                'timestamp' => array(
                    'name'     => 'Timestamp',
                    'datatype' => 'integer',
                    'default'  => 0,
                    'required' => true ),
                'ip_address' => array(
                    'name'     => 'IPAddress',
                    'datatype' => 'string',
                    'default'  => '',
                    'required' => true ),
                'action' => array(
                    'name'     => 'Action',
                    'datatype' => 'string',
                    'default'  => '',
                    'required' => true ),
                'details' => array(
                    'name'     => 'Details',
                    'datatype' => 'string',
                    'required' => false ) ),
            'keys' => array( 'id' ),
            'class_name' => 'eZDBAudit',
            'sort' => array( 'timestamp' => 'desc' ),
            'name' => 'ezaudit'
        );
    }

    /**
     * Writes the audit record to the database
     *
     * @static
     *
     * @param string $auditName
     * @param array $auditAttributes
     *
     * @return array
     */
    public static function writeAudit( $auditName, $auditAttributes = array() )
    {
        if ( !is_string( $auditName ) || empty( $auditName ) )
            return false;

        $currentUser = eZUser::currentUser();

        $ipAddress = eZSys::clientIP();
        if ( !$ipAddress )
        {
            $ipAddress = eZSys::serverVariable( 'HOSTNAME', true );
        }

        $auditDetails = "";
        if ( is_array( $auditAttributes ) )
        {
            foreach ( $auditAttributes as $attributeKey => $attributeValue )
            {
                $auditDetails .= "$attributeKey: $attributeValue\n";
            }
        }

        $audit = new self(
            array(
                'user_id'    => $currentUser->attribute( 'contentobject_id' ),
                'user_login' => $currentUser->attribute( 'login' ),
                'timestamp'  => time(),
                'ip_address' => $ipAddress,
                'action'     => trim( $auditName ),
                'details'    => empty( $auditDetails ) ? null : $auditDetails
            )
        );

        $audit->store();

        return true;
    }

    /**
     * Returns the list of audit records from the database, filtered by audit name
     *
     * @static
     *
     * @param string $auditName
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public static function fetchByAuditName( $auditName, $offset = 0, $limit = false )
    {
        return parent::fetchObjectList(
            self::definition(),
            null,
            array( 'action' => $auditName ),
            null,
            array(
                'offset' => $offset,
                'length' => $limit
            )
        );
    }

    /**
     * Returns the count of audit records from the database, filtered by audit name
     *
     * @static
     *
     * @param string $auditName
     *
     * @return int
     */
    public static function fetchCountByAuditName( $auditName )
    {
        return parent::count(
            self::definition(),
            array( 'action' => $auditName )
        );
    }

    /**
     * Returns the list of audit records from the database, filtered by user ID
     *
     * @static
     *
     * @param int $userID
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public static function fetchByUserID( $userID, $offset = 0, $limit = false )
    {
        return parent::fetchObjectList(
            self::definition(),
            null,
            array( 'user_id' => (int) $userID ),
            null,
            array(
                'offset' => $offset,
                'length' => $limit
            )
        );
    }

    /**
     * Returns the count of audit records from the database, filtered by user ID
     *
     * @static
     *
     * @param int $userID
     *
     * @return int
     */
    public static function fetchCountByUserID( $userID )
    {
        return parent::count(
            self::definition(),
            array( 'user_id' => (int) $userID )
        );
    }

    /**
     * Removes all audit records older than $daysThreshold days
     *
     * @static
     *
     * @param int $daysThreshold
     */
    public static function removeAudits( $daysThreshold = 0 )
    {
        $secondsThreshold = (int) $daysThreshold * 86400;

        if ( $secondsThreshold < 0 )
            return;

        parent::removeObject(
            self::definition(),
            array( 'timestamp' => array( '<', time() - $secondsThreshold ) )
        );
    }
}

?>
