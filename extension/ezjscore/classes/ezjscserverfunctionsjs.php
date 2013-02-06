<?php
//
// Definition of ezjscServerFunctionsJs class
//
// Created on: <16-Jun-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2013 eZ Systems AS
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
     * @return string Empty string, this function only modifies $packerFiles
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
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'LocalScripts' );
            $scriptBases = $ezjscoreIni->variable( 'eZJSCore', 'LocalScriptBasePath' );
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
            return 'var YUI2_config = {
                base: \'' . self::getDesignFile( $args[0] ) . '\',
                loadOptional: true
            };
            var YUILoader =  new YAHOO.util.YUILoader(YUI2_config);';
        }

        return 'var YUI2_config = {
            loadOptional: true,
            combine: true
        };
        var YUILoader =  new YAHOO.util.YUILoader(YUI2_config);';
    }

    /**
     * Figures out where to load yui3 files from and prepends them to $packerFiles
     *
     * @param array $args
     * @param array $packerFiles ByRef list of files to pack (by ezjscPacker)
     * @return string Empty string, this function only modifies $packerFiles
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
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'LocalScripts' );
            $scriptBases = $ezjscoreIni->variable( 'eZJSCore', 'LocalScriptBasePath' );
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
        $options = eZINI::instance( 'ezjscore.ini' )->variable( 'YUI3', 'LoaderOptions' );

        if ( isset( $args[0] ) )
        {
            $options['base'] = self::getDesignFile( $args[0] );
            if ( !isset( $options['combine'] ) )
            {
                $options['combine'] = false;
            }
        }
        if ( !isset( $options['modules'] ) )
        {
            $options['modules'] = new stdClass;
        }

        return 'var YUI3_config = ' . json_encode( $options ) . ';';
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
    var _rootUrl = '$rootUrl', _serverUrl = _rootUrl + 'ezjscore/', _seperator = '@SEPERATOR$', _configBak,
        _prefUrl = _rootUrl + 'user/preferences';

    // (static) Y.io.ez() uses Y.io()
    //
    // @param string callArgs
    // @param object|undefined c Same format as second parameter of Y.io()
    function _ez( callArgs, c )
    {
        callArgs = callArgs.join !== undefined ? callArgs.join( _seperator ) : callArgs;
        var url = _serverUrl + 'call/';

        // Merge configuration object
        if ( c === undefined )
            c = {on:{}, data: '', headers: {}, method: 'POST'};
        else
            c = Y.merge( {on:{}, data: '', headers: {}, method: 'POST'}, c );

        var _token = '', _tokenNode = document.getElementById('ezxform_token_js');
        if ( _tokenNode ) _token = '&ezxform_token=' + _tokenNode.getAttribute('title');

        // Append function arguments as post param if method is POST
        if ( c.method === 'POST' )
            c.data += ( c.data ? '&' : '' ) + 'ezjscServer_function_arguments=' + callArgs + _token;
        else
            url += encodeURIComponent( callArgs );

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
        _configBak.on.success = _configBak.on.successCallback;
        _configBak.on.successCallback = undefined;
    }

    _ez.url = _serverUrl;
    _ez.root_url = _rootUrl;
    _ez.seperator = _seperator;
    Y.io.ez = _ez;
    Y.io.ez.setPreference = function( name, value )
    {
        var c = {on:{}, data:'', headers: {}, method: 'POST'},
            _tokenNode = document.getElementById( 'ezxform_token_js' );

        c.data = 'Function=set_and_exit&Key=' + encodeURIComponent( name ) + '&Value=' + encodeURIComponent( value );
        if ( _tokenNode )
            c.data += '&ezxform_token=' + _tokenNode.getAttribute( 'title' );
        return Y.io( _prefUrl, c );
    }
}, '3.0.0' ,{requires:['io-base', 'json-parse']});
        ";
    }

    /**
     * Figures out where to load jQuery files from and prepends them to $packerFiles
     *
     * @param array $args
     * @param array $packerFiles ByRef list of files to pack (by ezjscPacker)
     * @return string Empty string, this function only modifies $packerFiles
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
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'LocalScripts' );
            $packerFiles = array_merge( array( $scriptFiles['jquery'] ), $packerFiles );
        }
        return '';
    }

    /**
     * Figures out where to load jQueryUI files from and prepends them to $packerFiles
     *
     * @param array $args
     * @param array $packerFiles ByRef list of files to pack (by ezjscPacker)
     * @return string Empty string, this function only modifies $packerFiles
     */
    public static function jqueryUI( $args, &$packerFiles )
    {
        $ezjscoreIni = eZINI::instance( 'ezjscore.ini' );
        if ( $ezjscoreIni->variable( 'eZJSCore', 'LoadFromCDN' ) === 'enabled' )
        {
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'ExternalScripts' );
            $packerFiles = array_merge( array( $scriptFiles['jqueryUI'] ), $packerFiles );
        }
        else
        {
            $scriptFiles = $ezjscoreIni->variable( 'eZJSCore', 'LocalScripts' );
            $packerFiles = array_merge( array( $scriptFiles['jqueryUI'] ), $packerFiles );
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
    var _rootUrl = '$rootUrl', _serverUrl = _rootUrl + 'ezjscore/', _seperator = '@SEPERATOR$',
        _prefUrl = _rootUrl + 'user/preferences';

    // FIX: Ajax is broken on IE8 / IE7 on jQuery 1.4.x as it's trying to use the broken window.XMLHttpRequest object
    if ( window.XMLHttpRequest && window.ActiveXObject )
        $.ajaxSettings.xhr = function() { try { return new window.ActiveXObject('Microsoft.XMLHTTP'); } catch(e) {} };

    // (static) jQuery.ez() uses jQuery.post() (Or jQuery.get() if post paramer is false)
    //
    // @param string callArgs
    // @param object|array|string|false post Optional post values, uses get request if false or undefined
    // @param function Optional callBack
    function _ez( callArgs, post, callBack )
    {
        callArgs = callArgs.join !== undefined ? callArgs.join( _seperator ) : callArgs;
        var url = _serverUrl + 'call/';
        if ( post )
        {
            var _token = '', _tokenNode = document.getElementById('ezxform_token_js');
            if ( _tokenNode ) _token = _tokenNode.getAttribute('title');
            if ( post.join !== undefined )// support serializeArray() format
            {
                post.push( { 'name': 'ezjscServer_function_arguments', 'value': callArgs } );
                post.push( { 'name': 'ezxform_token', 'value': _token } );
            }
            else if ( typeof(post) === 'string' )// string
            {
                post += ( post ? '&' : '' ) + 'ezjscServer_function_arguments=' + callArgs + '&ezxform_token=' + _token;
            }
            else // object
            {
                post['ezjscServer_function_arguments'] = callArgs;
                post['ezxform_token'] = _token;
            }
            return $.post( url, post, callBack, 'json' );
        }
        return $.get( url + encodeURIComponent( callArgs ), {}, callBack, 'json' );
    };
    _ez.url = _serverUrl;
    _ez.root_url = _rootUrl;
    _ez.seperator = _seperator;
    $.ez = _ez;

    $.ez.setPreference = function( name, value )
    {
        var param = {'Function': 'set_and_exit', 'Key': name, 'Value': value};
            _tokenNode = document.getElementById( 'ezxform_token_js' );
        if ( _tokenNode )
            param.ezxform_token = _tokenNode.getAttribute( 'title' );

        return $.post( _prefUrl, param );
    };

    // Method version, for loading response into elements
    // NB: Does not use json (not possible with .load), so ezjscore/call will return string
    function _ezLoad( callArgs, post, selector, callBack )
    {
        callArgs = callArgs.join !== undefined ? callArgs.join( _seperator ) : callArgs;
        var url = _serverUrl + 'call/';
        if ( post )
        {
            post['ezjscServer_function_arguments'] = callArgs;
            post['ezxform_token'] = jQuery('#ezxform_token_js').attr('title');
        }
        else
            url += encodeURIComponent( callArgs );

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

        // Do not allow to search for more then x items at a time
        $ini = eZINI::instance();
        $maximumSearchLimit = (int) $ini->variable( 'SearchSettings', 'MaximumSearchLimit' );
        if ( $searchLimit > $maximumSearchLimit )
            $searchLimit = $maximumSearchLimit;

        // Prepare node encoding parameters
        $encodeParams = array();
        if ( self::hasPostValue( $http, 'EncodingLoadImages' ) )
            $encodeParams['loadImages'] = true;

        if ( self::hasPostValue( $http, 'EncodingFetchChildrenCount' ) )
            $encodeParams['fetchChildrenCount'] = true;

        if ( self::hasPostValue( $http, 'EncodingFetchSection' ) )
            $encodeParams['fetchSection'] = true;

        if ( self::hasPostValue( $http, 'EncodingFormatDate' ) )
            $encodeParams['formatDate'] = $http->postVariable( 'EncodingFormatDate' );

        // Prepare search parameters
        $params = array( 'SearchOffset' => $searchOffset,
                         'SearchLimit' => $searchLimit,
                         'SortArray' => array( 'published', 0 ), // Legacy search engine uses SortArray
                         'SortBy' => array( 'published' => 'desc' ) // eZ Find search method implementation uses SortBy
        );

        if ( self::hasPostValue( $http, 'SearchContentClassAttributeID' ) )
        {
             $params['SearchContentClassAttributeID'] = self::makePostArray( $http, 'SearchContentClassAttributeID' );
        }
        else if ( self::hasPostValue( $http, 'SearchContentClassID' ) )
        {
             $params['SearchContentClassID'] = self::makePostArray( $http, 'SearchContentClassID' );
        }
        else if ( self::hasPostValue( $http, 'SearchContentClassIdentifier' ) )
        {
             $params['SearchContentClassID'] = eZContentClass::classIDByIdentifier( self::makePostArray( $http, 'SearchContentClassIdentifier' ) );
        }

        if ( self::hasPostValue( $http, 'SearchSubTreeArray' ) )
        {
             $params['SearchSubTreeArray'] = self::makePostArray( $http, 'SearchSubTreeArray' );
        }

        if ( self::hasPostValue( $http, 'SearchSectionID' ) )
        {
             $params['SearchSectionID'] = self::makePostArray( $http, 'SearchSectionID' );
        }

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

        if ( self::hasPostValue( $http, 'EnableSpellCheck' ) || self::hasPostValue( $http, 'enable-spellcheck', '0' ) )
        {
            $params['SpellCheck'] = array( true );
        }

        if ( self::hasPostValue( $http, 'GetFacets' ) || self::hasPostValue( $http, 'show-facets', '0' ) )
        {
            $params['facet'] = eZFunctionHandler::execute( 'ezfind', 'getDefaultSearchFacets', array() );
        }

        $result = array( 'SearchOffset' => $searchOffset,
                         'SearchLimit' => $searchLimit,
                         'SearchResultCount' => 0,
                         'SearchCount' => 0,
                         'SearchResult' => array(),
                         'SearchString' => $searchStr,
                         'SearchExtras' => array()
        );

        // Possibility to keep track of callback reference for use in js callback function
        if ( $http->hasPostVariable( 'CallbackID' ) )
            $result['CallbackID'] = $http->postVariable( 'CallbackID' );

        // Only search if there is something to search for
        if ( $searchStr )
        {
            $searchList = eZSearch::search( $searchStr, $params );

            $result['SearchResultCount'] = $searchList['SearchResult'] !== false ? count( $searchList['SearchResult'] ) : 0;
            $result['SearchCount'] = (int) $searchList['SearchCount'];
            $result['SearchResult'] = ezjscAjaxContent::nodeEncode( $searchList['SearchResult'], $encodeParams, false );

            // ezfind stuff
            if ( isset( $searchList['SearchExtras'] ) && $searchList['SearchExtras'] instanceof ezfSearchResultInfo )
            {
                if ( isset( $params['SpellCheck'] ) )
                    $result['SearchExtras']['spellcheck'] = $searchList['SearchExtras']->attribute( 'spellcheck' );


                if ( isset( $params['facet'] ) )
                {
                    $facetInfo = array();
                    $retrievedFacets = $searchList['SearchExtras']->attribute( 'facet_fields' );
                    $baseSearchUrl = "/content/search/";
                    eZURI::transformURI( $baseSearchUrl, false, 'full' );

                    foreach ( $params['facet'] as $key => $defaultFacet )
                    {
                        $facetData       = $retrievedFacets[$key];
                        $facetInfo[$key] = array( 'name' => $defaultFacet['name'], 'list' => array() );
                        if ( $facetData !== null )
                        {
                            foreach ( $facetData['nameList'] as $key2 => $facetName )
                            {
                                if ( $key2 != '' )
                                {
                                    $tmp = array( 'value' => $facetName );
                                    $tmp['url'] = $baseSearchUrl . '?SearchText=' . $searchStr . '&filter[]=' . $facetData['queryLimit'][$key2] . '&activeFacets[' . $defaultFacet['field'] . ':' . $defaultFacet['name'] . ']=' . $facetName;
                                    $tmp['count'] = $facetData['countList'][$key2];
                                    $facetInfo[$key]['list'][] = $tmp;
                                }
                            }
                        }
                    }
                    $result['SearchExtras']['facets'] = $facetInfo;
                }
            }//$searchList['SearchExtras'] instanceof ezfSearchResultInfo
        }// $searchStr

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
                return $value;
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
    protected static function hasPostValue( eZHTTPTool $http, $key, $falseValue = '' )
    {
        return $http->hasPostVariable( $key ) && $http->postVariable( $key ) !== $falseValue;
    }

    /**
     * Reimp
     *
     * @param string $fn FunctionName to get cache time for
     * @return int -1 if function does not support caching, eg: yui3, yui2, jquery & jqueryUI
     */
    public static function getCacheTime( $fn )
    {
        // Functions that always needs to be executed, since they append other files dynamically
        if ( $fn === 'yui3' || $fn === 'yui2' || $fn === 'jquery' || $fn === 'jqueryUI' )
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
