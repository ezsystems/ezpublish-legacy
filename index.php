<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

if ( version_compare( phpversion(), '5.1' ) < 0 )
{
    print( "<h1>Unsupported PHP version " . phpversion() . "</h1>" );
    print( "<p>eZ Publish 4.x does not run with PHP 4.</p>".
           "<p>For more information about supported software please visit ".
           "<a href=\"http://ez.no/download/ez_publish\" >eZ Publish download page</a></p>" );
    exit;
}

// Set a default time zone if none is given to avoid "It is not safe to rely
// on the system's timezone settings" warnings. The time zone can be overriden
// in config.php or php.ini.
if ( !ini_get( "date.timezone" ) )
{
    date_default_timezone_set( "UTC" );
}

require 'autoload.php';

ignore_user_abort( true );

$scriptStartTime = microtime( true );
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
$siteBasics['policy-check-required'] =& $policyCheckRequired;
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

$GLOBALS['eZRedirection'] = false;

error_reporting ( E_ALL | E_STRICT );

// include standard libs
require_once( "lib/ezutils/classes/ezdebug.php" );
////include_once( "lib/ezutils/classes/ezini.php" );
////include_once( "lib/ezutils/classes/ezdebugsetting.php" );

$debugINI = eZINI::instance( 'debug.ini' );
eZDebugSetting::setDebugINI( $debugINI );


/*!
 Reads settings from site.ini and passes them to eZDebug.
*/
function eZUpdateDebugSettings()
{
    $ini = eZINI::instance();

    $settings = array();
    list( $settings['debug-enabled'], $settings['debug-by-ip'], $settings['log-only'], $settings['debug-by-user'], $settings['debug-ip-list'], $logList, $settings['debug-user-list'] ) =
        $ini->variableMulti( 'DebugSettings',
                             array( 'DebugOutput', 'DebugByIP', 'DebugLogOnly', 'DebugByUser', 'DebugIPList', 'AlwaysLog', 'DebugUserIDList' ),
                             array( 'enabled', 'enabled', 'disabled', 'enabled' ) );
    $logMap = array( 'notice' => eZDebug::LEVEL_NOTICE,
                     'warning' => eZDebug::LEVEL_WARNING,
                     'error' => eZDebug::LEVEL_ERROR,
                     'debug' => eZDebug::LEVEL_DEBUG,
                     'strict' => eZDebug::LEVEL_STRICT );
    $settings['always-log'] = array();
    foreach ( $logMap as $name => $level )
    {
        $settings['always-log'][$level] = in_array( $name, $logList );
    }
    eZDebug::updateSettings( $settings );
}

/*!
 Reads settings from i18n.ini and passes them to eZTextCodec.
*/
function eZUpdateTextCodecSettings()
{
    $ini = eZINI::instance( 'i18n.ini' );

    list( $i18nSettings['internal-charset'], $i18nSettings['http-charset'], $i18nSettings['mbstring-extension'] ) =
        $ini->variableMulti( 'CharacterSettings', array( 'Charset', 'HTTPCharset', 'MBStringExtension' ), array( false, false, 'enabled' ) );

    ////include_once( 'lib/ezi18n/classes/eztextcodec.php' );
    eZTextCodec::updateSettings( $i18nSettings );
}

// Initialize text codec settings
eZUpdateTextCodecSettings();

// Initialize debug settings.
eZUpdateDebugSettings();


// Set the different permissions/settings.
$ini = eZINI::instance();

// Set correct site timezone
$timezone = $ini->variable( "TimeZoneSettings", "TimeZone");
if ( $timezone )
{
    putenv( "TZ=$timezone" );
}


list( $iniFilePermission, $iniDirPermission ) =
    $ini->variableMulti( 'FileSettings', array( 'StorageFilePermissions', 'StorageDirPermissions' ) );

$iniVarDirectory = eZSys::cacheDirectory() ;

