<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];

if ( !$module->hasActionParameter( 'NodeID' ) )
{
    eZDebug::writeError( 'Missing NodeID parameter for action ' . $module->currentAction(),
                         'content/translation' );
    return $module->redirectToView( 'view', array( 'full', 2 ) );
}

$nodeID = $module->actionParameter( 'NodeID' );

if ( !$module->hasActionParameter( 'LanguageCode' ) )
{
    eZDebug::writeError( 'Missing LanguageCode parameter for action ' . $module->currentAction(),
                         'content/translation' );
    return $module->redirectToView( 'view', array( 'full', 2 ) );
}

$languageCode = $module->actionParameter( 'LanguageCode' );

$viewMode = 'full';
if ( !$module->hasActionParameter( 'ViewMode' ) )
{
    $viewMode = $module->actionParameter( 'ViewMode' );
}

if ( $module->isCurrentAction( 'Cancel' ) )
{
    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}

if ( !$module->hasActionParameter( 'ObjectID' ) )
{
    eZDebug::writeError( 'Missing ObjectID parameter for action ' . $module->currentAction(),
                         'content/translation' );
    return $module->redirectToView( 'view', array( 'full', 2 ) );
}
$objectID = $module->actionParameter( 'ObjectID' );


$object = eZContentObject::fetch( $objectID );

if ( !$object )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $module->isCurrentAction( 'UpdateInitialLanguage' ) )
{
    if ( $module->hasActionParameter( 'InitialLanguageID' ) )
    {
        $newInitialLanguageID = $module->actionParameter( 'InitialLanguageID' );

        if ( !$object->canEdit() )
        {
            return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );
        }

        if ( eZOperationHandler::operationIsAvailable( 'content_updateinitiallanguage' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content', 'updateinitiallanguage',
                                                            array( 'object_id'               => $objectID,
                                                                   'new_initial_language_id' => $newInitialLanguageID,
                                                                   // note : the $nodeID parameter is ignored here but is
                                                                   // provided for events that need it
                                                                   'node_id'                 => $nodeID ) );
        }
        else
        {
            eZContentOperationCollection::updateInitialLanguage( $objectID, $newInitialLanguageID );
        }
    }

    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'UpdateAlwaysAvailable' ) )
{
    if ( !$object->canEdit() )
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );
    }

    $newAlwaysAvailable = $module->hasActionParameter( 'AlwaysAvailable' );

    if ( eZOperationHandler::operationIsAvailable( 'content_updatealwaysavailable' ) )
    {
        $operationResult = eZOperationHandler::execute( 'content', 'updatealwaysavailable',
                                                        array( 'object_id'            => $objectID,
                                                               'new_always_available' => $newAlwaysAvailable,
                                                               // note : the $nodeID parameter is ignored here but is
                                                               // provided for events that need it
                                                               'node_id'              => $nodeID ) );
    }
    else
    {
        eZContentOperationCollection::updateAlwaysAvailable( $objectID, $newAlwaysAvailable );
    }

    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'RemoveTranslation' ) )
{
    if ( !$module->hasActionParameter( 'LanguageID' ) )
    {
        return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
    }

    $languageIDArray = $module->actionParameter( 'LanguageID' );

    if ( $module->hasActionParameter( 'ConfirmRemoval' ) && $module->actionParameter( 'ConfirmRemoval' ) )
    {
        if ( eZOperationHandler::operationIsAvailable( 'content_removetranslation' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content', 'removetranslation',
                                                            array( 'object_id'        => $objectID,
                                                                   'language_id_list' => $languageIDArray,
                                                                   // note : the $nodeID parameter is ignored here but is
                                                                   // provided for events that need it
                                                                   'node_id'          => $nodeID ) );

        }
        else
        {
            eZContentOperationCollection::removeTranslation( $objectID, $languageIDArray );
        }

        return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
    }

    $languages = array();
    foreach( $languageIDArray as $languageID )
    {
        $language = eZContentLanguage::fetch( $languageID );
        if ( $language )
        {
            $languages[] = $language;
        }
    }

    if ( !$languages )
    {
        return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
    }

    $tpl = eZTemplate::factory();

    $tpl->setVariable( 'object_id', $objectID );
    $tpl->setVariable( 'object', $object );
    $tpl->setVariable( 'node_id', $nodeID );
    $tpl->setVariable( 'language_code', $languageCode );
    $tpl->setVariable( 'languages', $languages );
    $tpl->setVariable( 'view_mode', $viewMode );

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:content/removetranslation.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezpI18n::tr( 'kernel/content', 'Remove translation' ) ) );

    return;
}

return $module->redirectToView( 'view', array( 'full', 2 ) );

?>
