<?php
/**
 * File containing the eZLocaleTestSuite class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
