<?php
/**
 * Setup the test accounts on the ldap server.
 *
 * Assumes the username entries are 'uid'.
 * Note that all tests will crash horribly if any new trilogy characters are introduced here ;)
 *
 */

$dc = "dc=phpuc,dc=ez,dc=no";
$host = "phpuc.ez.no";

$connection = Ldap::connect( "ldap://$host", "cn=%s,{$dc}", 'admin', 'wee123' );

Ldap::delete( $connection, 'yoda', "ou=StarWars,{$dc}" );
Ldap::delete( $connection, 'boba.fett', "ou=StarWars,{$dc}" );
Ldap::delete( $connection, 'obi.wan', "ou=StarWars,{$dc}" );
Ldap::delete( $connection, 'jabba.thehutt', "ou=StarWars,{$dc}" );
Ldap::delete( $connection, 'darth.vader', "ou=StarWars,{$dc}" );
Ldap::delete( $connection, 'leia', "ou=StarWars,{$dc}" );
Ldap::delete( $connection, 'han.solo', "ou=StarWars,{$dc}" );
Ldap::delete( $connection, 'chewbacca', "ou=StarWars,{$dc}" );

Ldap::deleteGroup( $connection, 'Jedi', "ou=RebelAlliance,ou=StarWars,{$dc}" );
Ldap::deleteGroup( $connection, 'RebelAlliance', "ou=StarWars,{$dc}" );
Ldap::deleteGroup( $connection, 'Sith', "ou=GalacticEmpire,ou=StarWars,$dc" );
Ldap::deleteGroup( $connection, 'GalacticEmpire', "ou=StarWars,{$dc}" );
Ldap::deleteGroup( $connection, 'Rogues', "ou=StarWars,{$dc}" );
Ldap::deleteGroup( $connection, 'StarWars', $dc );

Ldap::addGroup( $connection, 'StarWars', $dc );

$chewbaccaDN =
Ldap::add( $connection, 'chewbacca', '{MD5}' . base64_encode( pack( 'H*', md5( 'aaawwwwrrrkk' ) ) ), "ou=StarWars,{$dc}", 'Chewbacca', 'Chewbacca',
           array( 'givenName' => 'Chewbacca',
                  'displayName' => 'Chewbacca the Wokiee',
                  'ou' => array( 'StarWars', 'Rogues', 'RebelAlliance' ),
                  'mail' => array( 'chewbacca@millenniumfalcon.net' ) ) );
$hanSoloDN =
Ldap::add( $connection, 'han.solo', '{MD5}' . base64_encode( pack( 'H*', md5( 'leiaishot' ) ) ), "ou=StarWars,{$dc}", 'Solo', 'Han Solo',
           array( 'givenName' => 'Han',
                  'displayName' => 'He who shot first',
                  'ou' => array( 'StarWars', 'Rogues', 'RebelAlliance' ),
                  'mail' => array( 'han.solo@millenniumfalcon.net' ) ) );
$princessLeiaDN =
Ldap::add( $connection, 'leia', '{MD5}' . base64_encode( pack( 'H*', md5( 'bunhead' ) ) ), "ou=StarWars,{$dc}", 'Organa', 'Leia Organa',
           array( 'givenName' => 'Leia',
                  'displayName' => 'Princess Leia',
                  'ou' => array( 'StarWars', 'RebelAlliance' ),
                  'mail' => array( 'leia@rebelalliance.org' ) ) );
$darthVaderDN =
Ldap::add( $connection, 'darth.vader', '{MD5}' . base64_encode( pack( 'H*', md5( 'whosyourdaddy' ) ) ), "ou=StarWars,{$dc}", 'Skywalker', 'Anakin Skywalker',
           array( 'givenName' => 'Anakin',
                  'displayName' => 'Darth Vader',
                  'ou' => array( 'StarWars', 'GalacticEmpire', 'Sith' ),
                  'mail' => array( 'vader@empire.com' ) ) );
