<?php
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

//xdebug_start_profiling();

$scriptStartTime = microtime();
ob_start();

$use_external_css = true;
$show_page_layout = true;
$moduleRunRequired = true;
$policyCheckRequired = true;
$urlTranslatorAllowed = true;
$validityCheckRequired = false;
$userObjectRequired = true;
$sessionRequired = true;
$dbRequired = true;
$noCacheAdviced = false;

$siteDesignOverride = false;

// List of module names which will skip policy checking
$policyCheckOmitList = array();

// List of directories to search for modules
$moduleRepositories = array();

$siteBasics = array();
$siteBasics['external-css'] =& $use_external_css;
$siteBasics['show-page-layout'] =& $show_page_layout;
$siteBasics['module-run-required'] =& $moduleRunRequired;
$siteBasics['policy-check-required'] =& $policyheckRequired;
$siteBasics['policy-check-omit-list'] =& $policyCheckOmitList;
$siteBasics['url-translator-allowed'] =& $urlTranslatorAllowed;
$siteBasics['validity-check-required'] =& $validityCheckRequired;
$siteBasics['user-object-required'] =& $userObjectRequired;
$siteBasics['session-required'] =& $sessionRequired;
$siteBasics['db-required'] =& $dbRequired;
$siteBasics['no-cache-adviced'] =& $noCacheAdviced;
$siteBasics['site-design-override'] =& $siteDesignOverride;

$siteBasics['module-repositories'] =& $moduleRepositories;

$GLOBALS['eZSiteBasics'] =& $siteBasics;

error_reporting ( E_ALL );

// include standard libs
include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezutils/classes/ezdebugsetting.php" );

$debugINI =& eZINI::instance( 'debug.ini' );
eZDebugSetting::setDebugINI( $debugINI );


/*!
 Reads settings from site.ini and passes them to eZDebug.
*/
function eZUpdateDebugSettings()
{
    $ini =& eZINI::instance();
    $debugSettings = array();
    $debugSettings['debug-enabled'] = $ini->variable( 'DebugSettings', 'DebugOutput' ) == 'enabled';
    $debugSettings['debug-by-ip'] = $ini->variable( 'DebugSettings', 'DebugByIP' ) == 'enabled';
    $debugSettings['debug-ip-list'] = $ini->variable( 'DebugSettings', 'DebugIPList' );
    eZDebug::updateSettings( $debugSettings );
}

/*!
 Reads settings from i18n.ini and passes them to eZTextCodec.
*/
function eZUpdateTextCodecSettings()
{
    $ini =& eZINI::instance( 'i18n.ini' );
    $i18nSettings = array();
    $i18nSettings['internal-charset'] = $ini->variable( 'CharacterSettings', 'Charset' );
    $i18nSettings['http-charset'] = $ini->variable( 'CharacterSettings', 'HTTPCharset' );
    $i18nSettings['mbstring-extension'] = $ini->variable( 'CharacterSettings', 'MBStringExtension' ) == 'enabled';
    include_once( 'lib/ezi18n/classes/eztextcodec.php' );
    eZTextCodec::updateSettings( $i18nSettings );
}

// Initialize text codec settings
eZUpdateTextCodecSettings();

// Initialize debug settings.
eZUpdateDebugSettings();


// Set the different permissions/settings.
$ini =& eZINI::instance();
$iniFilePermission = $ini->variable( 'FileSettings', 'StorageFilePermissions' );
$iniDirPermission = $ini->variable( 'FileSettings', 'StorageDirPermissions' );
$iniVarDirectory = eZSys::cacheDirectory() ;

eZCodepage::setPermissionSetting( array( 'file_permission' => octdec( $iniFilePermission ),
                                         'dir_permission'  => octdec( $iniDirPermission ),
                                         'var_directory'   => $iniVarDirectory ) );

//
$warningList = array();