// OPTIMIZATION:
// Sets permission array as global variable, this avoids the eZCodePage include
$GLOBALS['EZCODEPAGEPERMISSIONS'] = array( 'file_permission' => octdec( $iniFilePermission ),
                                           'dir_permission'  => octdec( $iniDirPermission ),
                                           'var_directory'   => $iniVarDirectory );

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
                                      'text' => false,
                                      'identifier' => false ),
                               $parameters );
    $error = $parameters['error'];
    $text = $parameters['text'];
    $identifier = $parameters['identifier'];
    $warningList[] = array( 'error' => $error,
                            'text' => $text,
                            'identifier' => $identifier );
}

// Needed by the error handler, since the current directory is lost when
// the callback function eZExecutionUncleanShutdownHandler is called.
$GLOBALS['eZDocumentRoot'] = dirname( __FILE__ );
require_once( 'lib/ezutils/classes/ezexecution.php' );
/*
    see:
    - http://www.php.net/manual/en/function.session-set-save-handler.php
    - http://bugs.php.net/bug.php?id=33635
    - http://bugs.php.net/bug.php?id=33772
*/
register_shutdown_function( 'session_write_close' );

function eZDBCleanup()
{
    if ( class_exists( 'eZDB' )
         and eZDB::hasInstance() )
    {
        $db = eZDB::instance();
        $db->setIsSQLOutputEnabled( false );
    }
//     session_write_close();
}

function eZFatalError()
{
    //eZDebug::setHandleType( eZDebug::HANDLE_NONE );
    print( "<b>Fatal error</b>: eZ Publish did not finish its request<br/>" );
    print( "<p>The execution of eZ Publish was abruptly ended, the debug output is present below.</p>" );
    $templateResult = null;
    eZDisplayResult( $templateResult );
}

eZExecution::addCleanupHandler( 'eZDBCleanup' );
eZExecution::addFatalErrorHandler( 'eZFatalError' );

eZDebug::setScriptStart( $scriptStartTime );

// Enable this line to get eZINI debug output
// eZINI::setIsDebugEnabled( true );
// Enable this line to turn off ini caching
// eZINI::setIsCacheEnabled( false);

function eZDisplayDebug()
{
    $ini = eZINI::instance();

    if ( $ini->variable( 'DebugSettings', 'DebugOutput' ) != 'enabled' )
        return null;

    $type = $ini->variable( "DebugSettings", "Debug" );
    //eZDebug::setHandleType( eZDebug::HANDLE_NONE );
    if ( $type == "inline" or $type == "popup" )
    {
        $as_html = true;

        if ( $ini->variable( "DebugSettings", "DebugToolbar" ) == 'enabled' &&
             $ini->variable( "SiteAccessSettings", "CheckValidity" ) !== 'true' &&
             $as_html == true &&
             !$GLOBALS['eZRedirection'] )

        {
            require_once( 'kernel/common/template.php' );
            $tpl = templateInit();
            $result = "<tr><td>" . $tpl->fetch( 'design:setup/debug_toolbar.tpl' ) . "</td></tr>";
            eZDebug::appendTopReport( "Debug toolbar", $result );
        }

        ////include_once( 'kernel/common/eztemplatesstatisticsreporter.php' );
        eZDebug::appendBottomReport( 'Template Usage Statistics', eZTemplatesStatisticsReporter::generateStatistics( $as_html ) );

        return eZDebug::printReport( $type == "popup", $as_html, true );
    }
    return null;
}

/*!
  \private
*/
function eZDisplayResult( $templateResult )
{
    if ( $templateResult !== null )
    {
        $debugMarker = '<!--DEBUG_REPORT-->';
        $pos = strpos( $templateResult, $debugMarker );
        if ( $pos !== false )
        {
            $debugMarkerLength = strlen( $debugMarker );
            echo substr( $templateResult, 0, $pos );
            eZDisplayDebug();
            echo substr( $templateResult, $pos + $debugMarkerLength );
        }
        else
        {
            echo $templateResult, eZDisplayDebug();
        }
    }
    else
    {
        eZDisplayDebug();
    }
}

