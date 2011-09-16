<?php
/**
 * File containing the ezpRestHttpRequestParser class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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


    protected function createRequestObject()
    {
        return new ezpRestRequest();
    }

    protected function processVariables()
    {
        $this->request->variables = $this->fillVariables();
        $this->request->contentVariables = $this->fillContentVariables();
        $this->request->inputVariables = $this->fillInputVariables();
        $this->request->get = $_GET;
        $this->request->post = $_POST;
    }

    protected function processStandardHeaders()
    {
        $this->processEncryption();
        $this->processMethodOverride();
        parent::processStandardHeaders( );
    }

    /**
     * Sets the isEncrypted flag if HTTPS is on.
     *
     * @return void
     */
    protected function processEncryption()
    {
        if ( !empty( $_SERVER['HTTPS'] ) )
            $this->request->isEncrypted = true;
    }

    /**
     * Extract variables to be used internally from GET
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
     * Creates PUT & DELETE variables based on the protocol information
     *
     * @return array
     */
    protected function fillInputVariables()
    {
        $inputVariables = array();

        switch ( $this->request->protocol )
        {
            case 'http-put':
            case 'http-delete':
                parse_str( file_get_contents('php://input'), $inputVariables );
                break;
        }

        if ( $this->request->originalMethod === 'POST' )
            $inputVariables = $_POST;

        return $inputVariables;
    }

    /**
     * Overrides HTTP request method with POST _method value
     *
     * @return void
     */
    protected function processMethodOverride()
    {
        if ( isset( $_SERVER['REQUEST_METHOD'] )
             && ( $_SERVER['REQUEST_METHOD'] === 'POST' ) )
        {
            if ( isset( $_POST['_method'] )
                 && in_array( $method = strtoupper( $_POST['_method'] ), array( 'GET', 'POST', 'PUT', 'DELETE' ) ) )
            {
                $this->request->originalMethod = $_SERVER['REQUEST_METHOD'];
                $_SERVER['REQUEST_METHOD'] = $method;
            }
        }
    }
}
