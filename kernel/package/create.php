<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];

$http = eZHTTPTool::instance();

$creator = false;
$initializeStep = false;
if ( $module->isCurrentAction( 'CreatePackage' ) )
{
    $creatorID = $module->actionParameter( 'CreatorItemID' );
    if ( $creatorID )
    {
        $creator = eZPackageCreationHandler::instance( $creatorID );
        $persistentData = array();
        $http->setSessionVariable( 'eZPackageCreatorData' . $creatorID, $persistentData );
        $initializeStep = true;
        $package = false;
        if ( isset( $persistentData['package_name'] ) )
            $package = eZPackage::fetch( $persistentData['package_name'] );
        $creator->generateStepMap( $package, $persistentData );
    }
}
else if ( $module->isCurrentAction( 'PackageStep' ) )
{
    if ( $module->hasActionParameter( 'CreatorItemID' ) )
    {
        $creatorID = $module->actionParameter( 'CreatorItemID' );
        $creator = eZPackageCreationHandler::instance( $creatorID );
        if ( $http->hasSessionVariable( 'eZPackageCreatorData' . $creatorID ) )
            $persistentData = $http->sessionVariable( 'eZPackageCreatorData' . $creatorID );
        else
            $persistentData = array();
        $package = false;
        if ( isset( $persistentData['package_name'] ) )
            $package = eZPackage::fetch( $persistentData['package_name'] );
        $creator->generateStepMap( $package, $persistentData );
    }
}

$tpl = eZTemplate::factory();

$templateName = 'design:package/create.tpl';
if ( $creator )
{
    $currentStepID = false;
    if ( $module->hasActionParameter( 'CreatorStepID' ) )
        $currentStepID = $module->actionParameter( 'CreatorStepID' );
    $steps =& $creator->stepMap();
    if ( !isset( $steps['map'][$currentStepID] ) )
        $currentStepID = $steps['first']['id'];
    $errorList = array();
    $hasAdvanced = false;

    $lastStepID = $currentStepID;
    if ( $module->hasActionParameter( 'NextStep' ) )
    {
        $hasAdvanced = true;
        $currentStepID = $creator->validateStep( $package, $http, $currentStepID, $steps, $persistentData, $errorList );
        if ( $currentStepID != $lastStepID )
        {
            $lastStep =& $steps['map'][$lastStepID];
            $creator->commitStep( $package, $http, $lastStep, $persistentData, $tpl );
            $initializeStep = true;
        }
    }

    if ( $currentStepID )
    {
        $currentStep =& $steps['map'][$currentStepID];

        $stepTemplate = $creator->stepTemplate( $currentStep );
        $stepTemplateName = $stepTemplate['name'];
        $stepTemplateDir = $stepTemplate['dir'];

        if ( $initializeStep )
            $creator->initializeStep( $package, $http, $currentStep, $persistentData, $tpl );

        $creator->loadStep( $package, $http, $currentStepID, $persistentData, $tpl, $module );
        if ( $package )
            $persistentData['package_name'] = $package->attribute( 'name' );

        $http->setSessionVariable( 'eZPackageCreatorData' . $creatorID, $persistentData );

        $tpl->setVariable( 'creator', $creator );
        $tpl->setVariable( 'current_step', $currentStep );
        $tpl->setVariable( 'persistent_data', $persistentData );
        $tpl->setVariable( 'error_list', $errorList );
        $tpl->setVariable( 'package', $package );

        $templateName = "design:package/$stepTemplateDir/$stepTemplateName";
    }
    else
    {
        $creator->finalize( $package, $http, $persistentData );
        $package->setAttribute( 'is_active', true );
        $http->removeSessionVariable( 'eZPackageCreatorData' . $creatorID );
        if ( $package )
            return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
        else
            return $module->redirectToView( 'list' );
    }
}
else
{
    $creators =& eZPackageCreationHandler::creatorList( true );

    $tpl->setVariable( 'creator_list', $creators );
}

$Result = array();
$Result['content'] = $tpl->fetch( $templateName );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/package', 'Create package' ) ) );
?>
