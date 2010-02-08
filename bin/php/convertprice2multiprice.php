#!/usr/bin/env php
<?php
//
// Created on: <20-Mar-2006 15:00:00 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

// file  bin/php/convertprice2multiprice.php

// description: the script will go through all classes and objects with 'ezprice'
//              datatype changing it to the 'ezmultiprice' datatype.
//              Note: the IDs and indentifiers of the classes, class attributes,
//                    objects, object attributes will not be changed.
//              Resulting 'ezmultiprice' will have 1 'custom' price(with value of
//              'ezprice') in currency of the current locale(the 'currency' object
//              will be created if it doesn't exist).


// script initializing
require 'autoload.php';

global $cli;
global $currencyList;

$currencyList = false;

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                         "This script will convert objects with 'price' datatype to\n" .
                                                         "the objects with 'multiprice' datatype.\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => false,
                                      'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "",
                                      "",
                                      array(),
                                      false,
                                      array( 'user' => true )
                                     );


$script->initialize();


$convertedObjectsCount = 0;

$classList = eZContentClass::fetchList();

$db = eZDB::instance();
$db->begin();
foreach ( $classList as $class )
{
    if ( eZShopFunctions::isSimplePriceClass( $class ) )
    {
        $classID = $class->attribute( 'id' );
        $objectListCount = eZContentObject::fetchSameClassListCount( $classID );
        if ( $objectListCount == 0 )
        {
            $cli->output( "No objects found for '" . $class->attribute( 'name' ) . "' class" );
            continue;
        }

        $cli->output( "Processing objects of the '" . $class->attribute( 'name' ) . "' class" );

        $defaultCurrency = currencyForLocale();

        if ( !$defaultCurrency )
            $script->shutdown( 1 );

        $defaultCurrencyCode = $defaultCurrency->attribute( 'code' );

        $priceClassAttribute = eZShopFunctions::priceAttribute( $class );
        $priceClassAttributeID = $priceClassAttribute->attribute( 'id' );

        // replace 'ezprice' class attribute with 'ezmultiprice'.
        $priceClassAttribute->setAttribute( 'data_type_string', 'ezmultiprice' );
        $priceClassAttribute->setAttribute( eZMultiPriceType::DEFAULT_CURRENCY_CODE_FIELD, $defaultCurrencyCode );
        $priceClassAttribute->store();

        unset( $GLOBALS['eZContentClassAttributeCache'][$priceClassAttributeID] );

        // update objects
        $offset = 0;
        $limit = 1000;

        $cli->output( 'Converting', false );
        while ( $offset < $objectListCount )
        {
            $objectList = eZContentObject::fetchSameClassList( $class->attribute( 'id' ), true, $offset, $limit );
            $offset += count( $objectList );

            foreach ( $objectList as $object )
            {
                $contentObjectID = $object->attribute( 'id' );
                $objectVersions =& $object->versions();
                foreach ( $objectVersions as $objectVersion )
                {
                    $version = $objectVersion->attribute( 'version' );
                    $objectAttributeList = eZContentObjectAttribute::fetchSameClassAttributeIDList( $priceClassAttributeID, true, $version, $contentObjectID );

                    foreach ( $objectAttributeList as $objectAttribute )
                    {
                        $priceValue = $objectAttribute->attribute( 'data_float' );

                        $multiprice = eZMultiPriceData::create( $objectAttribute->attribute( 'id' ),
                                                                $version,
                                                                $defaultCurrencyCode,
                                                                $priceValue,
                                                                eZMultiPriceData::VALUE_TYPE_CUSTOM );
                        $multiprice->store();

                        $objectAttribute->setAttribute( 'data_type_string', 'ezmultiprice' );
                        $objectAttribute->setAttribute( 'data_float', 0 );
                        $objectAttribute->setAttribute( 'sort_key_int', 0 );
                        $objectAttribute->store();
                    }
                }

                $cli->output( '.', false );
                ++$convertedObjectsCount;
            }
        }
        $cli->output( ' ' );
    }
}


// create/update autoprices.
if ( is_array( $currencyList ) )
{
    $cli->output( "Updating autoprices." );

    foreach ( $currencyList as $currencyCode => $currency )
    {
        eZMultiPriceData::createPriceListForCurrency( $currencyCode );
    }

    eZMultiPriceData::updateAutoprices();
}

$db->commit();

eZContentCacheManager::clearAllContentCache();

$cli->output( "Total converted objects: $convertedObjectsCount" );
$cli->output( "Done." );

$script->shutdown( 0 );

function currencyForLocale( $localeString = false )
{
    global $cli;
    global $currencyList;
    $currency = false;

    if ( $currencyList === false )
    {
        $currencyList = eZCurrencyData::fetchList();
    }

    $locale = eZLocale::instance( $localeString );
    if ( is_object( $locale ) )
    {
        // get currency
        if ( $currencyCode = $locale->currencyShortName() )
        {
            if ( !isset( $currencyList[$currencyCode] ) )
            {
                $cli->warning( "Currency '$currencyCode' doesn't exist" );
                $cli->notice( "Creating currency '$currencyCode'... ", false );

                $currencySymbol = $locale->currencySymbol();
                $localeCode = $locale->localeFullCode();

                if ( $currency = eZCurrencyData::create( $currencyCode, $currencySymbol, $localeCode, '0.00000', '1.00000', '0.00000' ) )
                {
                    $cli->output( 'Ok' );
                    $currency->store();
                    $currencyList[$currencyCode] = $currency;
                }
                else
                {
                    $cli->error( 'Failed' );
                }
            }
            else
            {
                $currency = $currencyList[$currencyCode];
            }
        }
        else
        {
            $cli->error( "Unable to find currency code for the '$localeString' locale" );
        }
    }
    else
    {
        $cli->error( "Unable to find '$localeString' locale" );
    }

    return $currency;
}

?>
