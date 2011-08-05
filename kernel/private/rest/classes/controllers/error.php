<?php
/**
 * File containing the ezpRestErrorController class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * This controller deals with error situations arising in the REST layer.
 */
class ezpRestErrorController extends ezcMvcController
{
    /**
     * Default method, currently used for fatal error handling
     * @return ezcMvcResult
     */
    public function doShow()
    {
        if ( ($this->exception instanceof ezcMvcRouteNotFoundException) || ($this->exception instanceof ezpContentNotFoundException ) )
        {
            // we want to return a 404 to the user
            $result = new ezcMvcResult;
            $result->status = new ezpRestHttpResponse( ezpHttpResponseCodes::NOT_FOUND, "Not Found" );
            return $result;
        }

        else if ( $this->exception instanceof ezpOauthBadRequestException )
        {
            $result = new ezcMvcResult;
            $result->status = new ezpRestOauthErrorStatus( $this->exception->errorType, $this->exception->getMessage() );
            $result->variables['message'] = $this->exception->getMessage();
            return $result;
        }
        else if ( $this->exception instanceof ezpOauthRequiredException )
        {
            $result = new ezcMvcResult;
            $result->status = new ezpOauthRequired( "eZ Publish REST", $this->exception->errorType, $this->exception->getMessage() );
            $result->variables['message'] = $this->exception->getMessage();
            return $result;
        }
        else if ( $this->exception instanceof ezpContentAccessDeniedException )
        {
            $result = new ezcMvcResult;
            $result->variables['message'] = $this->exception->getMessage();
            $result->status = new ezpRestHttpResponse( ezpHttpResponseCodes::FORBIDDEN, $this->exception->getMessage() );
            return $result;
        }

        $result = new ezcMvcResult;
        $result->variables['message'] = $this->exception->getMessage();
        $result->status = new ezpRestHttpResponse( ezpHttpResponseCodes::SERVER_ERROR, $this->exception->getMessage() );
        return $result;
    }
}
?>
