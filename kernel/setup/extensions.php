<?php
//
// Definition of Extensions class
//
// Created on: <03-Jul-2003 10:14:14 jhe>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
$availableExtensionArray = eZDir::findSubItems( $extensionDir );

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
