<?php
/**
 * File containing the eZKernelTestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZKernelTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish Kernel Test Suite" );

        $this->addTestSuite( 'eZContentObjectRegression' );
        $this->addTestSuite( 'eZContentObjectTest' );
        $this->addTestSuite( 'eZContentObjectTest2' );
        $this->addTestSuite( 'eZContentObjectTreeNodeRegression' );
        $this->addTestSuite( 'eZContentObjectTreeNodeTest' );
        $this->addTestSuite( 'eZContentFunctionCollectionRegression' );
        $this->addTestSuite( 'eZContentFunctionCollectionTest' );
        $this->addTestSuite( 'eZURLAliasMlTest' );
        $this->addTestSuite( 'eZURLAliasMlRegression' );
        $this->addTestSuite( 'eZURLTypeRegression' );
        $this->addTestSuite( 'eZXMLTextRegression' );
        $this->addTestSuite( 'eZApproveTypeRegression' );
        $this->addTestSuite( 'eZMultiPriceTypeRegression' );
        $this->addTestSuite( 'eZContentObjectStateTest' );
        $this->addTestSuite( 'eZContentObjectStateGroupTest' );
        $this->addTestSuite( 'eZWorkflowEventRegression' );
        $this->addTestSuite( 'eZURLWildcardTest' );
        $this->addTestSuite( 'eZUserAuthenticationTest' );

        // This test suite is commented out until it will be fixed to work on any machine
        // $this->addTestSuite( 'eZWebDAVBackendContentRegressionTest' );

        $this->addTestSuite( 'eZLDAPUserTest' );
        $this->addTestSuite( 'eZTextFileUserTest' );
        $this->addTestSuite( 'eZUserTest' );
        $this->addTestSuite( 'eZSiteInstallerTest' );
        $this->addTestSuite( 'eZCountryTypeTest' );
        $this->addTestSuite( 'eZProductCollectionTest' );
        $this->addTestSuite( 'eZProductCollectionItemTest' );
        $this->addTestSuite( 'eZProductCollectionItemOptionTest' );
        $this->addTestSuite( 'eZPolicyTest' );
        $this->addTestSuite( 'eZRoleTest' );
        $this->addTestSuite( 'eZUserDiscountRuleTest' );
        $this->addTestSuite( 'eZSubtreeNotificationRuleTest' );
        $this->addTestSuite( 'eZSubtreeNotificationRuleRegression' );
        $this->addTestSuite( 'eZImageAliasHandlerRegression' );
        $this->addTestSuite( 'eZContentLanguageRegression' );
        $this->addTestSuite( 'eZContentOperationCollectionRegression' );
        $this->addTestSuite( 'eZContentClassTest' );
        $this->addTestSuite( 'eZContentClassAttributeTest' );
        $this->addTestSuite( 'eZPackageRegression' );
        $this->addTestSuite( 'eZContentFunctionsTest' );
        $this->addTestSuite( 'eZBinaryFileTypeRegression' );
        $this->addTestSuite( 'eZMediaTypeRegression' );
        $this->addTestSuite( 'eZImageTypeRegression' );
        $this->addTestSuite( 'eZXHTMLXMLOutputRegression' );
        $this->addTestSuite( 'eZContentClassRegression' );
        $this->addTestSuite( 'eZImageFileRegression' );
        $this->addTestSuite( 'eZURLOperatorTest' );
        $this->addTestSuite( 'eZUserTypeRegression' );

        $this->addTestSuite( 'ezpTopologicalSortTest' );
        $this->addTestSuite( 'eZExtensionWithOrderingTest' );
        $this->addTestSuite( 'eZExtensionWithoutOrderingTest' );
        $this->addTestSuite( 'ezpExtensionTest' );

        $this->addTestSuite( 'eZRSSExportTest' );

        $this->addTestSuite( 'eZSiteAccess_Test' );
        $this->addTestSuite( 'eZSiteDataTest' );
        $this->addTestSuite( 'eZPendingActionsTest' );
        $this->addTestSuite( 'eZSectionTest' );
        $this->addTestSuite( 'eZNodeAssignmentTest' );

        $this->addTestSuite( 'eZClusterTestSuite' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