/*!
 Appends a new warning item to the warning list.
 \a $parameters must contain a \c error and \c text key.
*/
function eZAppendWarningItem( $parameters = array() )
{
    global $warningList;
    $parameters = array_merge( array( 'error' => false,
                                      'text' => false ),
                               $parameters );
    $error = $parameters['error'];
    $text = $parameters['text'];
    $warningList[] = array( 'error' => $error,
                            'text' => $text );
}

include_once( 'lib/ezutils/classes/ezexecution.php' );

function eZDBCleanup()
{
    if ( class_exists( 'ezdb' )
         and eZDB::hasInstance() )
    {
        $db =& eZDB::instance();
        $db->setIsSQLOutputEnabled( false );
    }
//     session_write_close();
}

function eZFatalError()
{
    eZDebug::setHandleType( EZ_HANDLE_NONE );
    print( "<b>Fatal error</b>: eZ publish did not finish it's request<br/>" );
    print( "<p>The execution of eZ publish was abruptly ended, the debug output is present below.</p>" );
    $templateResult = null;
    eZDisplayResult( $templateResult, eZDisplayDebug() );
}

eZExecution::addCleanupHandler( 'eZDBCleanup' );
eZExecution::addFatalErrorHandler( 'eZFatalError' );

eZDebug::setScriptStart( $scriptStartTime );

// Enable this line to get eZINI debug output
// eZINI::setIsDebugEnabled( true );
// Enable this line to turn off ini caching
// eZINI::setIsCacheEnabled( false);

function &eZDisplayDebug()
{
    $ini =& eZINI::instance();

    $type = $ini->variable( "DebugSettings", "Debug" );
    eZDebug::setHandleType( EZ_HANDLE_NONE );
    if ( $type == "inline" or $type == "popup" )
    {
        $text =& eZDebug::printReport( $type == "popup", true, true );
        return $text;
    }
    return null;
}

function eZDisplayResult( &$templateResult, &$debugReport )
{
    if ( $debugReport !== null )
    {
        if ( $templateResult !== null )
        {
            $debugMarker = '<!--DEBUG_REPORT-->';
            $pos = strpos( $templateResult, $debugMarker );
            if ( $pos !== false )
            {
                $debugMarkerLength = strlen( $debugMarker );
                $templateResult = substr_replace( $templateResult, $debugReport, $pos, $debugMarkerLength );
            }
            else
                $templateResult = implode( '', array( $templateResult, $debugReport ) );
        }
        else
            $templateResult = $debugReport;
    }

    print( $templateResult );
}

function fetchModule( &$uri, &$check, &$module, &$module_name, &$function_name, &$params )
{
    $module_name = $uri->element();
    if ( $check !== null and isset( $check["module"] ) )
        $module_name = $check["module"];

    // Try to fetch the module object
    $module = eZModule::exists( $module_name );
    if ( get_class( $module ) != "ezmodule" )
        return false;

    $uri->increase();
    $function_name = "";
    if ( !$module->singleFunction() )
    {
        $function_name = $uri->element();
        $uri->increase();
    }
    // Override it if required
    if ( $check !== null and isset( $check["function"] ) )
        $function_name = $check["function"];

    $params = $uri->elements( false );
    return true;
}

include_once( 'lib/ezi18n/classes/eztextcodec.php' );
$httpCharset = eZTextCodec::httpCharset();
include_once( 'lib/ezlocale/classes/ezlocale.php' );
$ini =& eZINI::instance();
if ( $ini->variable( 'RegionalSettings', 'Debug' ) == 'enabled' )
    eZLocale::setIsDebugEnabled( true );

include_once( "lib/ezutils/classes/ezsys.php" );


eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

$GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );

// Initialize basic settings, such as vhless dirs and separators

