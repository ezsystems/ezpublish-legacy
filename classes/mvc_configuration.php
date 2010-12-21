<?php
/**
 * File containing Mvc configuration
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */
class ezpMvcConfiguration implements ezcMvcDispatcherConfiguration
{
    /**
     * @var string The path prefix for signifying HTTP calls to the REST interface. Can be empty in case of an api host.
     */
    public $apiPrefix;

    public function __construct()
    {
        $this->apiPrefix = eZINI::instance( 'rest.ini' )->variable( 'System', 'ApiPrefix' );
    }

    public function createFatalRedirectRequest( ezcMvcRequest $request, ezcMvcResult $result, Exception $e )
    {
        $req = clone $request;
        $req->uri = '/api/fatal';
        $req->variables['exception'] = $e;
        return $req;
    }

    public function createRequestParser()
    {
        $parser = new ezpRestHttpRequestParser();
        if ( strpos( $_SERVER['SCRIPT_NAME'], 'index_rest.php' ) !== false )
            $parser->prefix = $_SERVER['SCRIPT_NAME'];
        return $parser;
    }

    public function createResponseWriter( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result, ezcMvcResponse $response )
    {
        return new ezcMvcHttpResponseWriter( $response );
    }

    public function createRouter( ezcMvcRequest $request )
    {
        return new ezpRestRouter( $request );
    }

    public function createView( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result )
    {
        if ( $routeInfo->controllerClass === 'ezpRestAtomController' )
        {
            $view = new ezpRestAtomView( $request, $result );
        }
        else
        {
            $view = new ezpRestJsonView( $request, $result );
        }
        return $view;
    }

    public function runPreRoutingFilters( ezcMvcRequest $request )
    {
        $versionTokenOptions = new ezpExtensionOptions();
        $versionTokenOptions->iniFile = 'rest.ini';
        $versionTokenOptions->iniSection = 'System';
        $versionTokenOptions->iniVariable = 'VersionTokenClass';
        $versionTokenOptions->handlerParams = array( $request, $this->apiPrefix );

        $versionInfo = eZExtension::getHandlerClass( $versionTokenOptions );
        $versionInfo->filter();
        // We call this method here, so that implementors won't have to remember
        // adding this call to their own filter() implementation.
        $versionInfo->filterRequestUri();
    }

    public function runRequestFilters( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request )
    {
        // We need to catch exceptions here, as exceptions thrown in the RequestFilter
        // is not caught by MvcTools, so the error controller will not pick them up.
        try
        {
            // $authConfig = new ezpRestAuthConfiguration( $routeInfo, $request );
            // return $authConfig->filter();
        }
        catch ( Exception $e )
        {
            $request->variables['exception'] = $e;
            $request->uri = '/api/fatal';
            return new ezcMvcInternalRedirect( $request );
        }
    }

    public function runResponseFilters( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result, ezcMvcResponse $response )
    {
        $response->generator = "eZ Publish";
    }

    public function runResultFilters( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result )
    {

    }
}
?>
