<?php
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$scriptStartTime = microtime();

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

$useHIOCode = false;

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

$warningList = array();

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
        return eZDebug::printReport( $type == "popup", true, true );
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

// send header information
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . 'GMT' );
header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Pragma: no-cache' );
header( 'X-Powered-By: eZ publish' );

include_once( 'lib/ezi18n/classes/eztextcodec.php' );
$httpCharset = eZTextCodec::httpCharset();
include_once( 'lib/ezlocale/classes/ezlocale.php' );
$ini =& eZINI::instance();
if ( $ini->variable( 'RegionalSettings', 'Debug' ) == 'enabled' )
    eZLocale::setIsDebugEnabled( true );
$locale =& eZLocale::instance();
$languageCode =& $locale->httpLocaleCode();

header( 'Content-Type: text/html; charset=' . $httpCharset );
header( 'Content-language: ' . $languageCode );

// print( 'Content-Type: text/html; charset=' . $httpCharset . "<br/>" );
// print( 'Content-language: ' . $languageCode . "<br/>" );

include_once( "lib/ezutils/classes/ezsys.php" );

eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

$GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );

// Initialize basic settings, such as vhless dirs and separators
eZSys::init( 'index.php' );

$ini =& eZINI::instance();

eZSys::initIni( $ini );

// Initialize with locale settings
include_once( "lib/ezlocale/classes/ezlocale.php" );

$locale =& eZLocale::instance();
// $locale->setLanguage( $language );
// $locale->initPHP( "UTF8" );

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
include( "lib/ezutils/classes/ezsession.php" );
ob_start();

include_once( "access.php" );

$access = accessType( $uri,
                      eZSys::hostname(),
                      eZSys::serverPort(),
                      eZSys::indexFile() );
if ( $access !== null )
{
    changeAccess( $access );
}

$check = eZHandlePreChecks( $siteBasics );

if ( $sessionRequired )
    $dbRequired = true;

if ( $dbRequired or
     $sessionRequired )
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

$globalModuleRepositories = $ini->variableArray( 'ModuleSettings', 'ModuleRepositories' );
$moduleRepositories = array_merge( $moduleRepositories, $globalModuleRepositories );
eZModule::setGlobalPathList( $moduleRepositories );


// Start the module loop
while ( $moduleRunRequired )
{
    if ( $urlTranslatorAllowed and
         $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
    {
        include_once( 'kernel/classes/ezurltranslator.php' );
        $urlInstance =& eZURLTranslator::instance();
        $newURI =& $urlInstance->translate( $uri );
        if ( $newURI )
            $uri = $newURI;
    }

    if ( !accessAllowed( $uri ) )
    {
        $def_page = $ini->variable( "SiteSettings", "DefaultPage" );
        $uri->setURIString( $def_page );
    }

    include_once( "lib/ezutils/classes/ezhttptool.php" );
    $http =& eZHTTPTool::instance();
/*    $UserID =& $http->sessionVariable( "eZUserLoggedInID" );
    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
    $currentUser =& eZUser::currentUser();
*/

    $displayMissingModule = false;
    if ( $uri->isEmpty() )
    {
        $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "IndexPage" ) );
//         $check = null;
        if ( !fetchModule( $tmp_uri, $check, $module, $module_name, $function_name, $params ) )
            $displayMissingModule = true;
    }
    else if ( !fetchModule( $uri, $check, $module, $module_name, $function_name, $params ) )
    {
        if ( $ini->variable( "SiteSettings", "ErrorHandler" ) == "defaultpage" )
        {
            $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "DefaultPage" ) );
