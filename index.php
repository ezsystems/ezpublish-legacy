<?php
error_reporting ( E_ALL );

// include standard libs
include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );

// Enable this line to get eZINI debug output
// eZINI::setIsDebugEnabled( true );

function eZDisplayDebug()
{
    $ini =& eZINI::instance();

    $type = $ini->variable( "DebugSettings", "Debug" );
    if ( $type == "inline" or $type == "popup" )
        eZDebug::printReport( $type == "popup" );
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

// Remove url parameters
ereg( "([^?]+)", eZSys::serverVariable( 'REQUEST_URI' ), $regs );
eZSys::setServerVariable( 'REQUEST_URI', $regs[1] );
include_once( "lib/ezutils/classes/ezuri.php" );

$uri =& eZURI::instance( eZSys::serverVariable( 'REQUEST_URI' ) );
$GLOBALS['eZRequestedURI'] =& $uri;

include_once( "pre_check.php" );

// include ezsession override implementation
include( "lib/ezutils/classes/ezsession.php" );
ob_start();

include_once( "access.php" );

$access = accessType( $uri,
                      eZSys::serverVariable( 'HTTP_HOST' ),
                      eZSys::serverVariable( 'SERVER_PORT' ),
                      eZSys::indexFile() );
if ( $access !== null )
{
    changeAccess( $access );
}

session_start();

$use_external_css = true;
$show_page_layout = true;
$moduleRunRequired = true;

include_once( 'kernel/classes/ezsection.php' );
eZSection::initGlobalID();

while ( $moduleRunRequired )
{
    $nodePathString = $uri->elements();
    $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
    include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

    $node = eZContentObjectTreeNode::fetchByCRC(  $nodePathString  );

    if ( get_class( $node ) == 'ezcontentobjecttreenode' )
    {
        $newURI= '/content/view/full/' . $node->attribute( 'node_id' ) . '/';
        $uri = & eZURI::instance( $newURI );
    }

    if ( !accessAllowed( $uri ) )
    {
        $def_page = $ini->variable( "SiteSettings", "DefaultPage" );
        $uri->setURIString( $def_page );
    }

    include_once( "lib/ezutils/classes/ezhttptool.php" );
    $http =& eZHTTPTool::instance();
    $UserID =& $http->sessionVariable( "eZUserLoggedInID" );
    $currentUser =& eZUser::currentUser();

    $check = eZHandlePreChecks();

    include_once( "lib/ezutils/classes/ezmodule.php" );
    include_once( 'kernel/error/errors.php' );

    eZModule::setGlobalPathList( array( "kernel" ) );

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
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        eZDebug::addTimingPoint( "Module start '" . $module->attribute( 'name' ) . "'" );

        $currentUser =& eZUser::currentUser();

        $aviableViewsInModule = $module->attribute( 'views' );
        $runningFunctions = false;
        if ( isset( $aviableViewsInModule[$function_name][ 'functions' ] ) )
            $runningFunctions = $aviableViewsInModule[$function_name][ 'functions' ];
        $accessResult = $currentUser->hasAccessTo( $module->attribute( 'name' ), $runningFunctions[0] );

        if ( $accessResult['accessWord'] == 'limited' )
        {
            $params['Limitation'] =& $accessResult['policies'];
        }

        $GLOBALS['eZRequestedModule'] =& $module;

        if ( $accessResult['accessWord'] == 'no' &&
//             $module->attribute( 'name' ) != 'role' &&
//             $module->attribute( 'name' ) != 'error' &&
             $module->attribute( 'name' ) != 'user' 
//             !( $module->attribute( 'name' ) == 'content'  &&  $function_name == 'browse' )
             )
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
    $uri =& eZURI::instance( eZSys::serverVariable( 'REQUEST_URI' ) );

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

    if ( $automatic_redir )
    {
        $redirectURI = eZSys::indexDir();
        $redirectURI .= $module->redirectURI();

        header( "Location: " . $redirectURI );
    }
    else
    {
        $redirectURI = eZSys::indexDir();
        $redirectURI .= $module->redirectURI();
        include_once( "kernel/common/template.php" );
        $tpl =& templateInit();
        $tpl->setVariable( 'redirect_uri', $redirectURI );
        $tpl->display( 'design:redirect.tpl' );

        eZDebug::addTimingPoint( "End" );

        eZDisplayDebug();
    }

    exit;
}

eZDebug::addTimingPoint( "Module end '" . $module->attribute( 'name' ) . "'" );
if ( !is_array( $moduleResult ) )
{
    eZDebug::writeError( 'Module did not return proper result: ' . $module->attribute( 'name' ), 'index.php' );
    $moduleResult = array();
    $moduleResult['content'] = false;
}

eZDebug::setUseExternalCSS( $use_external_css );
if ( $show_page_layout )
{
    include_once( "kernel/common/template.php" );
    $tpl =& templateInit();
    if ( !isset( $moduleResult['path'] ) )
        $moduleResult['path'] = false;
    $tpl->setVariable( "module_result", $moduleResult );

    $site = array(
        "title" => "eZ publish 3.0",
        "page_title" => $module->title(),
        "redirect" => false,
        "design" => "standard",
        "meta" => array(
            "author" => "eZ systems",
            "copyright" => "eZ systems",
            "description" => "Content Management System",
            "keywords" => "cms, ez, publish, cool" ) );

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
        // include user class
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

        $currentUser =& eZUser::currentUser();
        $tpl->setVariable( "current_user", $currentUser );
        $tpl->setVariable( "anonymous_user_id", $ini->variable( 'UserSettings', 'AnonymousUserID' ) );

        include_once( "lib/ezutils/classes/ezexecutionstack.php" );
        $execStack =& eZExecutionStack::instance();
        $tpl->setVariable( "execution_entries", $execStack->entries() );

        $tpl->setVariable( "access_type", $access );

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

        /*
        /// HiO special menu code tmp
        eZDebug::writeWarning( "Temporary HiO specific code, remove", "index.php" );

        $level = 0;
        $done = false;
        $i = 0;
        $pathArray = array();
        $tmpModulePath = $moduleResult['path'];
        $tmpModulePath[count($tmpModulePath)-1]['url'] = eZSys::serverVariable( 'REQUEST_URI' );
        $offset = 0;
        if ( $moduleResult['section_id'] == 2 )
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
                                                                          'ClassFilterArray' => array( 1,6,20,25 )
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
                        if ( $value->attribute( 'enumvalue' ) <> 2 )
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
                    if ( !in_array( $tmpNodeID, array( 20, 258, 64, 49,22 ) ) and ( $addToMenu == true ) )
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
                                                                              'ClassFilterArray' => array( 1,6,20,25 )
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
                        if ( $value->attribute( 'enumvalue' ) <> 2 )
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
                    if ( !in_array( $tmpNodeID, array( 20, 258, 64,22 ) ) )
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
        */

        $tpl->display( $resource . $show_page_layout );
    }
}
else
{
    print( $moduleResult['content'] );
}

eZDebug::addTimingPoint( "End" );

eZDisplayDebug();

ob_end_flush();
?>
