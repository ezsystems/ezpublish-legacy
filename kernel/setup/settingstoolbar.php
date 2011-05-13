<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$http = eZHTTPTool::instance();
$module = $Params['Module'];

$allSettingsList = $module->actionParameter( 'AllSettingsList' );

if ( $module->hasActionParameter( 'SelectedList' ) )
    $selectedList = $module->actionParameter( 'SelectedList' );
else
    $selectedList=array();

$siteAccess = $module->actionParameter( 'SiteAccess' );
if ( !$siteAccess )
    $siteAccess = 'global_override';

eZPreferences::setValue( 'admin_quicksettings_siteaccess', $siteAccess );

$iniFiles = array();

foreach( $allSettingsList as $index => $setting )
{
    $settingArray = explode( ';', $setting );

    if ( !array_key_exists( $settingArray[2], $iniFiles ) )
        $iniFiles[$settingArray[2]] = array();

    $iniFiles[$settingArray[2]][] = array ( $settingArray[0], $settingArray[1], in_array( $index, $selectedList ) );
}
unset( $setting );

$iniPath = ( $siteAccess == "global_override" ) ? "settings/override" : "settings/siteaccess/$siteAccess";

foreach( $iniFiles as $fileName => $settings )
{
    $ini = new eZINI( $fileName . '.append', $iniPath, null, null, null, true, true );
    $baseIni = eZINI::instance( $fileName );

    foreach( $settings as $setting )
    {
        if ( $ini->hasVariable( $setting[0], $setting[1] ) )
            $value = $ini->variable( $setting[0], $setting[1] );
        else
            $value = $baseIni->variable( $setting[0], $setting[1] );

        if ( $value == 'true' || $value == 'false' )
            $ini->setVariable( $setting[0], $setting[1], $setting[2] ? 'true' : 'false' );
        else
            $ini->setVariable( $setting[0], $setting[1], $setting[2] ? 'enabled' : 'disabled' );
    }

    if ( !$ini->save() )
    {
        eZDebug::writeError( "Can't save ini file: $iniPath/$fileName.append" );
    }

    unset( $baseIni );
    unset( $ini );

    // Remove variable from the global override
    if ( $siteAccess != "global_override" )
    {
        $ini = new eZINI( $fileName . '.append', "settings/override", null, null, null, true, true );
        foreach( $settings as $setting )
        {
            if ( $ini->hasVariable( $setting[0], $setting[1] ) )
                $ini->removeSetting( $setting[0], $setting[1] );
        }
        if ( !$ini->save() )
        {
            eZDebug::writeError( "Can't save ini file: $iniPath/$fileName.append" );
        }

        unset($ini);
    }
}

$uri = $http->postVariable( 'RedirectURI', $http->sessionVariable( 'LastAccessedModifyingURI', '/' ) );
$module->redirectTo( $uri );

?>
