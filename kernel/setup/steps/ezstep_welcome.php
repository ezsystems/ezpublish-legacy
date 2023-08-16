<?php
/**
 * File containing the eZStepWelcome class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZStepWelcome ezstep_welcome.php
  \brief The class eZStepWelcome does

*/
class eZStepWelcome extends eZStepInstaller
{

    public $OptionalResults;
    public $OptionalResult;
    public $Results;
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
        parent::__construct( $tpl, $http, $ini, $persistenceList, 'welcome', 'Welcome' );
    }

    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetup_finetune_button' ) )
        {
            $this->PersistenceList['run_finetune'] = true;
        }

        if ( $this->Http->hasPostVariable( 'eZSetupWizardLanguage' ) )
        {
            $wizardLanguage = $this->Http->postVariable( 'eZSetupWizardLanguage' );
            $this->PersistenceList['setup_wizard'] = array( 'language' => $wizardLanguage );

            eZTranslatorManager::setActiveTranslation( $wizardLanguage );
        }

        return true;
    }

    function init()
    {
        $optionalTests = eZSetupOptionalTests();
        $testTable = eZSetupTestTable();

        $optionalRunResult = eZSetupRunTests( $optionalTests, 'eZSetup:init:system_check', $this->PersistenceList );
        $this->OptionalResults = $optionalRunResult['results'];
        $this->OptionalResult = $optionalRunResult['result'];

        $testsRun = array();
        if ( isset( $this->Results ) && is_array( $this->Results ) )
        {
            foreach ( $this->Results as $testResultItem )
            {
                $testsRun[$testResultItem[1]] = $testResultItem[0];
            }
        }

        $this->PersistenceList['tests_run'] = $testsRun;
        $this->PersistenceList['optional_tests_run'] = $testsRun;

        if ( $this->hasKickstartData() )
        {
            return $this->kickstartContinueNextStep();
        }

        return false; // Always show welcome message
    }

    function display()
    {
        $result = array();

        $languages = false;
        $defaultLanguage = false;
        $defaultExtraLanguages = false;

        eZSetupLanguageList( $languages, $defaultLanguage, $defaultExtraLanguages );

        eZTranslatorManager::setActiveTranslation( $defaultLanguage, false );

        $this->Tpl->setVariable( 'language_list', $languages );
        $this->Tpl->setVariable( 'primary_language', $defaultLanguage );
        $this->Tpl->setVariable( 'optional_test', array( 'result' => $this->OptionalResult,
                                                         'results' => $this->OptionalResults ) );
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/welcome.tpl' );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Welcome to eZ Publish' ),
                                    'url' => false ) );

        return $result;
    }

    function showMessage()
    {
        return true;
    }

}

?>
