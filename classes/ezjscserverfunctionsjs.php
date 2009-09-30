<?php
//
// Definition of ezjscServerFunctionsJs class
//
// Created on: <16-Jun-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 2009 eZ Systems AS
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

/*
 * Some ezjscServerFunctions
 */

class ezjscServerFunctionsJs extends ezjscServerFunctions
{
    /**
     * Example function for returning time stamp + first function argument if present
     *
     * @param array $args
     * @return int|string
     */
    public static function time( $args )
    {
        if ( $args && isset( $args[0] ) )
            return $args[0]. '_' . time();
        return time();
    }

    /**
     * Figgures out where to load yui2 files from and prepends them to $packerFiles
     *
     * @param array $args
     * @param array $packerFiles ByRef list of files to pack (by ezjscPacker)
     * @return string Empty string
     */
    public static function yui2( $args, &$packerFiles )
    {
        $ezjscoreIni = eZINI::instance( 'ezjscore.ini' );
        if ( $ezjscoreIni->variable( 'eZJSCore', 'LoadFromCDN' ) === 'enabled' )
        {
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'ExternalScripts' );
            $packerFiles = array_merge( array( $scriptFiles['yui2'], 'ezjsc::yui2conf' ), $packerFiles );
        }
        else
        {
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'LocaleScripts' );
            $scriptBases = $ezjscoreIni->variable( 'eZJSCore', 'LocaleScriptBasePath' );
            $packerFiles = array_merge( array( $scriptFiles['yui2'], 'ezjsc::yui2conf::' . $scriptBases['yui2'] ), $packerFiles );
        }
        return '';
    }
    
    /**
     * Yui2 config as requested by {@link ezjscServerFunctionsJs::yui2()}
     *
     * @param array $args First value is base bath if set
     * @return string YUI 2.0 JavaScript config string
     */
    public static function yui2conf( $args )
    {
        if ( isset( $args[0] ) )
        {
            return 'var YUILoader =  new YAHOO.util.YUILoader({
                base: \'' . self::getDesignFile( $args[0] ) . '\',
                loadOptional: true
            });';
        }

        return 'var YUILoader =  new YAHOO.util.YUILoader({
            loadOptional: true,
            combine: true
        });';
    }

    /**
     * Figgures out where to load yui3 files from and prepends them to $packerFiles
     *
     * @param array $args
     * @param array $packerFiles ByRef list of files to pack (by ezjscPacker)
     * @return string Empty string
     */
    public static function yui3( $args, &$packerFiles )
    {
        $ezjscoreIni = eZINI::instance( 'ezjscore.ini' );
        if ( $ezjscoreIni->variable( 'eZJSCore', 'LoadFromCDN' ) === 'enabled' )
        {
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'ExternalScripts' );
            $packerFiles = array_merge( array( $scriptFiles['yui3'], 'ezjsc::yui3conf' ), $packerFiles );
        }
        else
        {
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'LocaleScripts' );
            $scriptBases = $ezjscoreIni->variable( 'eZJSCore', 'LocaleScriptBasePath' );
            $packerFiles = array_merge( array( $scriptFiles['yui3'], 'ezjsc::yui3conf::' . $scriptBases['yui3'] ), $packerFiles );
        }
        return '';
    }

    /**
     * Yui3 config as requested by {@link ezjscServerFunctionsJs::yui3()}
     *
     * @param array $args First value is base bath if set
     * @return string YUI 3.0 JavaScript config string
     */
    public static function yui3conf( $args )
    {
        if ( isset( $args[0] ) )                
            return 'var YUI3_config = { \'base\' : \'' . self::getDesignFile( $args[0] ) . '\', modules: {} };';

        return 'var YUI3_config = { modules: {} };';
    }

    /**
     * Generates the javascript needed to do server calls directly from javascript in yui3.0
     *
     * @param array $args
     * @return string YUI 3.0 JavaScript plugin string
     */
    public static function yui3io( $args )
    {
        $url = self::getIndexDir() . 'ezjscore/';
        return "
YUI( YUI3_config ).add('io-ez', function( Y )
{
    var _serverUrl = '$url', _seperator = '@SEPERATOR$', _configBak;

    function _ez( callArgs, c )
    {
        callArgs = callArgs.join !== undefined ? callArgs.join( _seperator ) : callArgs;
        var url = _serverUrl + 'call/' + encodeURIComponent( callArgs );

        // force POST methode
        if ( c === undefined )
            c = {on:{}, data: '', headers: {}, method: 'POST'};
        else
            c = Y.merge( {on:{}, data: '', headers: {}}, c, {method: 'POST'} );

        // append function arguments as post param for encoding safty
        c.data += ( c.data !== '' ? '&' : '' ) + 'ezjscServer_function_arguments=' + callArgs;

        // force json transport
        c.headers.Accept = 'application/json,text/javascript,*/*';

        // backup user success call
        if ( c.on.success !== undefined )
            c.on.successCallback = c.on.success;

        c.on.success = _ioezSuccess;
        _configBak = c;

        return Y.io( url, c );
    }

    function _ioezSuccess( id, o )
    {
        if ( o.responseJSON === undefined )
        {
            // create new object to avoid error in ie6 (and do not use Y.merge since it fails in ff)
            var returnObject = {'responseJSON': Y.JSON.parse( o.responseText ),
                                'readyState': o.readyState,
                                'responseText': o.responseText,
                                'responseXML': o.responseXML,
                                'status': o.status,
                                'statusText': o.statusText
            };
        }
        else
        {
            var returnObject = o;
        }

        var c = _configBak;
        if ( c.on.successCallback !== undefined )
        {
            c.on.successCallback( id, returnObject );
        }
        else if ( window.console !== undefined )
        {
            if ( returnObject.responseJSON.error_text )
                window.console.error( 'Y.ez(): ' + returnObject.responseJSON.error_text );
            else
                window.console.log( 'Y.ez(): ' + returnObject.responseJSON.content );
        }
    }

    _ez.url = _serverUrl;
    _ez.seperator = _seperator;
    Y.io.ez = _ez;
}, '3.0.0' ,{requires:['io-base', 'json-parse']});
        ";
    }

    /**
     * Figgures out where to load jQuery files from and prepends them to $packerFiles
     *
     * @param array $args
     * @param array $packerFiles ByRef list of files to pack (by ezjscPacker)
     */
    public static function jquery( $args, &$packerFiles )
    {
        $ezjscoreIni = eZINI::instance( 'ezjscore.ini' );
        if ( $ezjscoreIni->variable( 'eZJSCore', 'LoadFromCDN' ) === 'enabled' )
        {
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'ExternalScripts' );
            $packerFiles = array_merge( array( $scriptFiles['jquery'] ), $packerFiles );
        }
        else
        {
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'LocaleScripts' );
            $packerFiles = array_merge( array( $scriptFiles['jquery'] ), $packerFiles );
        }
        return '';
    }

    /**
     * Generates the javascript needed to do server calls directly from javascript in jquery
     *
     * @param array $args
     * @return string jQuery JavaScript plugin string
     */
    public static function jqueryio( $args )
    {
        $url = self::getIndexDir() . 'ezjscore/';
        return "
(function($) {
    var _serverUrl = '$url', _seperator = '@SEPERATOR$';

    // (static) function version
    function _ez( callArgs, post, callBack )
    {
        callArgs = callArgs.join !== undefined ? callArgs.join( _seperator ) : callArgs;
        var url = _serverUrl + 'call/' + encodeURIComponent( callArgs );
        post = post === undefined ? {} : post;
        post['ezjscServer_function_arguments'] = callArgs;

        return $.post( url, post, callBack, 'json' );
    };
    _ez.url = _serverUrl;
    _ez.seperator = _seperator;
    $.ez = _ez; 

    // methode version, for loading response into elements
    // NB: Does not use json (not possible with .load), so ezjscore/call will return string
    function _ezLoad( callArgs, post, selector, callBack )
    {
        callArgs = callArgs.join !== undefined ? callArgs.join( _seperator ) : callArgs;
        var url = _serverUrl + 'call/' + encodeURIComponent( callArgs );
        post = post === undefined ? {} : post;
        post['ezjscServer_function_arguments'] = callArgs;

        return this.load( url + ( selector ? ' ' + selector : '' ), post, callBack );
    };
    $.fn.ez = _ezLoad;
})(jQuery);
        ";
    }

    /**
     * Reimp
     */
    public static function getCacheTime( $functionName )
    {
        // Functions that always needs to be executed, since they append other files dynamically
        if ( $functionName === 'yui3' || $functionName === 'yui2' || $functionName === 'jquery' )
            return -1;

        static $mtime = null;
        if ( $mtime === null )
        {
        	$mtime = filemtime( __FILE__ );
        }
    	return $mtime;
    }

    /**
     * Internal function to get current index dir
     * 
     * @return string
     */
    protected static function getIndexDir()
    {
        static $cachedIndexDir = null;
    	if ( $cachedIndexDir === null )
        {
            $cachedIndexDir = eZSys::indexDir() . '/';
        }
        return $cachedIndexDir;
    }

    /**
     * Internal function to get current index dir
     * 
     * @return string
     */
    protected static function getDesignFile( $file )
    {
        static $bases = null;
        static $wwwDir = null;
        if ( $bases === null )
            $bases = eZTemplateDesignResource::allDesignBases();
        if ( $wwwDir === null )
            $wwwDir = eZSys::wwwDir() . '/';

        $triedFiles = array();
        $match = eZTemplateDesignResource::fileMatch( $bases, '', $file, $triedFiles );
        if ( $match === false )
        {
            eZDebug::writeWarning( "Could not find: $file", __METHOD__ );
            return false;
        }
        return $wwwDir . htmlspecialchars( $match['path'] );
    }
}

?>