function fetchModule( $uri, $check, &$module, &$module_name, &$function_name, &$params )
{
    $module_name = $uri->element();
    if ( $check !== null and isset( $check["module"] ) )
        $module_name = $check["module"];

    // Try to fetch the module object
    $module = eZModule::exists( $module_name );
    if ( !( $module instanceof eZModule ) )
    {
        return false;
    }

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

////include_once( 'lib/ezi18n/classes/eztextcodec.php' );
$httpCharset = eZTextCodec::httpCharset();
////include_once( 'lib/ezlocale/classes/ezlocale.php' );
$ini = eZINI::instance();
if ( $ini->variable( 'RegionalSettings', 'Debug' ) == 'enabled' )
    eZLocale::setIsDebugEnabled( true );

////include_once( "lib/ezutils/classes/ezsys.php" );


eZDebug::setHandleType( eZDebug::HANDLE_FROM_PHP );

$GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );

// Initialize basic settings, such as vhless dirs and separators

eZSys::init( 'index.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) == 'true' );

eZDebug::addTimingPoint( "Script start" );

////include_once( "lib/ezutils/classes/ezuri.php" );

$uri = eZURI::instance( eZSys::requestURI() );
$GLOBALS['eZRequestedURI'] = $uri;
require_once "pre_check.php";

// Shall we start the eZ setup module?
//if ( $ini->variable( "SiteAccessSettings", "CheckValidity" ) == "true" )
//    //include_once( "lib/ezsetup/classes/ezsetup.php" );

//require_once 'kernel/error/errors.php';

/*
print( "<pre>" );
var_dump( $_SERVER );
print( "</pre>" );
print( "HTTP_HOST=" . eZSys::serverVariable( 'HTTP_HOST' ) . "<br/" );
*/

// include ezsession override implementation
//Needs to be added no class definition
require_once( "lib/ezutils/classes/ezsession.php" );


// Check for extension
//include_once( 'lib/ezutils/classes/ezextension.php' );
require_once( 'kernel/common/ezincludefunctions.php' );
eZExtension::activateExtensions( 'default' );
// Extension check end

require_once "access.php";

$access = accessType( $uri,
                      eZSys::hostname(),
                      eZSys::serverPort(),
                      eZSys::indexFile() );
$access = changeAccess( $access );
eZDebugSetting::writeDebug( 'kernel-siteaccess', $access, 'current siteaccess' );

// Check for activating Debug by user ID (Final checking. The first was in eZDebug::updateSettings())
eZDebug::checkDebugByUser();

// Check for siteaccess extension
eZExtension::activateExtensions( 'access' );
// Siteaccess extension check end

// Make sure template.ini reloads its cache incase
// siteaccess or extensions override it
$tplINI = eZINI::instance( 'template.ini' );
$tplINI->loadCache();

// Check if this should be run in a cronjob
// Need to be runned before eZHTTPTool::instance() because of eZSessionStart() which
// is called from eZHandlePreChecks() below.
$useCronjob = $ini->variable( 'Session', 'BasketCleanup' ) == 'cronjob';
if ( !$useCronjob )
{
    // Functions for session to make sure baskets are cleaned up
    function eZSessionBasketDestroy( $db, $key, $escapedKey )
    {
        ////include_once( 'kernel/classes/ezbasket.php' );
        $basket = eZBasket::fetch( $key );
        if ( is_object( $basket ) )
            $basket->remove();
    }

    function eZSessionBasketGarbageCollector( $db, $time )
    {
        ////include_once( 'kernel/classes/ezbasket.php' );
        eZBasket::cleanupExpired( $time );
    }

    function eZSessionBasketEmpty( $db )
    {
        ////include_once( 'kernel/classes/ezbasket.php' );
        eZBasket::cleanup();
    }

    // Fill in hooks
    $GLOBALS['eZSessionFunctions']['destroy_pre'][] = 'eZSessionBasketDestroy';
    $GLOBALS['eZSessionFunctions']['gc_pre'][] = 'eZSessionBasketGarbageCollector';
    $GLOBALS['eZSessionFunctions']['empty_pre'][] = 'eZSessionBasketEmpty';
}

// Initialize module loading
////include_once( "lib/ezutils/classes/ezmodule.php" );
$moduleRepositories = eZModule::activeModuleRepositories();
eZModule::setGlobalPathList( $moduleRepositories );

$check = eZHandlePreChecks( $siteBasics, $uri );

require_once( 'kernel/common/i18n.php' );

if ( $sessionRequired )
{
    $dbRequired = true;
}

$db = false;
if ( $dbRequired )
{
    ////include_once( 'lib/ezdb/classes/ezdb.php' );
    $db = eZDB::instance();
    if ( $sessionRequired and
         $db->isConnected() )
    {
        eZSessionStart();
    }

    if ( !$db->isConnected() )
        $warningList[] = array( 'error' => array( 'type' => 'kernel',
                                                  'number' => eZError::KERNEL_NO_DB_CONNECTION ),
                                'text' => 'No database connection could be made, the system might not behave properly.' );
}

// Initialize with locale settings
////include_once( "lib/ezlocale/classes/ezlocale.php" );
$locale = eZLocale::instance();
$languageCode = $locale->httpLocaleCode();
$phpLocale = trim( $ini->variable( 'RegionalSettings', 'SystemLocale' ) );
if ( $phpLocale != '' )
{
    setlocale( LC_ALL, explode( ',', $phpLocale ) );
}

// send header information
$headerList = array( 'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
                     'Last-Modified' => gmdate( 'D, d M Y H:i:s' ) . ' GMT',
                     'Cache-Control' => 'no-cache, must-revalidate',
                     'Pragma' => 'no-cache',
                     'X-Powered-By' => 'eZ Publish',
                     'Content-Type' => 'text/html; charset=' . $httpCharset,
                     'Served-by' => $_SERVER["SERVER_NAME"],
                     'Content-language' => $languageCode );

$site = array( 'title' => $ini->variable( 'SiteSettings', 'SiteName' ),
               'design' => $ini->variable( 'DesignSettings', 'SiteDesign' ),
               'http_equiv' => array( 'Content-Type' => 'text/html; charset=' . $httpCharset,
                                      'Content-language' => $languageCode ) );


////include_once( 'kernel/classes/ezhttpheader.php' );
$headerOverrideArray = eZHTTPHeader::headerOverrideArray( $uri );

$headerList = array_merge( $headerList, $headerOverrideArray );

foreach( $headerList as $key => $value )
{
    header( $key . ': ' . $value );
}

////include_once( 'kernel/classes/ezsection.php' );
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


// Start the module loop
while ( $moduleRunRequired )
{
    $objectHasMovedError = false;
    $objectHasMovedURI = false;
    $actualRequestedURI = $uri->uriString();

    // Extract user specified parameters
    $userParameters = $uri->userParameters();

    // Generate a URI which also includes the user parameters
    $completeRequestedURI = $uri->originalURIString();

    // Check for URL translation
    if ( $urlTranslatorAllowed and
         $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled' and
         !$uri->isEmpty() )
    {
        ////include_once( 'kernel/classes/ezurlaliasml.php' );
        $translateResult = eZURLAliasML::translate( $uri );

        if ( !is_string( $translateResult ) )
        {
            $useWildcardTranslation = $ini->variable( 'URLTranslator', 'WildcardTranslation' ) == 'enabled';
            if ( $useWildcardTranslation )
            {
                ////include_once( 'kernel/classes/ezurlwildcard.php' );
                $translateResult = eZURLWildcard::translate( $uri );
            }
        }

        // Check if the URL has moved
        if ( is_string( $translateResult ) )
        {
            $objectHasMovedURI = $translateResult;
            foreach ( $userParameters as $name => $value )
            {
                $objectHasMovedURI .= '/(' . $name . ')/' . $value;
            }

            $objectHasMovedError = true;
        }
    }

    if ( $uri->isEmpty() )
    {
        $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "IndexPage" ) );
        $moduleCheck = accessAllowed( $tmp_uri );
    }
    else
    {
        $moduleCheck = accessAllowed( $uri );
    }

    if ( !$moduleCheck['result'] )
    {
        if ( $ini->variable( "SiteSettings", "ErrorHandler" ) == "defaultpage" )
        {
            $defaultPage = $ini->variable( "SiteSettings", "DefaultPage" );
            $uri->setURIString( $defaultPage );
            $moduleCheck['result'] = true;
        }
    }

    ////include_once( "lib/ezutils/classes/ezhttptool.php" );
    $http = eZHTTPTool::instance();

    $displayMissingModule = false;
    $oldURI = $uri;

    if ( $uri->isEmpty() )
    {
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

    if ( !$displayMissingModule &&
         $moduleCheck['result'] &&
         $module instanceof eZModule )
    {
        // Run the module/function
        eZDebug::addTimingPoint( "Module start '" . $module->attribute( 'name' ) . "'" );

        $moduleAccessAllowed = true;
        $omitPolicyCheck = true;
        $runModuleView = true;

        $availableViewsInModule = $module->attribute( 'views' );
        if ( !isset( $availableViewsInModule[$function_name] ) )
        {
            $moduleResult = $module->handleError( eZError::KERNEL_MODULE_VIEW_NOT_FOUND, 'kernel' );
            $runModuleView = false;
            $policyCheckRequired = false;
            $omitPolicyCheck = true;
        }

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
            $currentUser = eZUser::currentUser();
            $siteAccessResult = $currentUser->hasAccessTo( 'user', 'login' );

            $hasAccessToSite = false;
            if ( $siteAccessResult[ 'accessWord' ] == 'limited' )
            {
                $policyChecked = false;
                foreach ( array_keys( $siteAccessResult['policies'] ) as $key )
                {
                    $policy = $siteAccessResult['policies'][$key];
                    if ( isset( $policy['SiteAccess'] ) )
                    {
                        $policyChecked = true;
                        $crc32AccessName = eZSys::ezcrc32( $access[ 'name' ] );
                        eZDebugSetting::writeDebug( 'kernel-siteaccess', $policy['SiteAccess'], $crc32AccessName );
                        if ( in_array( $crc32AccessName, $policy['SiteAccess'] ) )
                        {
                            $hasAccessToSite = true;
                            break;
                        }
                    }
                    if ( $hasAccessToSite )
                        break;
                }
                if ( !$policyChecked )
                    $hasAccessToSite = true;
            }
            else if ( $siteAccessResult[ 'accessWord' ] == 'yes' )
            {
                eZDebugSetting::writeDebug( 'kernel-siteaccess', "access is yes" );
                $hasAccessToSite = true;
            }
            else if ( $siteAccessResult['accessWord'] == 'no' )
            {
                $accessList = $siteAccessResult['accessList'];
            }

            if ( $hasAccessToSite )
            {
                $accessParams = array();
                $moduleAccessAllowed = $currentUser->hasAccessToView( $module, $function_name, $accessParams );
                if ( isset( $accessParams['accessList'] ) )
                {
                    $accessList = $accessParams['accessList'];
                }
            }
            else
            {
                eZDebugSetting::writeDebug( 'kernel-siteaccess', $access, 'not able to get access to siteaccess' );
                $moduleAccessAllowed = false;
                $requireUserLogin = ( $ini->variable( "SiteAccessSettings", "RequireUserLogin" ) == "true" );
                if ( $requireUserLogin )
                {
                    $module = eZModule::exists( 'user' );
                    if ( $module instanceof eZModule )
                    {
                        $moduleResult = $module->run( 'login', array(),
                                                       array( 'SiteAccessAllowed' => false,
                                                              'SiteAccessName' => $access['name'] ) );
                        $runModuleView = false;
                    }
                }
            }
        }

        $GLOBALS['eZRequestedModule'] = $module;

        if ( $runModuleView )
        {
            if ( $objectHasMovedError == true )
            {
                $moduleResult = $module->handleError( eZError::KERNEL_MOVED, 'kernel', array( 'new_location' => $objectHasMovedURI ) );
            }
            else if ( !$moduleAccessAllowed )
            {
                if ( isset( $availableViewsInModule[$function_name][ 'default_navigation_part' ] ) )
                {
                    $defaultNavigationPart = $availableViewsInModule[$function_name][ 'default_navigation_part' ];
                }

                if ( isset( $accessList ) )
                    $moduleResult = $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array( 'AccessList' => $accessList ) );
                else
                    $moduleResult = $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

                if ( isset( $defaultNavigationPart ) )
                {
                    $moduleResult['navigation_part'] = $defaultNavigationPart;
                    unset( $defaultNavigationPart );
                }
            }
            else
            {
                if ( !isset( $userParameters ) )
                {
                    $userParameters = false;
                }

                // Check if we should switch access mode (http/https) for this module view.
                ////include_once( 'kernel/classes/ezsslzone.php' );
                eZSSLZone::checkModuleView( $module->attribute( 'name' ), $function_name );

                $moduleResult = $module->run( $function_name, $params, false, $userParameters );

                if ( $module->exitStatus() == eZModule::STATUS_FAILED and
                     $moduleResult == null )
                    $moduleResult = $module->handleError( eZError::KERNEL_MODULE_VIEW_NOT_FOUND, 'kernel', array( 'module' => $module_name,
                                                                                                                   'view' => $function_name ) );
            }
        }
    }
    else if ( $moduleCheck['result'] )
    {
        eZDebug::writeError( "Undefined module: $module_name", "index" );
        $module = new eZModule( "", "", $module_name );
        $GLOBALS['eZRequestedModule'] = $module;
        $moduleResult = $module->handleError( eZError::KERNEL_MODULE_NOT_FOUND, 'kernel', array( 'module' => $module_name ) );
    }
    else
    {
        if ( $moduleCheck['view_checked'] )
            eZDebug::writeError( "View '" . $moduleCheck['view'] . "' in module '" . $moduleCheck['module'] . "' is disabled", "index" );
        else
            eZDebug::writeError( "Module '" . $moduleCheck['module'] . "' is disabled", "index" );
        $module = new eZModule( "", "", $moduleCheck['module'] );
        $GLOBALS['eZRequestedModule'] = $module;
        $moduleResult = $module->handleError( eZError::KERNEL_MODULE_DISABLED, 'kernel', array( 'check' => $moduleCheck ) );
    }
    $moduleRunRequired = false;
    if ( $module->exitStatus() == eZModule::STATUS_RERUN )
    {
        if ( isset( $moduleResult['rerun_uri'] ) )
        {
            $uri = eZURI::instance( $moduleResult['rerun_uri'] );
            $moduleRunRequired = true;
        }
        else
            eZDebug::writeError( 'No rerun URI specified, cannot continue', 'index.php' );
    }

    if ( is_array( $moduleResult ) )
    {
        if ( isset( $moduleResult["pagelayout"] ) )
        {
            $show_page_layout = $moduleResult["pagelayout"];
            $GLOBALS['eZCustomPageLayout'] = $moduleResult["pagelayout"];
        }
        if ( isset( $moduleResult["external_css"] ) )
            $use_external_css = $moduleResult["external_css"];
    }
}

