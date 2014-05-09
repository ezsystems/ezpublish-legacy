<?php
/**
 * File containing the ezpRestHttpRequestParser class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Custom request parser which creates instances of ezpRestRequest.
 *
 * The main difference is that GET and POST data is protected from potential
 * cookie pollution. And each category of variable has its own silo, to prevent
 * one from overwriting another.
 */
class ezpRestHttpRequestParser extends ezcMvcHttpRequestParser
{
    /**
     * @var ezpRestRequest
     */
    protected $request;

    /**
     * Overload createRequestObject() to make sure ezpRestRequest is created.
     *
     * @return ezpRestRequest
     */
    protected function createRequestObject()
    {
        return new ezpRestRequest();
    }

    /**
     * Overloads processVariables() to instead get ezpRest specific variables
     *
     * Note: ->variables is set with ezpRest specific variables instead of raw $_REQUEST.
     *
     * @return void
     */
    protected function processVariables()
    {
        $this->request->variables = $this->fillVariables();
        $this->request->contentVariables = $this->fillContentVariables();
        $this->request->get = $_GET;
        $this->request->post = $_POST;
    }

    /**
     * Overloads parent::processStandardHeaders() to also call processEncryption()
     *
     * @return void
     */
    protected function processStandardHeaders()
    {
        $this->processEncryption();
        parent::processStandardHeaders( );
    }

    /**
     * Sets the isEncrypted flag if HTTPS is on.
     *
     * @return void
     */
    protected function processEncryption()
    {
        if ( eZSys::isSSLNow() )
            $this->request->isEncrypted = true;
    }

    /**
     *  Overloads processBody() to add support for body on DELETE in addition to PUT
     */
    protected function processBody()
    {
        $this->request->body = file_get_contents( "php://input" );
    }

    /**
     * Extract variables to be used internally from GET
     *
     * @return array
     */
    protected function fillVariables()
    {
        $variables = array();
        $internalVariables = array( 'ResponseGroups' ); // Expected variables

        foreach( $internalVariables as $internalVariable )
        {
            if( isset( $_GET[$internalVariable] ) )
            {
                // Extract and organize variables as expected
                switch( $internalVariable )
                {
                    case 'ResponseGroups':
                        $variables[$internalVariable] = explode( ',', $_GET[$internalVariable] );
                        break;

                    default:
                        $variables[$internalVariable] = $_GET[$internalVariable];
                }

                unset( $_GET[$internalVariable] );
            }
            else
            {
                switch( $internalVariable )
                {
                    case 'ResponseGroups':
                        $variables[$internalVariable] = array();
                        break;

                    default:
                        $variables[$internalVariable] = null;
                }
            }
        }

        return $variables;
    }

    /**
     * Extract variables related to content from GET
     *
     * @return array
     */
    protected function fillContentVariables()
    {
        $contentVariables = array();
        $expectedVariables = array( 'Translation', 'OutputFormat' );

        foreach( $expectedVariables as $variable )
        {
            if( isset( $_GET[$variable] ) )
            {
                // Extract and organize variables as expected
                switch( $variable )
                {
                    case 'Translation': // @TODO => Make some control on the locale provided
                    default:
                        $contentVariables[$variable] = $_GET[$variable];
                }

                unset( $_GET[$variable] );
            }
            else
            {
                $contentVariables[$variable] = null;
            }
        }

        return $contentVariables;
    }

    /**
     * Processes the request protocol.
     */
    protected function processProtocol()
    {
        $req = $this->request;
        $req->originalProtocol = $req->protocol = 'http-' . ( isset( $_SERVER['REQUEST_METHOD'] ) ? strtolower( $_SERVER['REQUEST_METHOD'] ) : "get" );

        // Adds support for using POST for PUT and DELETE for legacy browsers that does not support these.
        // If a post param "_method" is set to either PUT or DELETE, then ->protocol is changed to that.
        // (original protocol is kept on ->originalProtocol param)
        // Post is used as this is only meant for forms in legacy browsers.
        if ( $req->protocol === 'http-post' && isset( $_POST['_method'] ) )
        {
            $method = strtolower( $_POST['_method'] );
            if ( $method  === 'put' || $method === 'delete' )
                $req->protocol = "http-{$method}";

            unset( $_POST['_method'] );
        }
    }

    /**
     * Processes the request date.
     *
     * @see http://issues.ez.no/19027
     */
    protected function processDate()
    {
        $this->request->date = isset( $_SERVER['REQUEST_TIME'] )
            ? new DateTime( '@' . (int)$_SERVER['REQUEST_TIME'] )
            : new DateTime();
    }
}
