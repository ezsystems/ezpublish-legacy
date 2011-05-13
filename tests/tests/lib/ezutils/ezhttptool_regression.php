<?php
/**
 * File containing the eZHTTPToolRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZHTTPToolRegression extends ezpTestCase
{
    /**
     * If you send an HTTP request using eZHTTPTool::sendHTTPRequest( ) to an
     * URL with a domain name containing a dash ( -), it's misunderpreted and
     * doesn't get executed.
     *
     * @link http://issues.ez.no/10651
     */
    public function testSendRequestContainingDashes()
    {
        self::markTestSkipped( "Test disabled pending update." );
        $url = 'http://php-og.mgdm.net/';

        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            eZHTTPTool::sendHTTPRequest( $url, 80, false, 'eZ Publish', false )
        );
    }
}

?>
