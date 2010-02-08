<?php
//
// Created on: <20-mar-2005 13:37:23 jk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

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

    require_once( "kernel/common/template.php" );

    $tpl = templateInit();

    $tpl->setVariable( 'object_id', $objectID );
    $tpl->setVariable( 'object', $object );
    $tpl->setVariable( 'node_id', $nodeID );
    $tpl->setVariable( 'language_code', $languageCode );
    $tpl->setVariable( 'languages', $languages );
    $tpl->setVariable( 'view_mode', $viewMode );

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:content/removetranslation.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'kernel/content', 'Remove translation' ) ) );

    return;
}

return $module->redirectToView( 'view', array( 'full', 2 ) );

?>