if ( $module->exitStatus() == eZModule::STATUS_REDIRECT )
{
    $GLOBALS['eZRedirection'] = true;
    $ini = eZINI::instance();
    $automatic_redir = true;

    if ( $GLOBALS['eZDebugAllowed'] && ( $redirUri = $ini->variable( 'DebugSettings', 'DebugRedirection' ) ) != 'disabled' )
    {
        if ( $redirUri == "enabled" )
        {
            $automatic_redir = false;
        }
        else
        {
            $redirUris = $ini->variableArray( "DebugSettings", "DebugRedirection" );
            $uri = eZURI::instance( eZSys::requestURI() );
            $uri->toBeginning();
            foreach ( $redirUris as $redirUri )
            {
                $redirUri = new eZURI( $redirUri );
                if ( $redirUri->matchBase( $uri ) )
                {
                    $automatic_redir = false;
                    break;
                }
            }
        }
    }

    $redirectURI = eZSys::indexDir();

    $moduleRedirectUri = $module->redirectURI();
    $redirectStatus = $module->redirectStatus();
    $translatedModuleRedirectUri = $moduleRedirectUri;
    if ( $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
    {
        ////include_once( 'kernel/classes/ezurlaliasml.php' );
        if ( eZURLAliasML::translate( $translatedModuleRedirectUri, true ) )
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
        $leftSlash = false;
        $rightSlash = false;
        if ( strlen( $redirectURI ) > 0 and
             $redirectURI[strlen( $redirectURI ) - 1] == '/' )
            $leftSlash = true;
        if ( strlen( $moduleRedirectUri ) > 0 and
             $moduleRedirectUri[0] == '/' )
            $rightSlash = true;

        if ( !$leftSlash and !$rightSlash ) // Both are without a slash, so add one
            $moduleRedirectUri = '/' . $moduleRedirectUri;
        else if ( $leftSlash and $rightSlash ) // Both are with a slash, so we remove one
            $moduleRedirectUri = substr( $moduleRedirectUri, 1 );
        $redirectURI .= $moduleRedirectUri;
    }

    ////include_once( 'kernel/classes/ezstaticcache.php' );
    eZStaticCache::executeActions();

    eZDB::checkTransactionCounter();

    if ( $automatic_redir )
    {
        eZHTTPTool::redirect( $redirectURI, array(), $redirectStatus );
    }
    else
    {
        // Make sure any errors or warnings are reported
        if ( $ini->variable( 'DebugSettings', 'DisplayDebugWarnings' ) == 'enabled' )
        {
            if ( isset( $GLOBALS['eZDebugError'] ) and
                 $GLOBALS['eZDebugError'] )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'error',
                                                              'number' => 1,
                                                              'count' => $GLOBALS['eZDebugErrorCount'] ),
                                            'identifier' => 'ezdebug-first-error',
                                            'text' => ezi18n( 'index.php', 'Some errors occurred, see debug for more information.' ) ) );
            }

            if ( isset( $GLOBALS['eZDebugWarning'] ) and
                 $GLOBALS['eZDebugWarning'] )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'warning',
                                                              'number' => 1,
                                                              'count' => $GLOBALS['eZDebugWarningCount'] ),
                                            'identifier' => 'ezdebug-first-warning',
                                            'text' => ezi18n( 'index.php', 'Some general warnings occured, see debug for more information.' ) ) );
            }
        }
        require_once( "kernel/common/template.php" );
        $tpl = templateInit();
        if ( count( $warningList ) == 0 )
            $warningList = false;
        $tpl->setVariable( 'site', $site );
        $tpl->setVariable( 'warning_list', $warningList );
        $tpl->setVariable( 'redirect_uri', eZURI::encodeURL( $redirectURI ) );
        $templateResult = $tpl->fetch( 'design:redirect.tpl' );

        eZDebug::addTimingPoint( "End" );

        eZDisplayResult( $templateResult );
    }

    eZExecution::cleanExit();
}

