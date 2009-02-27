<?php
/**
 * File containing the switchlanguage module
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

$languageSwitcherClassName = eZINI::instance()->variable( 'LanguageSwitcher', 'LanguageSwitcherClass' );

$Module = $Params['Module'];
$destinationSiteAccess = $Params['SiteAccess'];

$langSwitch = new $languageSwitcherClassName( $Params );
$langSwitch->setDestinationSiteAccess( $destinationSiteAccess );
$langSwitch->process();

$destinationUrl = $langSwitch->destinationUrl();

$Module->redirectTo( $destinationUrl );

?>