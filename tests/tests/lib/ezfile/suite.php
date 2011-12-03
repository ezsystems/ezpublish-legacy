<?php
/**
 * File containing the eZFileTestSuite class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZFileTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZFile Test Suite" );
        $this->addTestSuite( 'eZDirTestInsideRoot' );
        $this->addTestSuite( 'eZDirTestOutsideRoot' );
        $this->addTestSuite( 'eZFileDownloadTest' );
        $this->addTestSuite( 'eZFileRenameTest' );
    }

    public static function suite()
    {
        return new self();
    }

}

?>