// Store the last URI for access history for login redirection
// Only if database is connected and only if there was no error or no redirects happen
if ( is_object( $db ) and $db->isConnected() and
     $module->exitStatus() == eZModule::STATUS_OK )
{
    $currentURI = $completeRequestedURI;
    if ( strlen( $currentURI ) > 0 and $currentURI[0] != '/' )
        $currentURI = '/' . $currentURI;

    $lastAccessedURI = "";
    $lastAccessedViewURI = "";

    $http = eZHTTPTool::instance();

    // Fetched stored session variables
    if ( $http->hasSessionVariable( "LastAccessesURI" ) )
    {
        $lastAccessedViewURI = $http->sessionVariable( "LastAccessesURI" );
    }
    if ( $http->hasSessionVariable( "LastAccessedModifyingURI" ) )
    {
        $lastAccessedURI = $http->sessionVariable( "LastAccessedModifyingURI" );
    }

    // Update last accessed view page
    if ( $currentURI != $lastAccessedViewURI and
         !in_array( $module->uiContextName(), array( 'edit', 'administration', 'browse', 'authentication' ) ) )
    {
        $http->setSessionVariable( "LastAccessesURI", $currentURI );
    }

    // Update last accessed non-view page
    if ( $currentURI != $lastAccessedURI )
    {
        $http->setSessionVariable( "LastAccessedModifyingURI", $currentURI );
    }
}


