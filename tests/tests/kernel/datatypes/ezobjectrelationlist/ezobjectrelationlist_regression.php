<?php
/**
 * File containing eZContentOperationDeleteRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 *  object relation list data tupe regression test
 */
class eZObjectRelationListDatatypeRegression extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZObjectRelationList Regression Tests" );
    }

    public function testIssue18249()
    {
        // setup a class C1 with objectrelationlist and pattern name
        $class1 = new ezpClass( 'TestClass1', 'testclass1', '<test_name><test_relation>' );
        $class1->add( 'Test name', 'test_name', 'ezstring' );
        $class1->add( 'Test Relation', 'test_relation', 'ezobjectrelationlist' );
        $class1->store();
        // create an object O1 with eng and nor
        $o1 = new ezpObject( 'article', false, 14, 1, 'nor-NO' );
        $o1->title = 'Test_NOR';
        $o1->publish();
        $o1->addTranslation( 'nor-NO', array( 'title' => 'Test_NOR' ) );
        // create an object O2 based on C1 with Nor
        $o2 = new ezpObject( 'testclass1', false, 14, 1, 'nor-NO' );
        $o2->test_name = 'name_';
        $o2->test_relation = array( $o1->attribute( 'id' ) );
        $o2->publish();
        // test O2's name
        $this->assert( 'name_Test_NOR', $o2->name );
    }
}
?>
