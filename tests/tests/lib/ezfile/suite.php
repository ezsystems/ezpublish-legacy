<?php
/**
 * File containing the eZFileTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
