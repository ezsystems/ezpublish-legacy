<?php
/**
 * File containing the eZSetupFunctionCollection class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZSetupFunctionCollection ezsetupfunctioncollection.php
  \brief The class eZSetupFunctionCollection does

*/

class eZSetupFunctionCollection
{
    /*!
     Constructor
    */
    function eZSetupFunctionCollection()
    {
    }


    function fetchFullVersionString()
    {
        return array( 'result' => eZPublishSDK::version() );
    }

    function fetchMajorVersion()
    {
        return array( 'result' => eZPublishSDK::majorVersion() );
    }

    function fetchMinorVersion()
    {
        return array( 'result' => eZPublishSDK::minorVersion() );
    }

    function fetchRelease()
    {
        return array( 'result' => eZPublishSDK::release() );

    }

    function fetchState()
    {
        return array( 'result' => eZPublishSDK::state() );
    }

    function fetchIsDevelopment()
    {
        return array( 'result' => eZPublishSDK::developmentVersion() ? true : false );
    }

    function fetchDatabaseVersion( $withRelease = true )
    {
        return array( 'result' => eZPublishSDK::databaseVersion( $withRelease ) );
    }

    function fetchDatabaseRelease()
    {
        return array( 'result' => eZPublishSDK::databaseRelease() );
    }

    function fetchEdition()
    {
        return array( 'result' => eZPublishSDK::EDITION );
    }
}

?>
