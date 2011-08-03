<?php
/**
 * File containing the eZKernelTestSuite class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZKernelTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish Kernel Test Suite" );

        $this->addTestSuite( 'eZDatatypeTestSuite' );
        $this->addTestSuite( 'eZKernelContentTestSuite' );

        $this->addTestSuite( 'eZContentObjectRegression' );
        $this->addTestSuite( 'eZContentObjectTest' );
        $this->addTestSuite( 'eZContentObjectTest2' );
        $this->addTestSuite( 'eZContentObjectTreeNodeRegression' );
        $this->addTestSuite( 'eZContentObjectTreeNodeTest' );
        $this->addTestSuite( 'eZContentFunctionCollectionRegression' );
        $this->addTestSuite( 'eZContentFunctionCollectionTest' );
        $this->addTestSuite( 'eZURLAliasMLTest' );
        $this->addTestSuite( 'eZURLAliasMLRegression' );
        $this->addTestSuite( 'eZApproveTypeRegression' );
        $this->addTestSuite( 'eZContentObjectStateTest' );
        $this->addTestSuite( 'eZContentObjectStateGroupTest' );
        $this->addTestSuite( 'eZWorkflowEventRegression' );
        $this->addTestSuite( 'eZURLWildcardTest' );
        $this->addTestSuite( 'eZURLWildcardRegression' );
        $this->addTestSuite( 'eZUserAuthenticationTest' );

        // This test suite is commented out until it will be fixed to work on any machine
        // $this->addTestSuite( 'eZWebDAVBackendContentRegressionTest' );

        $this->addTestSuite( 'eZSiteInstallerTest' );
        $this->addTestSuite( 'eZProductCollectionTest' );
        $this->addTestSuite( 'eZProductCollectionItemTest' );
        $this->addTestSuite( 'eZProductCollectionItemOptionTest' );
        $this->addTestSuite( 'eZPolicyTest' );
        $this->addTestSuite( 'eZRoleTest' );
        $this->addTestSuite( 'eZUserDiscountRuleTest' );
        $this->addTestSuite( 'eZSubtreeNotificationRuleTest' );
        $this->addTestSuite( 'eZSubtreeNotificationRuleRegression' );
        $this->addTestSuite( 'eZContentLanguageRegression' );
        $this->addTestSuite( 'eZContentOperationCollectionRegression' );
        $this->addTestSuite( 'eZContentClassTest' );
        $this->addTestSuite( 'eZContentClassAttributeTest' );
        $this->addTestSuite( 'eZPackageRegression' );
        $this->addTestSuite( 'eZContentFunctionsTest' );
        $this->addTestSuite( 'eZBinaryFileTypeRegression' );
        $this->addTestSuite( 'eZContentClassRegression' );
        $this->addTestSuite( 'eZURLOperatorTest' );

        $this->addTestSuite( 'ezpTopologicalSortTest' );
        $this->addTestSuite( 'eZExtensionWithOrderingTest' );
        $this->addTestSuite( 'eZExtensionWithoutOrderingTest' );
        $this->addTestSuite( 'ezpExtensionTest' );
        $this->addTestSuite( 'ezpAutoloadGeneratorTest' );

        $this->addTestSuite( 'eZRSSExportTest' );
        $this->addTestSuite( 'ezpEventTest' );

        $this->addTestSuite( 'eZSiteAccess_Test' );
        $this->addTestSuite( 'eZSiteDataTest' );
        $this->addTestSuite( 'eZPendingActionsTest' );
        $this->addTestSuite( 'eZSectionTest' );
        $this->addTestSuite( 'eZNodeAssignmentTest' );

        $this->addTestSuite( 'eZClusterTestSuite' );

        $this->addTestSuite( 'ezpApiTestSuite' );
        $this->addTestSuite( 'ezpRestTestSuite' );

        $this->addTestSuite( 'eZURLTest' );

        $this->addTestSuite( 'eZOrderRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
