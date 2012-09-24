<?php
/**
 * File containing various wrapper classes used by the WebDAV tests.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class ezcWebdavTransportWrapper extends ezcWebdavKonquerorCompatibleTransport//ezcWebdavTransport
{
    protected function retrieveBody()
    {
        return isset( $GLOBALS['ezc_post_body'] ) ? $GLOBALS['ezc_post_body'] : null;
    }

    protected function sendResponse( ezcWebdavOutputResult $output )
    {
        $response = '';
        // Sends HTTP headers
        foreach ( $output->headers as $name => $content )
        {
            $content   = is_array( $content ) ? $content : array( $content );
            $response .= "{$name}: ";
            foreach ( $content as $contentLine )
            {
                $response .= "{$contentLine}\n";
            }
        }

        // Send HTTP status code
        $response .= "$output->status\n\n";

        $response .= "$output->body\n";

        // Content-Length header automatically send
        $GLOBALS['ezc_response_body'] = $response;
    }
}

class ezcWebdavServerConfigurationManagerWrapper extends ezcWebdavServerConfigurationManager
{
    public function __construct()
    {
        $config = new ezcWebdavServerConfiguration();
        $config->transportClass = 'ezcWebdavTransportWrapper';
        $config->pathFactory = new ezcWebdavBasicPathFactory( 'http://webdav.ezp' );
        $this[] = $config;
    }
}

?>
