<?php
//
// Created on: <17-Apr-2007 15:47:58 bjorn>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//


/*! \file updateisbn13.php
  \brief Updates the different ranges used by the ISBN standard to
         calculate the length of Registration group, Registrant and Publication element.
 */

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

//include_once( 'kernel/classes/datatypes/ezisbn/ezisbngroup.php' );
//include_once( 'kernel/classes/datatypes/ezisbn/ezisbngrouprange.php' );
//include_once( 'kernel/classes/datatypes/ezisbn/ezisbnregistrantrange.php' );

$fileAdded = false;
$scriptFound = true;
$file = "";


$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => "eZ publish Isbn-13 update\n\n" .
                                                       "Update the database with new updated isbn data to the database.",
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();



if ( $script instanceof eZScript )
{
    $options = $script->getOptions( "[file:]",
                                    "",
                                    array( 'file' => "Path to the file which contains a JavaScript file for the different ranges" ) );

    if ( isset( $options['file'] ) )
    {
        $file = $options['file'];
    }
    else
    {
        $cli->output( 'You need to specify a Javascript file for the script with --file=ranges.js' );
    }
}
else
{
    print( "Warning: Unable to start the script." );
    $scriptFound = false;
}

$script->initialize();

// $file = "http://www.isbn-international.org/converter/ranges.js";

