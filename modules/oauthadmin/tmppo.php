<?php
/**
 * Temporary file containing persistent object initialization. To be refactored of course.
 */
// ezcDb init. @todo: should be refactored, but later
$dbMapping = array( 'ezmysqli' => 'mysql',
                    'ezmysql' => 'mysql',
                    'mysql' => 'mysql',
                    'mysqli' => 'mysql',
                    'postgresql' => 'pgsql',
                    'ezpostgresql' => 'pgsql',
                    'ezoracle' => 'oracle',
                    'oracle' => 'oracle' );

$ini = eZINI::instance();
list( $dbType, $dbHost, $dbPort, $dbUser, $dbPass, $dbName ) =
    $ini->variableMulti( 'DatabaseSettings',
        array( 'DatabaseImplementation', 'Server', 'Port', 'User', 'Password', 'Database' ) );
if ( !isset( $dbMapping[$dbType] ) )
{
    eZDebug::writeError( "Unknown / unmapped DB type '$dbType'" );
    return eZError::KERNEL_NOT_AVAILABLE;
}
else
{
    $dbType = $dbMapping[$dbType];
}
$dsnHost = $dbHost . ( $dbPort != '' ? ":$dbPort" : '' );
$dsnAuth = $dbUser . ( $dbPass != '' ? ":$dbPass" : '' );
$dsn = "{$dbType}://{$dbUser}:{$dbPass}@{$dsnHost}/{$dbName}";

$ezcDb = ezcDbFactory::create( $dsn );
$session = new ezcPersistentSession(
    $ezcDb,
    new ezcPersistentCacheManager( new ezcPersistentCodeManager( "extension/oauthadmin/classes/persistentobjects/" ) )
);
ezcPersistentSessionInstance::set( $session ); // set default session
// end ezcDb init.
?>