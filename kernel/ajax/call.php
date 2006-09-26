<?php

    include_once( 'lib/3dparty/xajax/xajax.inc.php' );
    include_once( 'lib/ezutils/classes/ezsys.php' );

    $xajax = new xajax( eZSys::indexDir() . '/ajax/call' );    
    
    include_once( 'lib/ezutils/classes/ezextension.php' );
    include_once( 'lib/ezutils/classes/ezini.php' );

    $ini =& eZINI::instance( 'xajax.ini' );

    if ( $ini->variable( 'AjaxSettings', 'DebugAlert' ) == 'enabled' )
    {
        $xajax->debugOn();
    }

    $functionFiles = $ini->variable( 'AjaxSettings', 'AvailableFunctions' );
    $extensionDirectories = $ini->variable( 'ExtensionSettings', 'ExtensionDirectories' );
    $ajaxAppDirs = $ini->variable( 'AjaxSettings', 'AjaxAppDirectories' );

    $directoryList = eZExtension::expandedPathList( $extensionDirectories, 'xajax_app' );
    $directoryList = array_merge( $ajaxAppDirs, $directoryList );

    if ( count( $functionFiles ) > 0 )
    {
        foreach ( $functionFiles as $function => $functionFile )
        {
            foreach ( $directoryList as $directory )
            {
                $handlerFile = $directory . '/' . strtolower( $functionFile ) . '.php';
                if ( file_exists( $handlerFile ) )
                {
                    $xajax->registerExternalFunction( $function, $handlerFile );
                }
            }
        }
    }

    include_once( 'lib/ezutils/classes/ezexecution.php' );
    eZExecution::setCleanExit( );

    $xajax->processRequests( );
?>