//             $check = null;
            if ( !fetchModule( $tmp_uri, $check, $module, $module_name, $function_name, $params ) )
                $displayMissingModule = true;
        }
        else
            $displayMissingModule = true;
    }

    if ( !$displayMissingModule and get_class( $module ) == "ezmodule" )
    {
        // Run the module/function
        eZDebug::addTimingPoint( "Module start '" . $module->attribute( 'name' ) . "'" );

        $moduleAccessAllowed = true;
        $omitPolicyCheck = true;
        if ( $policyCheckRequired )
        {
            $omitPolicyCheck = false;
            $moduleName = $module->attribute( 'name');
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

            $aviableViewsInModule = $module->attribute( 'views' );
            $runningFunctions = false;
            if ( isset( $aviableViewsInModule[$function_name][ 'functions' ] ) )
                $runningFunctions = $aviableViewsInModule[$function_name][ 'functions' ];
            $accessResult = $currentUser->hasAccessTo( $module->attribute( 'name' ), $runningFunctions[0] );

            if ( $accessResult['accessWord'] == 'limited' )
            {
                $params['Limitation'] =& $accessResult['policies'];
                $GLOBALS['ezpolicylimitation_list'] =& $params['Limitation'];
            }

            if ( $accessResult['accessWord'] == 'no' )
                $moduleAccessAllowed = false;
        }

        $GLOBALS['eZRequestedModule'] =& $module;

        if ( !$moduleAccessAllowed )
        {
            $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
        }
        else
        {
            $moduleResult =& $module->run( $function_name, $params );
            if ( $module->exitStatus() == EZ_MODULE_STATUS_FAILED and
                 $moduleResult == null )
                $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_MODULE_VIEW_NOT_FOUND, 'kernel', array( 'module' => $module_name,
                                                                                                               'view' => $function_name ) );
        }
    }
    else
    {
        eZDebug::writeError( "Undefined module: $module_name", "index" );
        $module = new eZModule( "", "", $module_name );
        $GLOBALS['eZRequestedModule'] =& $module;
        $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_MODULE_NOT_FOUND, 'kernel', array( 'module' => $module_name ) );