eZSys::init( 'index.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) == 'true' );

eZSys::initIni( $ini );

eZDebug::addTimingPoint( "Script start" );

include_once( "lib/ezutils/classes/ezuri.php" );

$uri =& eZURI::instance( eZSys::requestURI() );
$GLOBALS['eZRequestedURI'] =& $uri;
include_once( "pre_check.php" );

// Shall we start the eZ setup module?
//if ( $ini->variable( "SiteAccessSettings", "CheckValidity" ) == "true" )
//    include_once( "lib/ezsetup/classes/ezsetup.php" );

include_once( 'kernel/error/errors.php' );

/*
print( "<pre>" );
var_dump( $_SERVER );
print( "</pre>" );
print( "HTTP_HOST=" . eZSys::serverVariable( 'HTTP_HOST' ) . "<br/" );
*/

// include ezsession override implementation
include_once( "lib/ezutils/classes/ezsession.php" );


include( "lib/ezutils/classes/ezweb.php" );

eZWeb::init();

// Check for extension
include_once( 'lib/ezutils/classes/ezextension.php' );
include_once( 'kernel/common/ezincludefunctions.php' );
eZExtension::activateExtensions( 'default' );
// Extension check end

include_once( "access.php" );

$access = accessType( $uri,
                      eZSys::hostname(),
                      eZSys::serverPort(),
                      eZSys::indexFile() );
$access = changeAccess( $access );
eZDebugSetting::writeDebug( 'kernel-siteaccess', $access, 'current siteaccess' );
$GLOBALS['eZCurrentAccess'] =& $access;

// Check for siteaccess extension
eZExtension::activateExtensions( 'access' );
// Siteaccess extension check end

$check = eZHandlePreChecks( $siteBasics, $uri );

include_once( 'kernel/common/i18n.php' );

if ( $sessionRequired )
{
	$dbRequired = true;
}

$db = false;
if ( $dbRequired )
{
    include_once( 'lib/ezdb/classes/ezdb.php' );
    $db =& eZDB::instance();
    if ( $sessionRequired and
         $db->isConnected() )
    {
        eZSessionStart();
    }

    if ( !$db->isConnected() )
        $warningList[] = array( 'error' => array( 'type' => 'kernel',
                                                  'number' => EZ_ERROR_KERNEL_NO_DB_CONNECTION ),
                                'text' => 'No database connection could be made, the system might not behave properly.' );
}

// Initialize with locale settings
include_once( "lib/ezlocale/classes/ezlocale.php" );
$locale =& eZLocale::instance();
$languageCode =& $locale->httpLocaleCode();

// send header information
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . 'GMT' );
header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Pragma: no-cache' );
header( 'X-Powered-By: eZ publish' );

header( 'Content-Type: text/html; charset=' . $httpCharset );
header( 'Content-language: ' . $languageCode );

include_once( 'kernel/classes/ezsection.php' );
eZSection::initGlobalID();

// Read role settings
$globalPolicyCheckOmitList = $ini->variable( 'RoleSettings', 'PolicyOmitList' );
$policyCheckOmitList = array_merge( $policyCheckOmitList, $globalPolicyCheckOmitList );
$policyCheckViewMap = array();
foreach ( $policyCheckOmitList as $omitItem )
{
    $items = explode( '/', $omitItem );
    if ( count( $items ) > 1 )
    {
        $module = $items[0];
        $view = $items[1];
        if ( !isset( $policyCheckViewMap[$module] ) )
            $policyCheckViewMap[$module] = array();
        $policyCheckViewMap[$module][] = $view;
    }
}

// Initialize module loading
include_once( "lib/ezutils/classes/ezmodule.php" );

$moduleINI =& eZINI::instance( 'module.ini' );
$globalModuleRepositories = $moduleINI->variable( 'ModuleSettings', 'ModuleRepositories' );
$extensionRepositories = $moduleINI->variable( 'ModuleSettings', 'ExtensionRepositories' );
$extensionDirectory = eZExtension::baseDirectory();
$globalExtensionRepositories = array();
foreach ( $extensionRepositories as $extensionRepository )
{
    $modulePath = $extensionDirectory . '/' . $extensionRepository . '/modules';
    if ( file_exists( $modulePath ) )
    {
        $globalExtensionRepositories[] = $modulePath;
    }
}
$moduleRepositories = array_merge( $moduleRepositories, $globalModuleRepositories, $globalExtensionRepositories );
eZModule::setGlobalPathList( $moduleRepositories );

include_once( 'kernel/classes/eznavigationpart.php' );

