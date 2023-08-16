<?php
/**
 * File containing the eZStepSystemCheck class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZStepSystemCheck ezstep_system_check.php
  \brief The class eZStepSystemCheck does

*/

class eZStepSystemCheck extends eZStepInstaller
{
    public $OptionalResults;
    public $OptionalResult;
    /**
     * Constructor
     *
     * @param eZTemplate $tpl
     * @param eZHTTPTool $http
     * @param eZINI $ini
     * @param array $persistenceList
     */
    public function __construct( $tpl, $http, $ini, &$persistenceList )
    {
        parent::__construct( $tpl, $http, $ini, $persistenceList, 'system_check', 'System check' );
    }

    function processPostData()
    {
        $return = $this->init();
        if ( $this->Http->hasPostVariable( 'eZSetup_finetune_button' ) )
        {
            $this->PersistenceList['run_finetune'] = true;
        }
        return $return;
    }

    function init()
    {
        $criticalTests = eZSetupCriticalTests();
        $optionalTests = eZSetupOptionalTests();
        $testTable = eZSetupTestTable();

        $runResult = eZSetupRunTests( $criticalTests, 'eZSetup:init:system_check', $this->PersistenceList );
        $optionalRunResult = eZSetupRunTests( $optionalTests, 'eZSetup:init:system_check', $this->PersistenceList );
        $this->Results = $runResult['results'];
        $this->Result = $runResult['result'];
        $this->OptionalResults = $optionalRunResult['results'];
        $this->OptionalResult = $optionalRunResult['result'];
        $persistenceData = $runResult['persistence_list'];

        $testsRun = array();
        foreach ( $this->Results as $testResultItem )
        {
            $testsRun[$testResultItem[1]] = $testResultItem[0];
        }

        eZSetupMergePersistenceList( $this->PersistenceList, $persistenceData );

        $this->PersistenceList['tests_run'] = $testsRun;
        $this->PersistenceList['optional_tests_run'] = $testsRun;

        return ( $this->Result == EZ_SETUP_TEST_SUCCESS );
    }

    function display()
    {
        $this->Tpl->setVariable( 'test', array( 'result' => $this->Result,
                                                'results' => $this->Results ) );
        $this->Tpl->setVariable( 'optional_test', array( 'result' => $this->OptionalResult,
                                                         'results' => $this->OptionalResults ) );
        $this->Tpl->setVariable( 'persistence_data', $this->PersistenceList );
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/system_check.tpl" );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'System check' ),
                                        'url' => false ) );
        return $result;
    }

    function showMessage()
    {
        return false;
    }

    // Variables for storing results from tests
    public $Result = null;
    public $Results = null;
}

?>
