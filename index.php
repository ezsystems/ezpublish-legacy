<?php
error_reporting ( E_ALL );

// include standard libs
include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );

function eZDisplayDebug()
{
    $ini =& eZINI::instance();

    $type = $ini->variable( "DebugSettings", "Debug" );
    if ( $type == "inline" or $type == "popup" )
        eZDebug::printReport( $type == "popup" );
}

function fetchModule( &$uri, &$check, $module_path_list, &$module, &$module_name, &$function_name, &$params )
{
    eZDebug::writeNotice( $uri, "in fetch module" );
    $module_name = $uri->element();
    eZDebug::writeNotice( $module_name, "in fetch module" );
    if ( $check !== null and isset( $check["module"] ) )
        $module_name = $check["module"];

    // Try to fetch the module object
    $module = eZModule::exists( $module_path_list, $module_name );
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
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
header( "Content-Type: text/html; charset=utf8" );
header( "X-Powered-By: eZ publish" );

include_once( "lib/ezutils/classes/ezsys.php" );

eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

// Initialize basic settings, such as vhless dirs and separators
eZSys::init( $SCRIPT_FILENAME, $PHP_SELF, $DOCUMENT_ROOT,
             $SCRIPT_NAME, $REQUEST_URI, "index.php" );

$ini =& eZINI::instance();

eZSys::initIni( $ini );

// Initialize with locale settings
include_once( "lib/ezlocale/classes/ezlocale.php" );

$locale =& eZLocale::instance();
// $locale->setLanguage( $language );
// $locale->initPHP( "UTF8" );

eZDebug::addTimingPoint( "Script start" );

// Remove url parameters
ereg( "([^?]+)", $REQUEST_URI, $regs );
$REQUEST_URI = $regs[1];
include_once( "lib/ezutils/classes/ezuri.php" );

$uri =& eZURI::instance( $REQUEST_URI );


$nodePathString = $uri->elements();
eZDebug::writeNotice( $nodePathString, 'nodePathString' );
$nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$node = eZContentObjectTreeNode::fetchByCRC( crc32( $nodePathString ) );
eZDebug::writeNotice( $nodePathString, 'nodePathString' );

if ( get_class( $node ) == 'ezcontentobjecttreenode' )
{
    $newURI= '/content/view/full/' . $node->attribute( 'node_id' ) . '/';
    $uri = & eZURI::instance( $newURI );
    eZDebug::writeNotice( $uri, 'Uri IN' );

}



// eZDebug::addTimingPoint( "Access validation" );

include_once( "access.php" );

$access = accessType( $uri,
                      $GLOBALS["HTTP_SERVER_VARS"]["HTTP_HOST"],
                      $GLOBALS["HTTP_SERVER_VARS"]["SERVER_PORT"],
                      eZSys::indexFile() );
if ( $access !== null )
{
    changeAccess( $access );
}

if ( !accessAllowed( $uri ) )
{
    $def_page = $ini->variable( "SiteSettings", "DefaultPage" );
    $uri->setURIString( $def_page );
}

// eZDebug::addTimingPoint( "Access done" );

include_once( "lib/ezutils/classes/ezhttptool.php" );
$http =& eZHTTPTool::instance();

$module_path_list = array( "kernel" );

// $UserID =& $http->sessionVariable( "eZUserLoggedInID" );

// eZDebug::addTimingPoint( "Pre checks" );

include_once( "pre_check.php" );

// include ezsession override implementation
include( "lib/ezutils/classes/ezsession.php" );
ob_start();
session_start();

$check = eZHandlePreChecks();

// eZDebug::addTimingPoint( "Pre checks done" );

include_once( "lib/ezutils/classes/ezmodule.php" );


// Initialize module name and override it if required
// $module_name = $uri->element();
// if ( $check !== null and isset( $check["module"] ) )
//     $module_name = $check["module"];

// Try to fetch the module object
// $module =& eZModule::exists( $module_path_list, $module_name );


$displayMissingModule = false;
if ( $uri->isEmpty() )
{
    $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "IndexPage" ) );
    $check = null;
    if ( !fetchModule( $tmp_uri, $check, $module_path_list, $module, $module_name, $function_name, $params ) )
        $displayMissingModule = true;
}
else if ( !fetchModule( $uri, $check, $module_path_list, $module, $module_name, $function_name, $params ) )
{
    if ( $ini->variable( "SiteSettings", "ErrorHandler" ) == "defaultpage" )
    {
        $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "DefaultPage" ) );
        $check = null;
        if ( !fetchModule( $tmp_uri, $check, $module_path_list, $module, $module_name, $function_name, $params ) )
            $displayMissingModule = true;
    }
    else
        $displayMissingModule = true;
}

