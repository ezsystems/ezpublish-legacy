{literal}
YUI( YUI3_config ).add('io-ez', function( Y )
{
    var _rootUrl = '{/literal}{$rootUrl}{literal}',
        _serverUrl = _rootUrl + 'ezjscore/',
        _seperator = '@SEPERATOR$',
        _configBak, _prefUrl = _rootUrl + 'user/preferences';

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
{/literal}
