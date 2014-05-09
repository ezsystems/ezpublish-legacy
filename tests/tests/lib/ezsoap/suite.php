<?php
/**
 * File containing the eZSOAPTestSuite class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZSOAPTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();

        $this->setName( "eZSOAP Test Suite" );
        $this->addTestSuite( 'eZSOAPClientTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
