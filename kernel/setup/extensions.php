<?php
//
// Definition of Extensions class
//
// Created on: <03-Jul-2003 10:14:14 jhe>
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

$http = eZHTTPTool::instance();
$module = $Params['Module'];

require_once( "kernel/common/template.php" );
$tpl = templateInit();

$extensionDir = eZExtension::baseDirectory();
$availableExtensionArray = eZDir::findSubItems( $extensionDir, 'dl' );

// open site.ini for reading
$siteINI = eZINI::instance();
$siteINI->load();
$selectedExtensionArray       = $siteINI->variable( 'ExtensionSettings', "ActiveExtensions" );
$selectedAccessExtensionArray = $siteINI->variable( 'ExtensionSettings', "ActiveAccessExtensions" );
$selectedExtensions           = array_merge( $selectedExtensionArray, $selectedAccessExtensionArray );
$selectedExtensions           = array_unique( $selectedExtensions );

// When the user clicks on "Apply changes" button in admin interface in the Extensions section
if ( $module->isCurrentAction( 'ActivateExtensions' ) )
{
    $ini = eZINI::instance( 'module.ini' );
    $oldModules = $ini->variable( 'ModuleSettings', 'ModuleList' );

    if ( $http->hasPostVariable( "ActiveExtensionList" ) )
    {
        $selectedExtensionArray = $http->postVariable( "ActiveExtensionList" );
        if ( !is_array( $selectedExtensionArray ) )
            $selectedExtensionArray = array( $selectedExtensionArray );
    }
    else
    {
        $selectedExtensionArray = array();
    }

    // The file settings/override/site.ini.append.php is updated like this:
    // - take the existing list of extensions from site.ini.append.php (to preserve their order)
    // - remove from the list the extensions that the user unchecked in the admin interface
    // - add to the list the extensions checked by the user in the admin interface, but to the end of the list
    $intersection = array_intersect( $selectedExtensions, $selectedExtensionArray );
    $difference = array_diff( $selectedExtensionArray, $selectedExtensions );
    $toSave = array_merge( $intersection, $difference );
    $toSave = array_unique( $toSave );

    // open settings/override/site.ini.append[.php] for writing
    $writeSiteINI = eZINI::instance( 'site.ini.append', 'settings/override', null, null, false, true );
    $writeSiteINI->setVariable( "ExtensionSettings", "ActiveExtensions", $toSave );
    $writeSiteINI->save( 'site.ini.append', '.php', false, false );
    eZCache::clearByTag( 'ini' );

    eZSiteAccess::reInitialise();

    $ini = eZINI::instance( 'module.ini' );
    $currentModules = $ini->variable( 'ModuleSettings', 'ModuleList' );
    if ( $currentModules != $oldModules )
    {
        // ensure that evaluated policy wildcards in the user info cache
        // will be up to date with the currently activated modules
        eZCache::clearByID( 'user_info_cache' );
    }

    updateAutoload( $tpl );
}

// open site.ini for reading (need to do it again to take into account the changes made to site.ini after clicking "Apply changes" button above
$siteINI = eZINI::instance();
$siteINI->load();
$selectedExtensionArray       = $siteINI->variable( 'ExtensionSettings', "ActiveExtensions" );
$selectedAccessExtensionArray = $siteINI->variable( 'ExtensionSettings', "ActiveAccessExtensions" );
$selectedExtensions           = array_merge( $selectedExtensionArray, $selectedAccessExtensionArray );
$selectedExtensions           = array_unique( $selectedExtensions );

if ( $module->isCurrentAction( 'GenerateAutoloadArrays' ) )
{
    updateAutoload( $tpl );
}

$tpl->setVariable( "available_extension_array", $availableExtensionArray );
$tpl->setVariable( "selected_extension_array", $selectedExtensions );

$Result = array();
$Result['content'] = $tpl->fetch( "design:setup/extensions.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => eZi18n::translate( 'kernel/setup', 'Extension configuration' ) ) );

function updateAutoload( $tpl = null )
{
    $autoloadGenerator = new eZAutoloadGenerator();
    try
    {
        $autoloadGenerator->buildAutoloadArrays();

        $messages = $autoloadGenerator->getMessages();
        foreach( $messages as $message )
        {
            eZDebug::writeNotice( $message, 'eZAutoloadGenerator' );
        }

        $warnings = $autoloadGenerator->getWarnings();
        foreach ( $warnings as &$warning )
        {
            eZDebug::writeWarning( $warning, "eZAutoloadGenerator" );

            // For web output we want to mark some of the important parts of
            // the message
            $pattern = '@^Class\s+(\w+)\s+.* file\s(.+\.php).*\n(.+\.php)\s@';
            preg_match( $pattern, $warning, $m );

            $warning = str_replace( $m[1], '<strong>'.$m[1].'</strong>', $warning );
            $warning = str_replace( $m[2], '<em>'.$m[2].'</em>', $warning );
            $warning = str_replace( $m[3], '<em>'.$m[3].'</em>', $warning );
        }

        if ( $tpl !== null )
        {
            $tpl->setVariable( 'warning_messages', $warnings );
        }
    }
    catch ( Exception $e )
    {
        eZDebug::writeError( $e->getMessage() );
    }
}

?>
