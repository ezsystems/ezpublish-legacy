<?php
/**
 * File containing the ezpExtensionHelper class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 * @since 4.4
 */

/**
 * Class provides helper functions for extension loading.
 *
 * Useful for extension tests to be able to activate itself and
 * optionally other extensions.
 *
 * Example from extension/ezoe/tests/suite.php:
 *
<code>
     public function setUp()
    {
        // make sure extension is enabled and settings are read
        // give a warning if it is already enabled
        if ( !ezpExtensionHelper::load( 'ezoe' ) )
            trigger_error( __METHOD__ . ': extension is already loaded, this hints about missing cleanup in other tests that uses it!', E_USER_WARNING );

        parent::setUp();
    }

    public function tearDown()
    {
        ezpExtensionHelper::unload( 'ezoe' );
        parent::tearDown();
    }
</code>
 *
 * @package tests
 */
class ezpExtensionHelper
{
    /**
     * Loads an extension by adding it to ActiveExtensions setting,
     * clearing extensions cache and add ini dir (not extension siteaccess)
     *
     * Note: Does not check if extension exist on files system!
     *
     * @param string $extension Extension name to load
     * @return bool True on success, false if already loaded
     **/
    public static function load( $extension )
    {
        $ini = eZINI::instance();
        $activeExtensions = $ini->variable( 'ExtensionSettings', 'ActiveExtensions' );
        if ( in_array( $extension, $activeExtensions, true ) )
        {
            return false;
        }

        $activeExtensions[] = $extension;
        $ini->setVariable( 'ExtensionSettings', 'ActiveExtensions', $activeExtensions );
        $extensionDirectory = eZExtension::baseDirectory();
        $ini->prependOverrideDir( $extensionDirectory . '/' . $extension . '/settings', true, 'extension:' . $extension, 'extension' );
        eZExtension::clearActiveExtensionsMemoryCache();
        return true;
    }

    /**
     * Unloads an extension by removing it from ActiveExtensions setting,
     * clearing extensions cache and remove ini dir (not extension siteaccess)
     *
     * @param string $extension Extension name to unload
     * @return bool True on success, false if not loaded
     **/
    public static function unload( $extension )
    {
        $ini = eZINI::instance();
        $activeExtensions = $ini->variable( 'ExtensionSettings', 'ActiveExtensions' );
        if ( !in_array( $extension, $activeExtensions, true ) )
        {
            return false;
        }

        $ini->setVariable( 'ExtensionSettings', 'ActiveExtensions', array_diff( $activeExtensions, array( $extension ) ) );
        $ini->removeOverrideDir( 'extension:' . $extension );
        eZExtension::clearActiveExtensionsMemoryCache();
        return true;
    }

}
?>
