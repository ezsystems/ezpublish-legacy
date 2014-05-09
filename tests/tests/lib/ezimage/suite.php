<?php
/**
 * File containing the eZImageTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZImageTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZImage Test Suite" );

        $this->addTestSuite( 'eZImageManagerTest' );
        $this->addTestSuite( 'eZImageShellHandlerTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