$use_external_css = true;
$show_page_layout = true;

if ( !$displayMissingModule and get_class( $module ) == "ezmodule" )
{
    // Run the module/function
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    eZDebug::addTimingPoint( "Module start" );

    $currentUser =& eZUser::currentUser();
    $aviableViewsInModule = $module->attribute( 'views' );
    $runningFunctions = $aviableViewsInModule[ $function_name ][ 'functions' ];
    $accessResult = $currentUser->hasAccessTo( $module->attribute( 'name' ), $runningFunctions[0] );

    print( 'accessResult:'. $accessResult['accessWord'] . '<br/>' );
    flush();
    eZDebug::writeNotice( $params, 'module parameters' );
    if ( $accessResult['accessWord'] == 'limited' )
    {
        $params['Limitation'] =& $accessResult['policies'];
    }

    if ( $accessResult['accessWord'] == 'no' &&
         $module->attribute( 'name' ) != 'role' &&
         $module->attribute( 'name' ) != 'error' &&
         $module->attribute( 'name' ) != 'user' &&
         !( $module->attribute( 'name' ) == 'content'  &&  $function_name == 'browse' )
         )
    {
        $module->redirectTo( '/error/403' );
    }
    else
    {
        eZDebug::writeNotice( $params, 'module parameters' );

        $result =& $module->run( $function_name, $params );
    }
}
else
{
    eZDebug::writeError( "Undefined module: $module_name", "index" );
    $module = new eZModule( "", "", $module_name );
    $result = array();
    $result["pagelayout"] = "undefinedmodule.tpl";
    $tpl_vars = array( "module" => array( "name" => $module_name ) );
}


if ( $module->exitStatus() == EZ_MODULE_STATUS_REDIRECT )
{
    $ini =& eZINI::instance();
    $uri =& eZURI::instance( $REQUEST_URI );

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
        header( "Location: " . $module->redirectURI() );
    }
    else
    {
        print( "<form action=\"" . $module->redirectURI() . "\" method=\"post\" name=\"Redirect\">\n" );
        print( "Redirecting to: <b>" . $module->redirectURI() . "</b><br/>\n");
        print( "<input class=\"stdbutton\" type=\"submit\" Name=\"RedirectButton\" value=\"Redirect\"/>\n" );
        print( "</form>\n" );
        eZDebug::addTimingPoint( "End" );

        eZDisplayDebug();
    }

    exit;
}

eZDebug::addTimingPoint( "Module end" );
if ( is_array( $result ) )
{
    $content =& $result["content"];
    if ( isset( $result["pagelayout"] ) )
        $show_page_layout =& $result["pagelayout"];
    if ( isset( $result["external_css"] ) )
        $use_external_css =& $result["external_css"];
}
else
{
    $content =& $result;
}


eZDebug::setUseExternalCSS( $use_external_css );
if ( $show_page_layout )
{
    include_once( "kernel/common/template.php" );
    $tpl =& templateInit();
    if ( $module->exitStatus() == EZ_MODULE_STATUS_OK )
    {
        $tpl->setVariable( "content", $content );
    }
    else if ( $module->exitStatus() == EZ_MODULE_STATUS_FAILED )
    {
        $tpl->setVariable( "content",
                           "Module $module_name failed." );
    }

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
    if ( $module->exitStatus() == EZ_MODULE_STATUS_SHOW_LOGIN_PAGE )
    {
        $show_page_layout = "loginpagelayout.tpl";
    }

    if ( $show_page_layout )
    {
        if ( $ini->variable( "SiteAccessSettings", "RequireUserLogin" ) == "true" )
        {
            // include user class
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
//         include_once( "kernel/classes/ezusertype.php" );
//         $currentUserID = $http->sessionVariable( "eZUserLoggedInID" );

            $currentUser =& eZUser::currentUser();
            $tpl->setVariable( "current_user", $currentUser );
        }

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
        $tpl->display( $resource . $show_page_layout );
    }
}
else
{
    print( $content );
}

eZDebug::addTimingPoint( "End" );

eZDisplayDebug();

ob_end_flush();
?>
