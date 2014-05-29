<?php
/**
 * This file is part of the eZ Publish Legacy package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributd with this source code.
 * @version //autogentag//
 */ 

class eZDFSFileHandlerDFSBackendFactoryTest extends ezpTestCase
{
    /**
     * Tests that the default DFS Backend gets build normally.
     */
    public function testBuildDFSBackend()
    {
        self::assertInstanceOf(
            'eZDFSFileHandlerDFSBackend',
            eZDFSFileHandlerBackendFactory::build()
        );
    }
}