if ( $file != '' and $scriptFound === true )
{
    $content = '';
    $dataPage = '';
    $isbnArray = array();

    $fp = fopen( $file, "r"  );
    if ( $fp )
    {
        while ( !feof( $fp ) )
        {
            $content .= fread( $fp, 4096 );
        }
        fclose( $fp );

        // Parse the JavaScript file and add the different ranges into an array.
        $contentArray = preg_split( "/\n+|\r\n+/", $content );
        foreach ( $contentArray as $contentItem )
        {
            if ( preg_match( "/^\s*gi\.area(\d+)\.text\s*=\s*\"?([^\"]*)\"?\;?\s*$/i", $contentItem, $matchArray ) )
            {
                $regGroupElement = (string)$matchArray[1];
                $regGroupElementText = (string)$matchArray[2];
                $isbnArray[$regGroupElement]['text'] = trim( $regGroupElementText );
            }
            else if ( preg_match( "/^\s*gi\.area(\d+)\.pubrange\s*=\s*\"?([^\"]*?)\"?\;*\s*$/i", $contentItem, $matchArray ) )
            {
                $regGroupElement = (string)$matchArray[1];
                $regGroupElementRange = (string)$matchArray[2];
                if ( trim( $regGroupElementRange ) != "" )
                {
                    $pubRangeArray = preg_split( "/;/", $regGroupElementRange );
                    foreach ( $pubRangeArray as $pubRangeItem )
                    {
                        $pubRangeItemArray = preg_split( "/\-+/", $pubRangeItem );

                        // The ranges need to be stored as a string, since the can start
                        // with 0. The value from the javaScript may also only contain the
                        // start value.
                        if ( count( $pubRangeItemArray ) == 2 )
                        {
                            $pubRangeFrom = (string)$pubRangeItemArray[0];
                            $pubRangeTo = (string)$pubRangeItemArray[1];
                        }
                        else if ( count( $pubRangeItemArray ) == 1 )
                        {
                            $pubRangeFrom = (string)$pubRangeItemArray[0];
                            $pubRangeTo = (string)$pubRangeItemArray[0];
                        }
                        else
                        {
                            $cli->output( "Should not happend (Data: $pubRangeItem)" );
                            continue;
                        }

                        $length = strlen( $pubRangeFrom );
                        $pubLength = 10 - strlen( trim( $regGroupElement ) ) - $length;
                        $isbnArray[$regGroupElement]['pubrange'][] = array( 'from' => $pubRangeFrom,
                                                                            'group_from' => $pubRangeFrom,
                                                                            'to' => $pubRangeTo,
                                                                            'group_to' => $pubRangeTo,
                                                                            'length' => $length,
                                                                            'pub_length' => $pubLength );
                    }
                }
                else
                {
                    $isbnArray[$regGroupElement]['pubrange'] = array();
                }
            }
        }

        // Clean up all tables to add everything from the start.
        eZISBNGroup::cleanAll();
        eZISBNGroupRange::cleanAll();
        eZISBNRegistrantRange::cleanAll();

        $registrationGroupArray = array();
        foreach ( $isbnArray as $isbnRegGroupElement => $isbnItem )
        {
            // Calculate the range for the registration group by adding it to an array and
            // extend the range by checking the existing array.
            $length = strlen( $isbnRegGroupElement );
            $isbnRegGroupElementFrom = (int)str_pad( $isbnRegGroupElement, 5, 0, STR_PAD_RIGHT );
            $isbnRegGroupElementTo = (int)str_pad( $isbnRegGroupElement, 5, 9, STR_PAD_RIGHT );
            if ( isset( $registrationGroupArray[$length] ) )
            {
                $registrationGroupArrayCount = count( $registrationGroupArray[$length] );
                $added = false;

                // Extend the length of the range, if it exists allready from one number in front.
                for ( $i = 0; $i < $registrationGroupArrayCount; $i++ )
                {
                    $fromTestValue = (int)$registrationGroupArray[$length][$i]['from'];
                    $toTestValue = (int)$registrationGroupArray[$length][$i]['to'];
                    if (  ( $isbnRegGroupElementFrom - 1 ) == $toTestValue )
                    {
                        $registrationGroupArray[$length][$i]['to'] = $isbnRegGroupElementTo;
                        $added = true;
                    }

                    if ( ( $isbnRegGroupElementTo + 1 ) == $fromTestValue )
                    {
                        $registrationGroupArray[$length][$i]['from'] = $isbnRegGroupElementFrom;
                        $added = true;
                    }
                }

                // Since the range is not found as a continued range from the other ranges, so
                // create a new one.
                if ( $added == false )
                {
                    $registrationGroupArray[$length][] = array( 'from' => $isbnRegGroupElementFrom,
                                                                'to' => $isbnRegGroupElementTo );
                }
            }
            else // Range for this length does not exist, create a new one.
            {
                $registrationGroupArray[$length][] = array( 'from' => $isbnRegGroupElementFrom,
                                                            'to' => $isbnRegGroupElementTo );
            }

            $isbnGroup = eZISBNGroup::create( $isbnRegGroupElement, $isbnItem['text'] );
            $isbnGroup->store();
            $isbnGroupID = $isbnGroup->attribute( 'id' );
            $pubRangeArray = $isbnItem['pubrange'];
            if ( is_array( $pubRangeArray ) )
            {
                foreach ( $pubRangeArray as $isbnRegistrantRange )
                {
                    // The test number should have a base with 5 digits where from should be padded with 0 and
                    // to should be padded with 9.
                    $fromValue = (int)substr( str_pad( $isbnRegistrantRange['from'], 5, 0, STR_PAD_RIGHT ), 0, 5 );
                    $toValue = (int)substr( str_pad( $isbnRegistrantRange['to'], 5, 9, STR_PAD_RIGHT ), 0, 5 );
                    $length = $isbnRegistrantRange['length'];

                    $registrationGroup = eZISBNRegistrantRange::create( $isbnGroupID,
                                                                        $fromValue,
                                                                        $toValue,
                                                                        $isbnRegistrantRange['from'],
                                                                        $isbnRegistrantRange['to'],
                                                                        $length );
                    $registrationGroup->store();
                }
            }
        }


        // Add the registration group ranges to the database.
        if ( count( $registrationGroupArray ) > 0 )
        {
            foreach ( $registrationGroupArray as $registrationGroupItemLength => $registrationGroupItemArray )
            {
                foreach ( $registrationGroupItemArray as $registrationGroupItemRange )
                {
                    // Will cut the last part of the numbers, since it's up to each registrant to use the other
                    // numbers.
                    $fromValue = $registrationGroupItemRange['from'];
                    $toValue = $registrationGroupItemRange['to'];

                    // Create the group: from and to string with the correct length.
                    $groupFrom = str_pad( substr( $fromValue, 0, $registrationGroupItemLength ), 0, $registrationGroupItemLength );
                    $groupTo = str_pad( substr( $toValue, 0, $registrationGroupItemLength ), 0, $registrationGroupItemLength );

                    $registrationGroupRange = eZISBNGroupRange::create( $fromValue,
                                                                        $toValue,
                                                                        $groupFrom,
                                                                        $groupTo,
                                                                        $registrationGroupItemLength );
                    $registrationGroupRange->store();
                }
            }
        }
        $cli->output( 'Complete' );
    }
    else
    {
        $cli->output( "File not found: " . $file );
    }
}

$script->shutdown();

?>
