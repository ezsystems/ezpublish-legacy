<?php
/**
 * File containing eZContentOperationCollectionTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZContentOperationCollectionTest extends ezpDatabaseTestCase
{
    public function __construct( $name = NULL, array $data = array(), $dataName = '' )
    {
        parent::__construct( $name, $data, $dataName );
        $this->setName( "eZContentOperationCollection Tests" );
    }

    /**
     * Fatal Error when calling eZContentOperationCollection::removeOldNodes()
     * with inexistant object/object version
     *
     * @link http://issues.ez.no/22232
     */
    public function testRemoveOldNodes()
    {
        eZContentOperationCollection::removeOldNodes( 1234, 1234 );
    }
}
