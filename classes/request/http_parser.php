<?php
/**
 * File containing the ezpRestHttpRequestParser class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
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
        $this->request->variables = array();
        $this->request->get = $_GET;
        $this->request->post = $_POST;
    }

}
