<?php
/**
 * File containing eZContentOperationCollectionTest class
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
