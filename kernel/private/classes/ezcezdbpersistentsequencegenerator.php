<?php
/**
 * File containing the ezcPersistentSequenceGenerator class
 *
 * @package PersistentObject
 * @version //autogen//
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Works around the fact that the eZPublish db schema is different in oracle and postgres (sequence naming in particular)
 */
class ezcEZDBPersistentSequenceGenerator extends ezcPersistentSequenceGenerator
{

    /**
     * Fetches the next sequence value for PostgreSQL and Oracle implementations.
     * Fetches the next sequence value for PostgreSQL and Oracle implementations.
     * Dispatches to {@link ezcPersistentNativeGenerator} for MySQL.
     *
     * @param ezcPersistentObjectDefinition $def
     * @param ezcDbHandler $db
     * @param ezcQueryInsert $q
     * @return void
     */
    public function preSave( ezcPersistentObjectDefinition $def, ezcDbHandler $db, ezcQueryInsert $q )
    {
        // We first had the native generator within here
        // For BC reasons we still allow to use the seq generator with MySQL.
        if ( $db->getName() === "mysql" || $db->getName() === "sqlite" )
        {
            if ( $this->nativeGenerator === null )
            {
                $this->nativeGenerator = new ezcPersistentNativeGenerator();
            }
            return $this->nativeGenerator->preSave( $def, $db, $q );
        }

        if ( isset( $def->idProperty->generator->params["sequence"] ) )
        {
            $seq = $def->idProperty->generator->params["sequence"];
            if ( $db->getName() === "oracle" && substr( $seq, -2 ) == '_s' )
            {
                $seq = substr( preg_replace( '/^ez/', 's_', $seq ), 0, -2 );
            }
            $q->set( $db->quoteIdentifier( $def->idProperty->columnName ), "nextval(" . $db->quote( $db->quoteIdentifier( $seq ) ) . ")" );
        }
    }

    /**
     * Returns the integer value of the generated identifier for the new object.
     * Called right after execution of the insert query.
     * Dispatches to {@link ezcPersistentNativeGenerator} for MySQL.
     *
     * @param ezcPersistentObjectDefinition $def
     * @param ezcDbHandler $db
     * @return int
     */
    public function postSave( ezcPersistentObjectDefinition $def, ezcDbHandler $db )
    {
        if ( $db->getName() == "mysql" || $db->getName() == "sqlite" )
        {
            $native = new ezcPersistentNativeGenerator();
            return $native->postSave( $def, $db );
        }
        $id = null;
        if ( array_key_exists( 'sequence', $def->idProperty->generator->params ) &&
            $def->idProperty->generator->params['sequence'] !== null )
        {
            switch ( $db->getName() )
            {
                case "oracle":
                    $seq = $def->idProperty->generator->params["sequence"];
                    if ( substr( $seq, -2 ) == '_s' )
                    {
                        $seq = substr( preg_replace( '/^ez/', 's_', $seq ), 0, -2 );
                    }
                    $idRes = $db->query( "SELECT " . $db->quoteIdentifier( $seq ) . ".CURRVAL AS id FROM DUAL" )->fetch();
                    $id = (int) $idRes["id"];
                    break;
                default:
                    $id = (int)$db->lastInsertId( $db->quoteIdentifier( $def->idProperty->generator->params['sequence'] ) );
                break;
            }
        }
        else
        {
            $id = (int)$db->lastInsertId();
        }
        // check that the value was in fact successfully received.
        if ( $db->errorCode() != 0 )
        {
            return null;
        }
        return $id;
    }
}

?>
