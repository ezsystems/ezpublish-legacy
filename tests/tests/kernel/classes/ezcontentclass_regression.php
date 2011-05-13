<?php
/**
 * File containing the eZContentClassRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZContentClassRegression extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZContentClass Regression Tests" );
    }

    public function setUp()
    {
        parent::setUp();

        $this->class = new ezpClass( 'eZContentClassRegression', 'eZContentClassRegression', '<name>' );
        $this->class->add( 'Name', 'name' );
        $this->class->store();
    }

    public function tearDown()
    {
        eZContentClassOperations::remove( $this->class->id );
        parent::tearDown();
    }

    /**
     * Test that saving a content class in DEFINED version status
     * correctly manipulate associated class groups
     *
     * @link http://issues.ez.no/16197
     */
    public function testContentClassStillInGroupAfterEdition()
    {
        $class = eZContentClass::fetch( $this->class->id );
        // This is logic contained in kernel/class/edit.php
        foreach ( eZContentClassClassGroup::fetchGroupList( $class->attribute( 'id' ),
                                                            eZContentClass::VERSION_STATUS_DEFINED )  as $classGroup )
        {
            eZContentClassClassGroup::create( $class->attribute( 'id' ),
                                              eZContentClass::VERSION_STATUS_TEMPORARY,
                                              $classGroup->attribute( 'group_id' ),
                                              $classGroup->attribute( 'group_name' ) )
                ->store();
        }

        $attributes = $class->fetchAttributes();
        $class->setAttribute( 'version', eZContentClass::VERSION_STATUS_TEMPORARY );
        $class->NameList->setHasDirtyData();

        foreach ( $attributes as $attribute )
        {
            $attribute->setAttribute( 'version', eZContentClass::VERSION_STATUS_TEMPORARY );
            if ( $dataType = $attribute->dataType() )
                $dataType->initializeClassAttribute( $attribute );
        }

        $class->store( $attributes );
        $db = eZDB::instance();
        $db->begin();
        $class->storeVersioned( $attributes, eZContentClass::VERSION_STATUS_DEFINED );
        $db->commit();

        $this->assertTrue( eZContentClassClassGroup::classInGroup( $class->attribute( 'id' ),
                                                                   eZContentClass::VERSION_STATUS_DEFINED,
                                                                   1 ) );
    }

}
?>
