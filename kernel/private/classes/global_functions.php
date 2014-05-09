<?php
/**
 * File containing the global eZ Publish 4 functions
 *
 * @deprecated Since 5.0
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

/**
 * @deprecated Since 5.0
 * @return null
 */
function eZDisplayDebug()
{
    $ini = eZINI::instance();

    if ( $ini->variable( 'DebugSettings', 'DebugOutput' ) != 'enabled' )
        return null;

    $scriptStopTime = microtime( true );

    $type = $ini->variable( "DebugSettings", "Debug" );
    //eZDebug::setHandleType( eZDebug::HANDLE_NONE );
    if ( $type == "inline" or $type == "popup" )
    {
        $asHtml = true;

        if ( $ini->variable( "DebugSettings", "DebugToolbar" ) == 'enabled' &&
             $ini->variable( "SiteAccessSettings", "CheckValidity" ) !== 'true' &&
             $asHtml == true &&
             !$GLOBALS['eZRedirection'] )

        {
            $tpl = eZTemplate::factory();
            $result = "<tr><td colspan='2'>" . $tpl->fetch( 'design:setup/debug_toolbar.tpl' ) . "</td></tr>";
            eZDebug::appendTopReport( "Debug toolbar", $result );
        }

        eZDebug::appendBottomReport( 'Template Usage Statistics', eZTemplatesStatisticsReporter::generateStatistics( $asHtml ) );

        eZDebug::setScriptStop( $scriptStopTime );
        return eZDebug::printReport( $type == "popup", $asHtml, true, false, true,
            true, $ini->variable( "DebugSettings", "DisplayIncludedFiles" ) == 'enabled' );
    }
    return null;
}

/**
 * @deprecated Since 5.0
 * @param string|null $templateResult
 */
function eZDisplayResult( $templateResult )
{
    ob_start();
    if ( $templateResult !== null )
    {
        $templateResult = ezpEvent::getInstance()->filter( 'response/preoutput', $templateResult );
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
    echo ezpEvent::getInstance()->filter( 'response/output', ob_get_clean() );
}

/**
 * Reads settings from site.ini and passes them to eZDebug
 *
 * @deprecated Since 5.0
 */
function eZUpdateDebugSettings()
{
    $settings = array();
    list( $settings['debug-enabled'], $settings['debug-by-ip'], $settings['log-only'], $settings['debug-by-user'], $settings['debug-ip-list'], $logList, $settings['debug-user-list'] ) =
        eZINI::instance()->variableMulti(
            'DebugSettings',
            array( 'DebugOutput', 'DebugByIP', 'DebugLogOnly', 'DebugByUser', 'DebugIPList', 'AlwaysLog', 'DebugUserIDList' ),
            array( 'enabled', 'enabled', 'disabled', 'enabled' )
        );
    $settings['always-log'] = array();
    foreach (
        array(
            'notice' => eZDebug::LEVEL_NOTICE,
            'warning' => eZDebug::LEVEL_WARNING,
            'error' => eZDebug::LEVEL_ERROR,
            'debug' => eZDebug::LEVEL_DEBUG,
            'strict' => eZDebug::LEVEL_STRICT
        ) as $name => $level )
    {
        $settings['always-log'][$level] = $logList !== null && in_array( $name, $logList );
    }
    eZDebug::updateSettings( $settings );
}

/**
 * Appends a new warning item to the warning list.
 * a $parameters must contain an error and text key.
 *
 * @deprecated Since 5.0
 * @param array $parameters
 */
function eZAppendWarningItem( $parameters = array() )
{
    global $warningList;
    $parameters += array(
        'error' => false,
        'text' => false,
        'identifier' => false
    );
    $warningList[] = array(
        'error' => $parameters['error'],
        'text' => $parameters['text'],
        'identifier' => $parameters['identifier'],
    );
}

/**
 * @deprecated Since 5.0
 * @param \eZURI $uri
 * @param null|array $check
 * @param null|\eZModule $module ByRef, will be set to a eZModule instace based on $moduleName
 * @param string $moduleName
 * @param string $functionName
 * @param array $params
 * @return bool
 */
function fetchModule( eZURI $uri, $check, &$module, &$moduleName, &$functionName, &$params )
{
    $moduleName = $uri->element();
    if ( $check !== null && isset( $check["module"] ) )
        $moduleName = $check["module"];

    // Try to fetch the module object
    $module = eZModule::exists( $moduleName );
    if ( !( $module instanceof eZModule ) )
    {
        return false;
    }

    $uri->increase();
    $functionName = "";
    if ( !$module->singleFunction() )
    {
        $functionName = $uri->element();
        $uri->increase();
    }
    // Override it if required
    if ( $check !== null && isset( $check["function"] ) )
        $functionName = $check["function"];

    $params = $uri->elements( false );
    return true;
}