$jabbaTheHuttDN =
Ldap::add( $connection, 'jabba.thehutt', '{MD5}' . base64_encode( pack( 'H*', md5( 'wishihadlegs' ) ) ), "ou=StarWars,{$dc}", 'Hutt', 'Jabba Hutt',
           array( 'givenName' => 'Jabba',
                  'displayName' => 'Jabba the Hutt',
                  'ou' => array( 'Hutts' ),
                  'mail' => array( 'jabba@hutt.com' ) ) );
$obiWanDN =
Ldap::add( $connection, 'obi.wan', '{MD5}' . base64_encode( pack( 'H*', md5( 'thesearenotthedroids' ) ) ), "ou=StarWars,{$dc}", 'Kenobi', 'Obi Wan Kenobi',
           array( 'givenName' => 'Obi Wan',
                  'displayName' => 'Obi Wan Kenobi',
                  'ou' => array( 'StarWars', 'RebelAlliance', 'Jedi' ),
                  'seeAlso' => array( "ou=StarWars,{$dc}", "ou=RebelAlliance,ou=StarWars,{$dc}", "ou=Jedi,ou=RebelAlliance,ou=StarWars,{$dc}" ),
                  'mail' => array( 'obi.wan@jedi.org' ) ) );
$bobaFettDN =
Ldap::add( $connection, 'boba.fett', '{MD5}' . base64_encode( pack( 'H*', md5( 'ihatesarlacs' ) ) ), "ou=StarWars,{$dc}", 'Fett', 'Boba Fett',
           array( 'givenName' => 'Boba',
                  'displayName' => 'Boba Fett',
                  'ou' => array( 'StarWars', 'Rogues' ),
                  'seeAlso' => array( "ou=StarWars,{$dc}", "ou=Rogues,ou=StarWars,{$dc}" ),
                  'mail' => array( 'boba.fett@bountyhunter.com' ) ) );
$yodaDN =
Ldap::add( $connection, 'yoda', '{MD5}' . base64_encode( pack( 'H*', md5( 'dagobah4eva' ) ) ), "ou=StarWars,{$dc}", 'Yoda', 'Yoda',
           array( 'givenName' => 'Yoda',
                  'displayName' => 'Yoda',
                  'ou' => array( 'StarWars', 'RebelAlliance', 'Jedi' ),
                  'seeAlso' => array( "ou=StarWars,{$dc}", "ou=RebelAlliance,ou=StarWars,{$dc}", "ou=Jedi,ou=RebelAlliance,ou=StarWars,{$dc}" ),
                  'mail' => array( 'yoda@jedi.org' ) ) );

Ldap::addGroup( $connection, 'RebelAlliance', "ou=StarWars,{$dc}",
                array( 'seeAlso' => array( $princessLeiaDN, $chewbaccaDN, $hanSoloDN, $obiWanDN, $yodaDN ) ) );
Ldap::addGroup( $connection, 'Rogues', "ou=StarWars,{$dc}",
                array( 'seeAlso' => array( $chewbaccaDN, $hanSoloDN ) ) );
Ldap::addGroup( $connection, 'GalacticEmpire', "ou=StarWars,{$dc}",
                array( 'seeAlso' => array( $darthVaderDN ) ) );
Ldap::addGroup( $connection, 'Sith', "ou=GalacticEmpire,ou=StarWars,$dc",
                array( 'seeAlso' => array( $darthVaderDN ) ) );
Ldap::addGroup( $connection, 'Jedi', "ou=RebelAlliance,ou=StarWars,{$dc}",
                array( 'seeAlso' => array( $obiWanDN, $yodaDN ) ) );

// This dumps all the LDAP data
// Ldap::fetchAll( $connection, $dc );

Ldap::close( $connection );


/**
 * Support for LDAP functions connect, add, delete and get_entries.
 */