//         $moduleResult = array();
//         $moduleResult["pagelayout"] = "undefinedmodule.tpl";
//         $tpl_vars = array( "module" => array( "name" => $module_name ) );
    }
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
    if ( $automatic_redir )
    {
        $redirectURI .= $module->redirectURI();

        header( "Location: " . $redirectURI );
    }
    else
    {
        $redirectURI .= $module->redirectURI();
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

    $meta = array( "author" => "eZ systems",
                   "copyright" => "eZ systems",
                   "description" => "Content Management System",
                   "keywords" => "cms, ez, publish, cool" );

    $http_equiv = array( 'Content-Type' => 'text/html; charset=' . $httpCharset,
                         'Content-language' => $languageCode );
    $site = array(
        "title" => "eZ publish 3.0",
        "page_title" => $module->title(),
        "redirect" => false,
        "design" => "standard",
        "http_equiv" => $http_equiv,
        "meta" => $meta );

    $tpl->setVariable( "site", $site );
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

        if ( $useHIOCode )
        {
             /// HiO special menu code tmp
        eZDebug::writeWarning( "Temporary HiO specific code, remove", "index.php" );

        $level = 0;
        $done = false;
        $i = 0;
        $pathArray = array();
        $tmpModulePath = $moduleResult['path'];
        $tmpModulePath[count($tmpModulePath)-1]['url'] = eZSys::requestURI();
        $offset = 0;
        $sessionIDs = array ( 2,  // Sykepleierutdanning
                              4,  // Estetiske fag
                              5,  // Helsefag
                              6,  // Ingeniørutdanning
                              7,  // Journalistikk, bibliotek- og informasjonsfag
                              8,  // Lærerutdanning
                              9,  // Økonomi, kommunal- og sosialfag
                              10, // Profesjonsstudier
                              11, // Internasjonalt og flerkulturelt arbeid
                              12, // Kompetanseutvikling i den flerkulturelle skolen
                              13, // Voldsofferarbeid
                              14, // Pedagogisk utviklingssenter
                              15, // Høgskolebiblioteket
                              16  // Administrasjonen
                              );
        if ( in_array( $moduleResult['section_id'], $sessionIDs ) )
            $offset = 2;
        while ( !$done )
        {

            // get node id
            $elements = explode( "/", $tmpModulePath[$i+$offset]['url'] );
            $nodeID = $elements[4];

            $excludeNode = false;
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( $node )
            {
                $obj = $node->attribute('object');
                $dataMap = $obj->dataMap();
                if ( $obj->attribute( 'contentclass_id' ) == 1 )
                {
                    if ( get_class( $dataMap['liste'] ) == 'ezcontentobjectattribute' )
                        if ( $dataMap['liste']->attribute('data_int' ) == 1 )
                        {
                            $excludeNode = true;
                        }
                }
            }

            if ( $elements[1] == 'content' and $elements[2] == 'view' and is_numeric( $nodeID ) and $excludeNode == false )
            {

                $menuChildren =& eZContentObjectTreeNode::subTree( array( 'Depth' => 1,
                                                                          'Offset' => 0,
                                                                          'SortBy' => array( array('priority') ),
                                                                          'ClassFilterType' => 'include',
                                                                          'ClassFilterArray' => array( 6, 25 )
                                                                          ),
                                                                   $nodeID );

                $tmpPathArray = array();
                foreach ( $menuChildren as $child )
                {
                    $name = $child->attribute( 'name' );

                    $strLimit = 17;
                    if ( strlen( $name ) > $strLimit )
                    {
                        $name = substr( $name, 0, $strLimit ) . "...";
                    }
                    $tmpNodeID = $child->attribute( 'node_id' );
                    $tmpObj = $child->attribute( 'object' );
                    $className = $tmpObj->attribute( 'class_name' );

                    $addToMenu = true;
                    if ( $className == "Vevside" )
                    {
                        $map = $tmpObj->attribute( "data_map" );
                        $enum = $map['type']->content();
                        $values = $enum->attribute( "enumobject_list" );
                        $value = $values[0];
                        if ( get_class( $value ) == 'ezenumobjectvalue' and  $value->attribute( 'enumvalue' ) <> 2 )
                            $addToMenu = false;
                    }

                    if ( $className == "Link" )
                    {
                        $map = $tmpObj->attribute( "data_map" );
                        $tmpURL = $map['url']->content();
                        $url = "$tmpURL";
                    }
                    else
                        $url = "/content/view/full/$tmpNodeID/";
                    if ( $addToMenu == true )
                    $tmpPathArray[] = array( 'id' => $tmpNodeID,
                                             'level' => $i,
                                             'url' => $url,
                                             'text' => $name );
                }

                // find insert pos
                $j = 0;
                $insertPos = 0;
                foreach ( $pathArray as $path )
                {
                    if ( $path['id'] == $nodeID )
                        $insertPos = $j;
                    $j++;
                }
                $restArray = array_splice( $pathArray, $insertPos + 1 );

                $pathArray = array_merge( $pathArray, $tmpPathArray );
                $pathArray = array_merge( $pathArray, $restArray  );
            }
            else
            {
                if ( $level == 0 )
                {
                    $menuChildren =& eZContentObjectTreeNode::subTree( array( 'Depth' => 1,
                                                                              'Offset' => 0,
                                                                              'SortBy' => array( array('priority') ),
                                                                              'ClassFilterType' => 'include',
                                                                              'ClassFilterArray' => array( 6,25 )
                                                                              ),
                                                                       2 );
                    $pathArray = array();
                foreach ( $menuChildren as $child )
                {
                    $name = $child->attribute( 'name' );

                    $strLimit = 17;
                    if ( strlen( $name ) > $strLimit )
                    {
                        $name = substr( $name, 0, $strLimit ) . "...";
                    }
                    $tmpNodeID = $child->attribute( 'node_id' );
                    $tmpObj = $child->attribute( 'object' );
                    $className = $tmpObj->attribute( 'class_name' );

                    $addToMenu = true;
                    if ( $className == "Vevside" )
                    {
                        $map = $tmpObj->attribute( "data_map" );
                        $enum = $map['type']->content();
                        $values = $enum->attribute( "enumobject_list" );
                        $value = $values[0];
                        if ( get_class( $value ) == 'ezenumobjectvalue' and  $value->attribute( 'enumvalue' ) <> 2 )
                            $addToMenu = false;
                    }

                    if ( $className == "Link" )
                    {
                        $map = $tmpObj->attribute( "data_map" );
                        $tmpURL = $map['url']->content();
                        $url = "$tmpURL";
                    }
                    else
                        $url = "/content/view/full/$tmpNodeID/";
                    if ( $addToMenu == true  )
                    $pathArray[] = array( 'id' => $tmpNodeID,
                                          'level' => $i,
                                          'url' => $url,
                                          'text' => $name );
                }

                }
                $done = true;
            }
            ++$level;
            ++$i;
        }

//        foreach ( $pathArray as $path )
//        {
//          print( $path['level'] . " " . $path['text'] . "<br>");
//      }
        $tpl->setVariable( 'menuitems', $pathArray );
        /// end HiO code
        }

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

?>