eZDebug::addTimingPoint( "Module end '" . $module->attribute( 'name' ) . "'" );
if ( !is_array( $moduleResult ) )
{
    eZDebug::writeError( 'Module did not return proper result: ' . $module->attribute( 'name' ), 'index.php' );
    $moduleResult = array();
    $moduleResult['content'] = false;
}

if ( !isset( $moduleResult['ui_context'] ) )
{
    $moduleResult['ui_context'] = $module->uiContextName();
}
$moduleResult['ui_component'] = $module->uiComponentName();

$templateResult = null;

eZDebug::setUseExternalCSS( $use_external_css );
if ( $show_page_layout )
{
    require_once( "kernel/common/template.php" );
    $tpl = templateInit();
    if ( $tpl->hasVariable( 'node' ) )
        $tpl->unsetVariable( 'node' );

    if ( !isset( $moduleResult['path'] ) )
        $moduleResult['path'] = false;
    $moduleResult['uri'] = eZSys::requestURI();

    $tpl->setVariable( "module_result", $moduleResult );

    $meta = $ini->variable( 'SiteSettings', 'MetaDataArray' );

    if ( !isset( $meta['description'] ) )
    {
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
    }

    ////include_once( 'lib/version.php' );
    $site['uri'] = $oldURI;
    $site['redirect'] = false;
    $site['meta'] = $meta;
    $site['version'] = eZPublishSDK::version();
    $site['page_title'] = $module->title();

    $tpl->setVariable( "site", $site );

    ////include_once( 'lib/version.php' );
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
        if ( $ini->variable( 'DebugSettings', 'DisplayDebugWarnings' ) == 'enabled' )
        {
            // Make sure any errors or warnings are reported
            if ( isset( $GLOBALS['eZDebugError'] ) and
                 $GLOBALS['eZDebugError'] )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'error',
                                                              'number' => 1 ,
                                                              'count' => $GLOBALS['eZDebugErrorCount'] ),
                                            'identifier' => 'ezdebug-first-error',
                                            'text' => ezi18n( 'index.php', 'Some errors occurred, see debug for more information.' ) ) );
            }

            if ( isset( $GLOBALS['eZDebugWarning'] ) and
                 $GLOBALS['eZDebugWarning'] )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'warning',
                                                              'number' => 1,
                                                              'count' => $GLOBALS['eZDebugWarningCount'] ),
                                            'identifier' => 'ezdebug-first-warning',
                                            'text' => ezi18n( 'index.php', 'Some general warnings occured, see debug for more information.' ) ) );
            }
        }

        if ( $userObjectRequired )
        {
            // include user class
            // if( //include_once( "kernel/classes/datatypes/ezuser/ezuser.php" ) )
            $currentUser = eZUser::currentUser();

            $tpl->setVariable( "current_user", $currentUser );
            $tpl->setVariable( "anonymous_user_id", $ini->variable( 'UserSettings', 'AnonymousUserID' ) );
        }
        else
        {
            $tpl->setVariable( "current_user", false );
            $tpl->setVariable( "anonymous_user_id", false );
        }

//         ////include_once( "lib/ezutils/classes/ezexecutionstack.php" );
//         $execStack = eZExecutionStack::instance();
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
        if ( isset( $moduleResult['requested_uri_string'] ) )
        {
            $tpl->setVariable( 'requested_uri_string', $moduleResult['requested_uri_string'] );
        }
        else
        {
            $tpl->setVariable( 'requested_uri_string', $actualRequestedURI );
        }

        // Set UI context and component
        $tpl->setVariable( 'ui_context', $moduleResult['ui_context'] );
        $tpl->setVariable( 'ui_component', $moduleResult['ui_component'] );

        $templateResult = $tpl->fetch( $resource . $show_page_layout );
    }
}
else
{
    $templateResult = $moduleResult['content'];
}


eZDebug::addTimingPoint( "End" );

$out = ob_get_clean();
echo trim( $out );

eZDB::checkTransactionCounter();

eZDisplayResult( $templateResult );

eZExecution::cleanup();
eZExecution::setCleanExit();

?>