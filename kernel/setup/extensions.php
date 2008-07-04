<?php
//
// Definition of Extensions class
//
// Created on: <03-Jul-2003 10:14:14 jhe>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

include_once( "kernel/common/template.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezfile/classes/ezdir.php' );

$tpl =& templateInit();

if ( $module->isCurrentAction( 'ActivateExtensions' ) )
{
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

    // open settings/override/site.ini.append[.php] for writing
    $writeSiteINI = eZINI::instance( 'site.ini.append', 'settings/override', null, null, false, true );
    $writeSiteINI->setVariable( "ExtensionSettings", "ActiveExtensions", $selectedExtensionArray );
    $writeSiteINI->save( 'site.ini.append', '.php', false, false );
    include_once( 'kernel/classes/ezcache.php' );
    eZCache::clearByTag( 'ini' );
}
// open site.ini for reading
$siteINI = eZINI::instance();
$siteINI->loadCache();
$extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );
$availableExtensionArray = eZDir::findSubItems( $extensionDir, 'dl' );
sort( $availableExtensionArray );

$selectedExtensionArray       = $siteINI->variable( 'ExtensionSettings', "ActiveExtensions" );
$selectedAccessExtensionArray = $siteINI->variable( 'ExtensionSettings', "ActiveAccessExtensions" );
$selectedExtensions           = array_merge( $selectedExtensionArray, $selectedAccessExtensionArray );
$selectedExtensions           = array_unique( $selectedExtensions );

$tpl->setVariable( "available_extension_array", $availableExtensionArray );
$tpl->setVariable( "selected_extension_array", $selectedExtensions );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:setup/extensions.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'Extension configuration' ) ) );

?>
