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
            return htmlspecialchars( $args[0] ) . '_' . time();
        return time();
    }

    /**
     * Figures out where to load yui2 files from and prepends them to $packerFiles
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
     * Figures out where to load yui3 files from and prepends them to $packerFiles
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
     * Generates the JavaScript needed to do server calls directly from JavaScript in yui3.0
     *
     * @param array $args
     * @return string YUI 3.0 JavaScript plugin string
     */
    public static function yui3io( $args )
    {
        $rootUrl = self::getIndexDir();
        return "
YUI( YUI3_config ).add('io-ez', function( Y )
{
    var _rootUrl = '$rootUrl', _serverUrl = _rootUrl + 'ezjscore/', _seperator = '@SEPERATOR$', _configBak;

    function _ez( callArgs, c )
    {
        callArgs = callArgs.join !== undefined ? callArgs.join( _seperator ) : callArgs;
        var url = _serverUrl + 'call/';

        // force POST method
        if ( c === undefined )
            c = {on:{}, data: '', headers: {}, method: 'POST'};
        else
            c = Y.merge( {on:{}, data: '', headers: {}}, c, {method: 'POST'} );

        // append function arguments as post param for encoding safety
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
            if ( c.arguments !== undefined )
                c.on.successCallback( id, returnObject, c.arguments );
            else
                c.on.successCallback( id, returnObject, null );
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
    _ez.root_url = _rootUrl;
    _ez.seperator = _seperator;
    Y.io.ez = _ez;
}, '3.0.0' ,{requires:['io-base', 'json-parse']});
        ";
    }

    /**
     * Figures out where to load jQuery files from and prepends them to $packerFiles
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
     * Generates the JavaScript needed to do server calls directly from JavaScript in jQuery
     *
     * @param array $args
     * @return string jQuery JavaScript plugin string
     */
    public static function jqueryio( $args )
    {
        $rootUrl = self::getIndexDir();
        return "
(function($) {
    var _rootUrl = '$rootUrl', _serverUrl = _rootUrl + 'ezjscore/', _seperator = '@SEPERATOR$';

    // (static) function version
    function _ez( callArgs, post, callBack )
    {
        callArgs = callArgs.join !== undefined ? callArgs.join( _seperator ) : callArgs;
        var url = _serverUrl + 'call/';
        post = post === undefined ? {} : post;
        post['ezjscServer_function_arguments'] = callArgs;

        return $.post( url, post, callBack, 'json' );
    };
    _ez.url = _serverUrl;
    _ez.root_url = _rootUrl;
    _ez.seperator = _seperator;
    $.ez = _ez;

    // Method version, for loading response into elements
    // NB: Does not use json (not possible with .load), so ezjscore/call will return string
    function _ezLoad( callArgs, post, selector, callBack )
    {
        callArgs = callArgs.join !== undefined ? callArgs.join( _seperator ) : callArgs;
        var url = _serverUrl + 'call/';
        post = post === undefined ? {} : post;
        post['ezjscServer_function_arguments'] = callArgs;

        return this.load( url + ( selector ? ' ' + selector : '' ), post, callBack );
    };
    $.fn.ez = _ezLoad;
})(jQuery);
        ";
    }

    /**
     * Returns search results based on given post params 
     * 
     * @param mixed $args Only used if post parameter is not set
     *              0 => SearchStr
     *              1 => SearchOffset
     *              2 => SearchLimit (10 by default, max 50)
     * @return array
     */
    public static function search( $args )
    {
        $http = eZHTTPTool::instance();

        if ( $http->hasPostVariable( 'SearchStr' ) )
            $searchStr = trim( $http->postVariable( 'SearchStr' ) );
        else if ( isset( $args[0] ) )
            $searchStr = trim( $args[0] );

        if ( $http->hasPostVariable( 'SearchOffset' ))
            $searchOffset = (int) $http->postVariable( 'SearchOffset' );
        else if ( isset( $args[1] ) )
            $searchOffset = (int) $args[1];
        else
            $searchOffset = 0;

        if ( $http->hasPostVariable( 'SearchLimit' ))
            $searchLimit = (int) $http->postVariable( 'SearchLimit' );
        else if ( isset( $args[2] ) )
            $searchLimit = (int) $args[2];
        else
            $searchLimit = 10;

        // Do not allow to search for more then 50 items at a time
        if ( $searchLimit > 50 ) $searchLimit = 50;

        // Prepare node encoding parameters
        $encodeParams = array();
        if ( self::hasPostValue( $http, 'EncodingLoadImages' ) )
            $encodeParams['loadImages'] = true;

        if ( self::hasPostValue( $http, 'EncodingFetchChildrenCount' ) )
            $encodeParams['fetchChildrenCount'] = true;

        if ( self::hasPostValue( $http, 'EncodingFetchSection' ) )
            $encodeParams['fetchSection'] = true;

        // Prepare search parameters
        $params = array( 'SearchOffset' => $searchOffset,
                         'SearchLimit' => $searchLimit,
                         'SortArray' => array( 'published', 0 )
        );
        if ( self::hasPostValue( $http, 'SearchContentClassAttributeID' ) )
             $params['SearchContentClassAttributeID'] = self::makePostArray( $http, 'SearchContentClassAttributeID' );
        else if ( self::hasPostValue( $http, 'SearchContentClassID' ) )
             $params['SearchContentClassID'] = self::makePostArray( $http, 'SearchContentClassID' );

        if ( self::hasPostValue( $http, 'SearchSubTreeArray' ) )
             $params['SearchSubTreeArray'] = self::makePostArray( $http, 'SearchSubTreeArray' );

        if ( self::hasPostValue( $http, 'SearchSectionID' ) )
             $params['SearchSectionID'] = self::makePostArray( $http, 'SearchSectionID' );

        if ( self::hasPostValue( $http, 'SearchDate' ) )
        {
             $params['SearchDate'] = (int) $http->postVariable( 'SearchDate' );
        }
        else if ( self::hasPostValue( $http, 'SearchTimestamp' ) )
        {
            $params['SearchTimestamp'] = self::makePostArray( $http, 'SearchTimestamp' );
            if ( !isset( $params['SearchTimestamp'][1] ) )
                $params['SearchTimestamp'] = $params['SearchTimestamp'][0];
        }
        
        $result = array( 'SearchOffset' => $searchOffset,
                         'SearchLimit' => $searchLimit,
                         'SearchResultCount' => 0,
                         'SearchCount' => 0,
                         'SearchResult' => array(),
        );

        // Possibility to keep track of callback reference for use in js callback function
        if ( $http->hasPostVariable( 'CallbackID' ) )
            $result['CallbackID'] = $http->postVariable( 'CallbackID' );

        // Only search if there is something to search for
        if ( $searchStr )
        {
            $searchList = eZSearch::search( $searchStr, $params );

            $result['SearchResultCount'] = count( $searchList['SearchResult'] );
            $result['SearchCount'] = $searchList['SearchCount'];
            $result['SearchResult'] = ezjscAjaxContent::nodeEncode( $searchList['SearchResult'], $encodeParams, false );
        }

        return $result;
    }

    /**
     * Creates an array out of a post parameter, return empty array if post parameter is not set.
     * Splits string on ',' in case of comma seperated values.
     * 
     * @param eZHTTPTool $http
     * @param string $key
     * @return array
     */
    protected static function makePostArray( eZHTTPTool $http, $key )
    {
        if ( $http->hasPostVariable( $key ) && $http->postVariable( $key ) !== '' )
        {
            $value = $http->postVariable( $key );
            if ( is_array( $value ) )
                return $str;
            elseif( strpos($value, ',') === false )
                return array( $value );
            else
                return explode( ',', $value );
        }
        return array();
    }

    /**
     * Checks if a post variable exitst and has a value
     * 
     * @param eZHTTPTool $http
     * @param string $key
     * @return bool
     */
    protected static function hasPostValue( eZHTTPTool $http, $key )
    {
        return $http->hasPostVariable( $key ) && $http->postVariable( $key ) !== '';
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