// Start the module loop
while ( $moduleRunRequired )
{
    $objectHasMovedError = false;
    $objectHasMovedURI = false;
    // Check for URL translation
    if ( $urlTranslatorAllowed and
         $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled' and
         !$uri->isEmpty() )
    {
        include_once( 'kernel/classes/ezurlalias.php' );
        $userParameters = $uri->userParameters();
        $translateResult =& eZURLAlias::translate( $uri );

        if ( !$translateResult )
        {
            $useWildcardTranslation = $ini->variable( 'URLTranslator', 'WildcardTranslation' ) == 'enabled';
            if ( $useWildcardTranslation )
            {
                $translateResult =& eZURLAlias::translateByWildcard( $uri );
            }
        }

        // Check if the URL has moved
        if ( get_class( $translateResult ) == 'ezurlalias' )
        {
            $objectHasMovedURI =& $translateResult->attribute( 'source_url' );
            $objectHasMovedError = true;
        }
        else if ( is_string( $translateResult ) )
        {
            $objectHasMovedURI = $translateResult;
            $objectHasMovedError = true;
        }
    }

    // Store the last URI for access history for login redirection
    // Only if database is connected
    if ( is_object( $db ) and $db->isConnected() )
    {
        $currentURI = $uri->uriString( true );
        $lastAccessedURI = "";
        $http =& eZHTTPTool::instance();
        if ( $http->hasSessionVariable( "LastAccessesURI" ) )
            $lastAccessedURI = $http->sessionVariable( "LastAccessesURI" );
        if ( $currentURI != $lastAccessedURI )
        {
            if ( preg_match( "/\/content\/view\/.*/", $currentURI  ) )
            {
                $http->setSessionVariable( "LastAccessesURI", $currentURI );
            }
        }
    }

    $moduleCheck = accessAllowed( $uri );
    if ( !$moduleCheck['result'] )
    {
        if ( $ini->variable( "SiteSettings", "ErrorHandler" ) == "defaultpage" )
        {
            $defaultPage = $ini->variable( "SiteSettings", "DefaultPage" );
            $uri->setURIString( $defaultPage );
            $moduleCheck['result'] = true;
        }
    }

    include_once( "lib/ezutils/classes/ezhttptool.php" );
    $http =& eZHTTPTool::instance();

    $displayMissingModule = false;
    $oldURI = $uri;

    if ( $uri->isEmpty() )
    {
        $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "IndexPage" ) );
        if ( !fetchModule( $tmp_uri, $check, $module, $module_name, $function_name, $params ) )
            $displayMissingModule = true;
    }
    else if ( !fetchModule( $uri, $check, $module, $module_name, $function_name, $params ) )
    {
        if ( $ini->variable( "SiteSettings", "ErrorHandler" ) == "defaultpage" )
        {
            $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "DefaultPage" ) );
            if ( !fetchModule( $tmp_uri, $check, $module, $module_name, $function_name, $params ) )
                $displayMissingModule = true;
        }
        else
            $displayMissingModule = true;
    }

    if ( !$displayMissingModule and
         $moduleCheck['result'] and
         get_class( $module ) == "ezmodule" )
    {
        // Run the module/function
        eZDebug::addTimingPoint( "Module start '" . $module->attribute( 'name' ) . "'" );

        $moduleAccessAllowed = true;
        $omitPolicyCheck = true;
        $runModuleView = true;
        if ( $policyCheckRequired )
        {
            $omitPolicyCheck = false;
            $moduleName = $module->attribute( 'name' );
            $viewName = $function_name;
            if ( in_array( $moduleName, $policyCheckOmitList ) )
                $omitPolicyCheck = true;
            else if ( isset( $policyCheckViewMap[$moduleName] ) and
                      in_array( $viewName, $policyCheckViewMap[$moduleName] ) )
                $omitPolicyCheck = true;
        }
        if ( !$omitPolicyCheck )
        {
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $currentUser =& eZUser::currentUser();

            $availableViewsInModule = $module->attribute( 'views' );
            $runningFunctions = false;
            if ( isset( $availableViewsInModule[$function_name][ 'functions' ] ) )
                $runningFunctions = $availableViewsInModule[$function_name][ 'functions' ];
            $siteAccessResult = $currentUser->hasAccessTo( 'user', 'login' );
            $hasAccessToSite = false;
            if ( $siteAccessResult[ 'accessWord' ] == 'limited' )
            {
                foreach ( array_keys( $siteAccessResult['policies'] ) as $key )
                {
                    $policy =& $siteAccessResult['policies'][$key];
                    $limitations =& $policy->attribute( 'limitations' );
                    foreach ( array_keys( $limitations ) as $limitationKey )
                    {
                        $limitation =& $limitations[$limitationKey];
                        if ( $limitation->attribute( 'identifier' ) == 'SiteAccess' )
                        {
                            $limitationValues =& $limitation->attribute( 'values_as_array' );
                            eZDebugSetting::writeDebug( 'kernel-siteaccess', $limitationValues, crc32( $access[ 'name' ] ));
                            if ( in_array( crc32( $access[ 'name' ] ), $limitationValues ) )
                            {
                                $hasAccessToSite = true;
                                break;
                            }
                        }
                    }
                    if ( $hasAccessToSite )
                        break;
                }
            }
            else if ( $siteAccessResult[ 'accessWord' ] == 'yes' )
            {
                eZDebugSetting::writeDebug( 'kernel-siteaccess', "access is yes" );
                $hasAccessToSite = true;
            }

            if ( $hasAccessToSite )
            {
                $accessResult = $currentUser->hasAccessTo( $module->attribute( 'name' ), $runningFunctions[0] );
                if ( $accessResult['accessWord'] == 'limited' )
                {
                    $moduleName = $module->attribute( 'name' );
                    $functionName = $runningFunctions[0];
                    $params['Limitation'] =& $accessResult['policies'];
                    $GLOBALS['ezpolicylimitation_list'][$moduleName][$functionName] =& $params['Limitation'];
                }
                if ( $accessResult['accessWord'] == 'no' )
                    $moduleAccessAllowed = false;
            }
            else
            {
                eZDebugSetting::writeDebug( 'kernel-siteaccess', $access, 'not able to get access to siteaccess' );
                $moduleAccessAllowed = false;
                $requireUserLogin = ( $ini->variable( "SiteAccessSettings", "RequireUserLogin" ) == "true" );
                if ( $requireUserLogin )
                {
                    $module = eZModule::exists( 'user' );
                    if ( get_class( $module ) == "ezmodule" )
                    {
                        $moduleResult =& $module->run( 'login', array(),
                                                       array( 'SiteAccessAllowed' => false,
                                                              'SiteAccessName' => $access['name'] ) );
                        $runModuleView = false;
                    }
                }
            }
        }

        $GLOBALS['eZRequestedModule'] =& $module;

        if ( $runModuleView )
        {
            if ( $objectHasMovedError == true )
            {
                $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_MOVED, 'kernel', array( 'new_location' => $objectHasMovedURI ) );
            }
            else if ( !$moduleAccessAllowed )
            {
                $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
            }
            else
            {
                if ( !isset( $userParameters ) )
                {
                    $userParameters = false;
                }

                $moduleResult =& $module->run( $function_name, $params, false, $userParameters );

                if ( $module->exitStatus() == EZ_MODULE_STATUS_FAILED and
                     $moduleResult == null )
                    $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_MODULE_VIEW_NOT_FOUND, 'kernel', array( 'module' => $module_name,
                                                                                                                   'view' => $function_name ) );
            }
        }
    }
    else if ( $moduleCheck['result'] )
    {
        eZDebug::writeError( "Undefined module: $module_name", "index" );
        $module = new eZModule( "", "", $module_name );
        $GLOBALS['eZRequestedModule'] =& $module;
        $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_MODULE_NOT_FOUND, 'kernel', array( 'module' => $module_name ) );
    }
    else
    {
        if ( $moduleCheck['view_checked'] )
            eZDebug::writeError( "View '" . $moduleCheck['view'] . "' in module '" . $moduleCheck['module'] . "' is disabled", "index" );
        else
            eZDebug::writeError( "Module '" . $moduleCheck['module'] . "' is disabled", "index" );
        $module = new eZModule( "", "", $moduleCheck['module'] );
        $GLOBALS['eZRequestedModule'] =& $module;
        $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_MODULE_DISABLED, 'kernel', array( 'check' => $moduleCheck ) );
    }
    $GLOBALS['eZRequestedModuleParams'] = array( 'module_name' => $module_name,
                                                 'function_name' => $function_name,
                                                 'parameters' => $module->getNamedParameters() );
    $moduleRunRequired = false;
    if ( $module->exitStatus() == EZ_MODULE_STATUS_RERUN )
    {
        if ( isset( $moduleResult['rerun_uri'] ) )
        {
            $uri = & eZURI::instance( $moduleResult['rerun_uri'] );
            $moduleRunRequired = true;
        }
        else
            eZDebug::writeError( 'No rerun URI specified, cannot continue', 'index.php' );
    }

    if ( is_array( $moduleResult ) )
    {
        if ( isset( $moduleResult["pagelayout"] ) )
            $show_page_layout =& $moduleResult["pagelayout"];
        if ( isset( $moduleResult["external_css"] ) )
            $use_external_css =& $moduleResult["external_css"];
    }
}

