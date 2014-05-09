<?php
/**
 * File containing the eZLocaleTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 *
 */

class eZLocaleTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZLocale Test Suite" );
        $this->addTestSuite( 'eZLocaleRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}
?>
