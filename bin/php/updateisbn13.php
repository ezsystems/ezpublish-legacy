<?php
/**
 * File containing the updateisbn13.php bin script
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * This script updates the different ranges used by the ISBN standard to
 * calculate the length of Registration group, Registrant and Publication element
 *
 * It gets the values from xml file normally provided by International ISBN Agency
 * http://www.isbn-international.org/agency?rmxml=1
 */

require 'autoload.php';

$url = ''; // http://www.isbn-international.org/agency?rmxml=1 url with the xml.

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => "eZ Publish ISBN-13 update\n\n" .
                                                      "Update the database with new updated ISBN data to the database.",
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[url:][db-host:][db-user:][db-password:][db-database:][db-driver:]",
                                "",
                                array( 'url' => "URL containing the xml file for the different ranges",
                                       'db-host' => "Database host.",
                                       'db-user' => "Database user.",
                                       'db-password' => "Database password.",
                                       'db-database' => "Database name.",
                                       'db-driver' => "Database driver." ) );

$script->initialize();

if ( isset( $options['url'] ) )
{
    $url = $options['url'];
}
else
{
    $cli->error( 'Error: you need to specify a url to the xml file containing the ranges' );
    $script->shutdown( 1 );
}

$db = eZDB::instance();
if( !$db->IsConnected )
{
    // default settings are not valid
    // try user-defined settings

    $dbUser = $options['db-user'] ? $options['db-user'] : false;
    $dbPassword = $options['db-password'] ? $options['db-password'] : false;
    $dbHost = $options['db-host'] ? $options['db-host'] : false;
    $dbName = $options['db-database'] ? $options['db-database'] : false;
    $dbImpl = $options['db-driver'] ? $options['db-driver'] : false;

    if ( $dbHost or $dbName or $dbUser or $dbImpl )
    {
        $params = array();
        if ( $dbHost !== false )
            $params['server'] = $dbHost;
        if ( $dbUser !== false )
        {
            $params['user'] = $dbUser;
            $params['password'] = '';
        }
        if ( $dbPassword !== false )
            $params['password'] = $dbPassword;
        if ( $dbName !== false )
            $params['database'] = $dbName;
        $db = eZDB::instance( $dbImpl, $params, true );
        eZDB::setInstance( $db );
    }

    // still no success?
    if( !$db->IsConnected )
    {
        $cli->error( "Error: couldn't connect to database '" . $db->DB . "'" );
        $cli->error( '       for mysql try: ' );
        $cli->error( '          mysql -e "create database ' . $db->DB . ';"' );
        $cli->error( '          mysql tmp < kernel/sql/mysql/kernel_schema.sql' );
        $cli->error( '       or use --help for more info' );

        $script->shutdown( 1 );
    }
}

$cli->output( "Using database '" . $cli->terminalStyle( 'red' ) . $db->DB . $cli->terminalStyle( 'normal' ) . "'" );

$xml = simplexml_load_file( $url );

if ( $xml === false )
{
    $cli->error( "Error retrieving '$url'" );
    $script->shutdown( 1 );
}

// Clean up all tables to add everything from the start.
eZISBNGroup::cleanAll();
eZISBNGroupRange::cleanAll();
eZISBNRegistrantRange::cleanAll();

// Get registration groups.
$registrationGroups = $xml->xpath( 'RegistrationGroups/Group' );
foreach ( $registrationGroups as $group )
{
    // Prefix is always 978 or 979 followed by an hyphen (-) and up to 5 digits
    // Explode it in order to get the group number
    $prefixArray = explode( '-', $group->Prefix );
    $groupNumber = $prefixArray[1];
    $description = $group->Agency; // name
    $isbnGroup = eZISBNGroup::create( $groupNumber, $description );
    $isbnGroup->store();
    $isbnGroupID = $isbnGroup->attribute( 'id' );

    // look for the rules
    $rules = $group->Rules[0]->Rule;
    foreach ( $rules as $rule )
    {
        $length = (int)$rule->Length;

        // if length is 0 there is no need to add to the database
        if( $length > 0 )
        {
            $rangeArray = explode( '-', $rule->Range );
            $fromValue = substr( $rangeArray[0], 0, 5 );
            $toValue = substr( $rangeArray[1], 0, 5 );
            $registrantFrom = substr( $rangeArray[0], 0, $length );
            $registrantTo = substr( $rangeArray[1], 0, $length );
            $registrationGroup = eZISBNRegistrantRange::create( $isbnGroupID,
                                                                $fromValue,
                                                                $toValue,
                                                                $registrantFrom,
                                                                $registrantTo,
                                                                $length );
            $registrationGroup->store();
        }
    }
}

// get group ranges
$groupRanges = $xml->xpath( '///EAN.UCC/Rules/Rule' );
foreach( $groupRanges as $groupRange )
{
    $registrationGroupItemLength = (int)$groupRange->Length;

    // if length is 0 there is no need to add to the database
    if( $registrationGroupItemLength > 0 )
    {
        $rangeArray = explode( '-', $groupRange->Range );
        $fromValue = substr( $rangeArray[0], 0, 5 );
        $toValue = substr( $rangeArray[1], 0, 5 );
        $groupFrom = substr( $rangeArray[0], 0, $registrationGroupItemLength );
        $groupTo = substr( $rangeArray[1], 0, $registrationGroupItemLength );
        $registrationGroupRange = eZISBNGroupRange::create( $fromValue,
                                                            $toValue,
                                                            $groupFrom,
                                                            $groupTo,
                                                            $registrationGroupItemLength );
        $registrationGroupRange->store();
    }
}

$cli->output( 'Complete' );

$script->shutdown();
?>