if ( $module->exitStatus() == EZ_MODULE_STATUS_REDIRECT )
{
    $ini =& eZINI::instance();
    $uri =& eZURI::instance( eZSys::requestURI() );

    $redir_uri = $ini->variable( "DebugSettings", "DebugRedirection" );
    $automatic_redir = true;
    if ( $redir_uri == "enabled" )
    {
        $automatic_redir = false;
    }
    else if ( $redir_uri != "disabled" )
    {
        $redir_uris = $ini->variableArray( "DebugSettings", "DebugRedirection" );
        $uri->toBeginning();
        foreach ( $redir_uris as $redir_uri )
        {
            $redir_uri = new eZURI( $redir_uri );
            if ( $redir_uri->matchBase( $uri ) )
            {
                $automatic_redir = false;
                break;
            }
        }
    }

    $redirectURI = eZSys::indexDir();
//     eZDebug::writeDebug( eZSys::indexDir(), 'eZSys::indexDir()' );
//     eZDebug::writeDebug( $module->redirectURI(), '$module->redirectURI()' );

    $moduleRedirectUri = $module->redirectURI();
    $translatedModuleRedirectUri = $moduleRedirectUri;
    if ( $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
    {
        if ( eZURLAlias::translate( $translatedModuleRedirectUri, true ) )
        {
            $moduleRedirectUri = $translatedModuleRedirectUri;
            if ( strlen( $moduleRedirectUri ) > 0 and
                 $moduleRedirectUri[0] != '/' )
                $moduleRedirectUri = '/' . $moduleRedirectUri;
        }
    }

    if ( preg_match( '#^(\w+:)|^//#', $moduleRedirectUri ) )
    {
        $redirectURI = $moduleRedirectUri;
    }
    else
    {
        $redirectURI .= $moduleRedirectUri;
    }

    if ( $automatic_redir )
    {
        eZHTTPTool::redirect( $redirectURI );
    }
    else
    {
        include_once( "kernel/common/template.php" );
        $tpl =& templateInit();
        $tpl->setVariable( 'redirect_uri', $redirectURI );
        $templateResult =& $tpl->fetch( 'design:redirect.tpl' );

        eZDebug::addTimingPoint( "End" );

        eZDisplayResult( $templateResult, eZDisplayDebug() );
    }

    eZExecution::cleanExit();
}

eZDebug::addTimingPoint( "Module end '" . $module->attribute( 'name' ) . "'" );
if ( !is_array( $moduleResult ) )
{
    eZDebug::writeError( 'Module did not return proper result: ' . $module->attribute( 'name' ), 'index.php' );
    $moduleResult = array();
    $moduleResult['content'] = false;
}

$templateResult = null;

eZDebug::setUseExternalCSS( $use_external_css );
if ( $show_page_layout )
{
    include_once( "kernel/common/template.php" );
    $tpl =& templateInit();
    if ( !isset( $moduleResult['path'] ) )
        $moduleResult['path'] = false;
    $moduleResult['uri'] =& eZSys::requestURI();

    $tpl->setVariable( "module_result", $moduleResult );

    $meta = $ini->variable( 'SiteSettings', 'MetaDataArray' );

    $metaDescription = "";
    if ( isset( $moduleResult['path'] ) and
         is_array( $moduleResult['path'] ) )
    {
        foreach ( $moduleResult['path'] as $pathPart )
        {
            if ( isset( $pathPart['text'] ) )
                $metaDescription .= $pathPart['text'] . " ";
        }
    }
    $meta['description'] = $metaDescription;

    $http_equiv = array( 'Content-Type' => 'text/html; charset=' . $httpCharset,
                         'Content-language' => $languageCode );

    include_once( 'lib/version.php' );
    $site = array(
        "title" => $ini->variable( 'SiteSettings', 'SiteName' ),
        "page_title" => $module->title(),
        "uri" => $oldURI,
        "redirect" => false,
        "design" => "standard",
        "http_equiv" => $http_equiv,
        "meta" => $meta,
        "version" => eZPublishSDK::version());
    $tpl->setVariable( "site", $site );

    include_once( 'lib/version.php' );
    $ezinfo = array( 'version' => eZPublishSDK::version( true ),
                     'version_alias' => eZPublishSDK::version( true, true ),
                     'revision' => eZPublishSDK::revision() );

    $tpl->setVariable( "ezinfo", $ezinfo );
    if ( isset( $tpl_vars ) and is_array( $tpl_vars ) )
    {
        foreach( $tpl_vars as $tpl_var_name => $tpl_var_value )
        {
            $tpl->setVariable( $tpl_var_name, $tpl_var_value );
        }
    }

    if ( $show_page_layout )
    {
        if ( $userObjectRequired )
        {
            // include user class
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

            $currentUser =& eZUser::currentUser();
            $tpl->setVariable( "current_user", $currentUser );
            $tpl->setVariable( "anonymous_user_id", $ini->variable( 'UserSettings', 'AnonymousUserID' ) );
        }
        else
        {
            $tpl->setVariable( "current_user", false );
            $tpl->setVariable( "anonymous_user_id", false );
        }

//         include_once( "lib/ezutils/classes/ezexecutionstack.php" );
//         $execStack =& eZExecutionStack::instance();
//         $tpl->setVariable( "execution_entries", $execStack->entries() );

        $tpl->setVariable( "access_type", $access );

        if ( count( $warningList ) == 0 )
            $warningList = false;
        $tpl->setVariable( 'warning_list', $warningList );

        $resource = "design:";
        if ( is_string( $show_page_layout ) )
        {
            if ( strpos( $show_page_layout, ":" ) !== false )
            {
                $resource = "";
            }
        }
        else
        {
            $show_page_layout = "pagelayout.tpl";
        }

        // Set the navigation part
        // Check for navigation part settings
        $navigationPartString = 'ezcontentnavigationpart';
        if ( isset( $moduleResult['navigation_part'] ) )
        {
            $navigationPartString = $moduleResult['navigation_part'];

            // Fetch the navigation part
        }
        $navigationPart = eZNavigationPart::fetchPartByIdentifier( $navigationPartString );

        $tpl->setVariable( 'navigation_part', $navigationPart );
        $tpl->setVariable( 'uri_string', $uri->uriString() );
        $templateResult =& $tpl->fetch( $resource . $show_page_layout );
    }
}
else
{
    $templateResult =& $moduleResult['content'];
}


eZDebug::addTimingPoint( "End" );

eZDisplayResult( $templateResult, eZDisplayDebug() );

ob_end_flush();

eZExecution::cleanup();
eZExecution::setCleanExit();

//xdebug_dump_function_profile( 4 );

?>
