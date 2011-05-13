<?php
/**
 * File containing Mvc configuration
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
class ezpMvcConfiguration implements ezcMvcDispatcherConfiguration
{
    const FILTER_TYPE_PREROUTING = 'PreRouting';
    const FILTER_TYPE_REQUEST = 'Request';
    const FILTER_TYPE_RESULT = 'Result';
    const FILTER_TYPE_RESPONSE = 'Response';

    const INDEX_FILE = 'index_rest.php';

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
        $req->uri = $this->apiPrefix . '/fatal';
        $req->variables['exception'] = $e;
        return $req;
    }

    public function createRequestParser()
    {
        $parser = new ezpRestHttpRequestParser();
        if ( strpos( $_SERVER['SCRIPT_NAME'], self::INDEX_FILE ) !== false ) // Non-vhost mode
        {
            // In non-vhost mode we need to build the prefix to be removed from URI
            // This prefix is contained in SCRIPT_NAME server variable
            $parser->prefix = $_SERVER['SCRIPT_NAME'];
            if ( strpos( $_SERVER['REQUEST_URI'], self::INDEX_FILE ) === false ) // Index file doesn't appear in requested URI, remove it from the prefix
            {
                $parser->prefix = str_replace( '/'.self::INDEX_FILE, '', $parser->prefix );
            }
        }

        return $parser;
    }

    public function createResponseWriter( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result, ezcMvcResponse $response )
    {
        return new ezpRestHttpResponseWriter( $response );
    }

    public function createRouter( ezcMvcRequest $request )
    {
        return new ezpRestRouter( $request );
    }

    public function createView( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result )
    {
        $viewController = ezpRestProvider::getProvider( ezpRestPrefixFilterInterface::getApiProviderName() )->getViewController();
        $view = $viewController->loadView( $routeInfo, $request, $result );

        return $view;
    }

    public function runPreRoutingFilters( ezcMvcRequest $request )
    {
        $prefixFilterOptions = new ezpExtensionOptions();
        $prefixFilterOptions->iniFile = 'rest.ini';
        $prefixFilterOptions->iniSection = 'System';
        $prefixFilterOptions->iniVariable = 'PrefixFilterClass';
        $prefixFilterOptions->handlerParams = array( $request, $this->apiPrefix );

        $prefixFilter = eZExtension::getHandlerClass( $prefixFilterOptions );
        $prefixFilter->filter();
        // We call this method here, so that implementors won't have to remember
        // adding this call to their own filter() implementation.
        $prefixFilter->filterRequestUri();

        try
        {
            $this->runCustomFilters( self::FILTER_TYPE_PREROUTING, array( 'request' => $request ) );
        }
        catch ( Exception $e )
        {
            $request->variables['exception'] = $e;
            $request->uri = $this->apiPrefix . '/fatal';
            return new ezcMvcInternalRedirect( $request );
        }
    }

    public function runRequestFilters( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request )
    {
        // We need to catch exceptions here, as exceptions thrown in the RequestFilter
        // is not caught by MvcTools, so the error controller will not pick them up.
        try
        {
            $this->runCustomFilters( self::FILTER_TYPE_REQUEST, array( 'routeInfo' => $routeInfo, 'request' => $request ) );
            $authConfig = new ezpRestAuthConfiguration( $routeInfo, $request );
            // For now this return is needed in order to pass redirect requests to the dispatcher
            return $authConfig->filter();
        }
        catch ( Exception $e )
        {
            $request->variables['exception'] = $e;
            $request->uri = $this->apiPrefix . '/fatal';
            return new ezcMvcInternalRedirect( $request );
        }
    }

    public function runResponseFilters( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result, ezcMvcResponse $response )
    {
        $response->generator = "eZ Publish";
        $params = array( 'routeInfo' => $routeInfo, 'request' => $request,
                         'result' => $result, 'response' => $response );
        try
        {
            $this->runCustomFilters( self::FILTER_TYPE_RESPONSE, $params );
        } catch ( Exception $e )
        {
            $request->variables['exception'] = $e;
            $request->uri = $this->apiPrefix . '/fatal';
            return new ezcMvcInternalRedirect( $request );
        }
    }

    public function runResultFilters( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result )
    {
        $params = array( 'routeInfo' => $routeInfo, 'request' => $request,
                         'result' => $result );
        try
        {
            $this->runCustomFilters( self::FILTER_TYPE_RESULT, $params );
        } catch ( Exception $e )
        {
            $request->variables['exception'] = $e;
            $request->uri = $this->apiPrefix . '/fatal';
            return new ezcMvcInternalRedirect( $request );
        }
    }

    protected function runCustomFilters( $type, array $filterParams )
    {
        $filterName = $type . 'Filters';
        $interfaceName = 'ezpRest' . $filterName . 'FilterInterface';
        $definedCustomFilters = eZINI::instance( 'rest.ini' )->variable( $filterName , 'Filters' );

        if ( empty( $definedCustomFilters ) )
            return;

        foreach ( $definedCustomFilters as $filter )
        {
            switch( $interfaceName )
            {
                case 'ezpRestPreRoutingFilterInterface':
                    $filterObject = new $filter( $filterParams['request'] );
                break;

                case 'ezpRestRequestFilterInterface':
                    $filterObject = new $filter( $filterParams['routeInfo'], $filterParams['request'] );
                break;

                case 'ezpRestResultFilterInterface':
                    $filterObject = new $filter( $filterParams['routeInfo'], $filterParams['request'], $filterParams['result'] );
                break;

                case 'ezpRestResponseFilterInterface':
                    $filterObject = new $filter( $filterParams['routeInfo'], $filterParams['request'], $filterParams['result'], $filterParams['response'] );
                break;
            }

            if ( ! $filter instanceof $interfaceName )
                throw new ezpRestFilterNotFoundException( $filter );

            $filterObject->filter();
        }
    }

    protected function handleFilterException( $request, Exception $e )
    {
        $request->variables['exception'] = $e;
        $request->uri = $this->apiPrefix . '/fatal';
        return new ezcMvcInternalRedirect( $request );
    }
}
?>
