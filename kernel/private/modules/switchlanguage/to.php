<?php
/**
 * File containing the switchlanguage module
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

$handlerOptions = new ezpExtensionOptions();
$handlerOptions->iniFile = 'site.ini';
$handlerOptions->iniSection = 'RegionalSettings';
$handlerOptions->iniVariable = 'LanguageSwitcherClass';
$handlerOptions->handlerParams = array( $Params );

$langSwitch = eZExtension::getHandlerClass( $handlerOptions );

$Module = $Params['Module'];
$destinationSiteAccess = $Params['SiteAccess'];

$langSwitch->setDestinationSiteAccess( $destinationSiteAccess );
$langSwitch->process();

$destinationUrl = $langSwitch->destinationUrl();

$Module->redirectTo( $destinationUrl );

?>