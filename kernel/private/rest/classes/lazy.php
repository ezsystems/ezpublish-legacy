<?php
/**
 * File containing a collection lazy initialisation hooks
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpRestDbConfig implements ezcBaseConfigurationInitializer
{
    public static function configureObject( $instance )
    {
        //Ignoring $instance
        $dsn = self::lazyDbHelper();
        return ezcDbFactory::create( $dsn );
    }

    protected static function lazyDbHelper()
    {
        $dbMapping = array( 'ezmysqli' => 'mysql',
                            'ezmysql' => 'mysql',
                            'mysql' => 'mysql',
                            'mysqli' => 'mysql',
                            'pgsql' => 'pgsql',
                            'postgresql' => 'pgsql',
                            'ezpostgresql' => 'pgsql',
                            'ezoracle' => 'oracle',
                            'oracle' => 'oracle' );

        $ini = eZINI::instance();
        list( $dbType, $dbHost, $dbPort, $dbUser, $dbPass, $dbName ) =
            $ini->variableMulti( 'DatabaseSettings',
                                 array( 'DatabaseImplementation', 'Server', 'Port',
                                        'User', 'Password', 'Database',
                                       )
                                );

        if ( !isset( $dbMapping[$dbType] ) )
        {
            // @TODO: Add a proper exception type here.
            throw new Exception( "Unknown / unmapped DB type '$dbType'" );
        }

        $dbType = $dbMapping[$dbType];

        $dsnHost = $dbHost . ( $dbPort != '' ? ":$dbPort" : '' );
        $dsnAuth = $dbUser . ( $dbPass != '' ? ":$dbPass" : '' );
        $dsn = "{$dbType}://{$dbUser}:{$dbPass}@{$dsnHost}/{$dbName}";

        return $dsn;
    }
}
ezcBaseInit::setCallback( 'ezcInitDatabaseInstance', 'ezpRestDbConfig' );

class ezpRestPoConfig implements ezcBaseConfigurationInitializer
{
    public static function configureObject( $instance )
    {
        return new ezcPersistentSession( ezcDbInstance::get(),
            new ezcPersistentMultiManager( array(
                new ezcPersistentCodeManager( 'kernel/private/rest/classes/po_maps/' ),
                new ezcPersistentCodeManager( 'kernel/private/oauth/classes/persistentobjects/' )
            ))
        );
    }
}
ezcBaseInit::setCallback( 'ezcInitPersistentSessionInstance', 'ezpRestPoConfig' );

?>
