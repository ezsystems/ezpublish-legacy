<?php
/**
 * File containing the eZPackageRegression class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZPackageRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZPackage Regression Tests" );
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
    * Regression test for issue #15223
    * Content object name/url of imported content classes aren't generated correctly
    *
    * @url http://issues.ez.no/15223
    *
    * @outline
    * 1) Expire and force generation of class attribute cache
    * 2) Load a test package
    * 3) Install the package
    * 4) Publish an object of the imported class
    * 5) The object name / url alias shouldn't be the expected one
    **/
    public function testIssue15223()
    {
        $adminUser = eZUser::fetchByName( 'admin' );
        $previousUser = eZUser::currentUser();
        eZUser::setCurrentlyLoggedInUser( $adminUser, $adminUser->attribute( 'contentobject_id' ) );

        // 1) Expire and force generation of class attribute cache
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'class-identifier-cache', time() - 1 );
        $handler->store();
        eZContentClassAttribute::classAttributeIdentifierByID( 1 );

        // 1) Load a test package
        $packageName = 'ezpackage_regression_testIssue15223.ezpkg';
        $packageFilename = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $packageName;
        $package = eZPackage::import( $packageFilename, $packageName );
        if ( !$package instanceof eZPackage )
        {
            $package = eZPackage::fetch( $packageName );
            if ( $package instanceof $package )
            {
                $package->remove();
                $package = eZPackage::import( $packageFilename, $packageName );
            }
            else
            {
                $this->fail( "An error occured loading the package '$packageFilename'" );
            }
        }

        // 2) Install the package
        $installParameters = array( 'site_access_map' => array( '*' => false ),
                                    'top_nodes_map' => array( '*' => 2 ),
                                    'design_map' => array( '*' => false ),
                                    'restore_dates' => true,
                                    'user_id' => $adminUser->attribute( 'contentobject_id' ),
                                    'non-interactive' => true,
                                    'language_map' => $package->defaultLanguageMap() );
        $result = $package->install( $installParameters );

        // 3) Publish an object of the imported class
        $object = new ezpObject( 'test_issue_15523', 2, $adminUser->attribute( 'contentobject_id' ), 1 );
        $object->myname = __METHOD__;
        $object->myothername = __METHOD__;
        $publishedObjectID = $object->publish();
        unset( $object );

        // 4) Test data from the publish object
        $publishedNodeArray = eZContentObjectTreeNode::fetchByContentObjectID( $publishedObjectID );
        if ( count( $publishedNodeArray ) != 1 )
        {
            $this->fail( "An error occured fetching node for object #$publishedObjectID" );
        }
        $publishedNode = $publishedNodeArray[0];
        if ( !$publishedNode instanceof eZContentObjectTreeNode )
        {
            $this->fail( "An error occured fetching node for object #$publishedObjectID" );
        }
        else
        {
            $this->assertEquals( "eZPackageRegression::testIssue15223", $publishedNode->attribute( 'name' ) );
            $this->assertEquals( "eZPackageRegression-testIssue15223",  $publishedNode->attribute( 'url_alias' ) );
        }

        // Remove the installed package & restore the logged in user
        $package->remove();
        eZUser::setCurrentlyLoggedInUser( $previousUser, $previousUser->attribute( 'contentobject_id' ) );
    }
}

?>