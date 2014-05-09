<?php
/**
 * File containing eZObjectRelationListDatatypeRegression class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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

    public function setUp()
    {
        parent::setUp();
        $this->xxx = eZContentLanguage::addLanguage( 'xxx-XX', 'XXXX' );
        ezpINIHelper::setINISetting(
            'site.ini', 'RegionalSettings',
            'SiteLanguageList', array( 'xxx-XX', 'eng-GB' )
        );
        eZContentLanguage::clearPrioritizedLanguages();
    }

    public function tearDown()
    {
        $this->xxx->removeThis();
        ezpINIHelper::restoreINISettings();
        parent::tearDown();
    }

    /**
     * @group issue18249
     */
    public function testIssue18249()
    {

        // setup a class C1 with objectrelationlist and pattern name
        $classIdentifier = strtolower( __FUNCTION__ );
        $class1 = new ezpClass(
            __FUNCTION__, $classIdentifier, '<test_name><test_relation>'
        );
        $class1->add( 'Test name', 'test_name', 'ezstring' );
        $class1->add( 'Test Relation', 'test_relation', 'ezobjectrelationlist' );
        $class1->store();

        $o1 = new ezpObject( 'article', 2, 14, 1, 'eng-GB' );
        $o1->title = 'Test_ENG';
        $o1->publish();
        $o1->addTranslation( 'xxx-XX', array( 'title' => 'Test_XXX' ) );

        $o2 = new ezpObject( $classIdentifier, 2, 14, 1, 'xxx-XX' );
        $o2->test_name = 'name_';
        $o2->test_relation = array( $o1->attribute( 'id' ) );
        $o2->publish();

        // test O2's name
        $this->assertEquals( 'name_Test_XXX', $o2->name );
        $o1->remove();
        $o2->remove();
    }
}
?>
