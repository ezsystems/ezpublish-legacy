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
     * Generates the javascript needed to do server calls directly from javascript in yui3.0
     *
     * @param array $args
     * @return string YUI 3.0 JavaScript plugin string
     */
    public static function yui3( $args )
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
            o.responseJSON = Y.JSON.parse( o.responseText )

        var c = _configBak;
        if ( c.on.successCallback !== undefined )
        {
            c.on.successCallback( id, o );
        }
        else if ( window.console !== undefined )
        {
            if ( o.responseJSON.error_text )
                window.console.error( 'Y.ez(): ' + o.responseJSON.error_text );
            else
                window.console.log( 'Y.ez(): ' + o.responseJSON.content );
        }
    }

    _ez.url = _serverUrl;
    _ez.seperator = _seperator;
    Y.io.ez = _ez;
}, '3.0.0b1' ,{requires:['io-base', 'event', 'json-parse']});//io does't list event a dependancy, so until it does or stop using it, it is listed here..
        ";
    }

    /**
     * Generates the javascript needed to do server calls directly from javascript in jquery
     *
     * @param array $args
     * @return string jQuery JavaScript plugin string
     */
    public static function jquery( $args )
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
}

?>