class Ldap
{
    /**
     * Connects to an LDAP server specified by $uri, with admin $user and $password.
     *
     * Returns a resource which can be used in LDAP functions like add, delete, search.
     *
     * @param string $uri Uri for LDAP, such as 'ldap://example.com'
     * @param string $format Format for an entry, like 'cn=%s,dc=example,dc=com'. %s is a literal placeholder for username
     * @param string $user Admin username
     * @param string $password Password for admin
     * @return resource
     */
    public static function connect( $uri, $format, $user, $password )
    {
        if ( !extension_loaded( 'ldap' ) )
        {
            die( 'LDAP extension is not loaded.' );
        }
        $connection = ldap_connect( $uri );
        if ( !$connection )
        {
            throw new Exception( "Could not connect to host '{$uri}'" );
        }
        ldap_set_option( $connection, LDAP_OPT_PROTOCOL_VERSION, 3 );
        @ldap_bind( $connection, sprintf( $format, $user ), $password );
        $err = ldap_errno( $connection );
        switch ( $err )
        {
            case 0x51: // LDAP_SERVER_DOWN
            case 0x52: // LDAP_LOCAL_ERROR
            case 0x53: // LDAP_ENCODING_ERROR
            case 0x54: // LDAP_DECODING_ERROR
            case 0x55: // LDAP_TIMEOUT
            case 0x56: // LDAP_AUTH_UNKNOWN
            case 0x57: // LDAP_FILTER_ERROR
            case 0x58: // LDAP_USER_CANCELLED
            case 0x59: // LDAP_PARAM_ERROR
            case 0x5a: // LDAP_NO_MEMORY
                throw new Exception( "Could not connect to host '{$uri}'. (0x" . dechex( $err ) . ")" );
                break;
        }
        return $connection;
    }

    /**
     * Adds an entry in the LDAP directory.
     *
     * Throws a warning if the entry already exists.
     *
     * @param resource $connection Connection resource returned by ldap_connect()
     * @param string $user Username
     * @param string $password Password for username. Use an encryption function and put method in front of hash, like: '{MD5}hash'
     * @param string $dc The dc part of the entry, like: 'dc=example,dc=com'
     */
    public static function add( $connection, $user, $password, $dc, $sn, $cn, $extra = array() )
    {
        $ldaprecord['uid'][0] = $user;
        $ldaprecord['objectclass'][0] = "person";
        $ldaprecord['objectclass'][] = "organizationalPerson";
        $ldaprecord['objectclass'][] = "inetOrgPerson";
        $ldaprecord['sn'] = array( $sn );
        $ldaprecord['cn'] = array( $cn );
        $ldaprecord['objectclass'][] = "top";
        $ldaprecord['userPassword'][0] = $password;

        foreach ( $extra as $key => $value )
        {
            $ldaprecord[$key] = $value;
        }
        $dn = "uid={$user},{$dc}";
        $success = ldap_add( $connection, $dn, $ldaprecord );

        if ( $success )
            return $dn;
        else
            return false;
    }

    /**
     * Deletes an entry from the LDAP directory.
     *
     * @param resource $connection Connection resource returned by ldap_connect()
     * @param string $user Username to delete
     * @param string $dc The dc part of the entry, like: 'dc=example,dc=com'
     */
    public static function delete( $connection, $user, $dc )
    {
        ldap_delete( $connection, "uid={$user},{$dc}" );
    }

    public static function addGroup( $connection, $group, $dc, $extra = array() )
    {
        $ldaprecord['ou'] = $group;
        $ldaprecord['objectclass'][0] = "organizationalUnit";
        $ldaprecord['objectclass'][1] = "top";

        foreach ( $extra as $key => $value )
        {
            $ldaprecord[$key] = $value;
        }
        $dn = "ou={$group},{$dc}";
        $success = ldap_add( $connection, $dn, $ldaprecord );

        if ( $success )
            return $dn;
        else
            return false;
    }

    public static function deleteGroup( $connection, $group, $dc )
    {
        ldap_delete( $connection, "ou={$group},{$dc}" );
    }

    /**
     * Returns an array of all the entries in the LDAP directory.
     *
     * @param resource $connection Connection resource returned by ldap_connect()
     * @param string $dc The dc part of the entry, like: 'dc=example,dc=com'
     * @return array(mixed)
     */
    public static function fetchAll( $connection, $dc )
    {
        $sr = ldap_search( $connection, $dc, '(&(ou=*))' );
        var_dump( ldap_get_entries( $connection, $sr ) );
    }

    /**
     * Closes the connection to the LDAP server.
     *
     * @param resource $connection Connection resource returned by ldap_connect()
     */
    public static function close( $connection )
    {
        ldap_close( $connection );
    }
}
?>
