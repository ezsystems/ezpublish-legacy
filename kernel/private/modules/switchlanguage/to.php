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

// 0. Module params are sent to constructor to process the request.
$handlerOptions->handlerParams = array( $Params );

$langSwitch = eZExtension::getHandlerClass( $handlerOptions );

$Module = $Params['Module'];
$destinationSiteAccess = $Params['SiteAccess'];

// Steps for language switcher classes

// 1. destination siteaccess is set
$langSwitch->setDestinationSiteAccess( $destinationSiteAccess );

// 2. The process hook is called, it is up to each class what this step involves.
$langSwitch->process();

// 3. The final URL is fetched from the language switcher class. This URL must
// point to the correct full URL including host (for host based mapping) and
// translated URL alias where applicable.
$destinationUrl = $langSwitch->destinationUrl();

// 4. The browser is redirected to the URL of the translated content.
$Module->redirectTo( $destinationUrl );

?>