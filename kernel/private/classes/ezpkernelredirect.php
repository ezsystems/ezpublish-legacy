<?php
/**
 * File containing the ezpKernelRedirect class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

/**
 * Struct containing information on HTTP redirection.
 */
class ezpKernelRedirect extends ezpKernelResult
{
    /**
     * Target URL to redirect to.
     *
     * @var string
     */
    private $targetUrl;

    /**
     * Redirection status code (e.g. 302).
     *
     * @var int
     */
    private $statusCode;

    public function __construct( $url, $redirectStatus = null, $content = null )
    {
        $this->targetUrl = $url ?: '/';
        $this->statusCode = $redirectStatus ? (int)substr( $redirectStatus, 0, 3 ) : 302;
        parent::__construct( $content, array( 'status' => $redirectStatus ) );
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }
}
