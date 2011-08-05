<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

function stateEditPostFetch( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage, &$validation )
{
}

function stateEditPreCommit( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage )
{
}

function stateEditActionCheck( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage )
{
    if ( $module->isCurrentAction( 'StateEdit' ) )
    {
        $http = eZHTTPTool::instance();
        if ( $http->hasPostVariable( 'SelectedStateIDList' ) )
        {
            $selectedStateIDList = $http->postVariable( 'SelectedStateIDList' );
            $objectID = $object->attribute( 'id' );

            if ( eZOperationHandler::operationIsAvailable( 'content_updateobjectstate' ) )
            {
                $operationResult = eZOperationHandler::execute( 'content', 'updateobjectstate',
                                                                array( 'object_id'     => $objectID,
                                                                       'state_id_list' => $selectedStateIDList ) );
            }
            else
            {
                eZContentOperationCollection::updateObjectState( $objectID, $selectedStateIDList );
            }
            $module->redirectToView( 'edit', array( $object->attribute( 'id' ), $editVersion, $editLanguage, $fromLanguage ) );
        }
    }
}

function stateEditPreTemplate( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $tpl )
{
}

function initializeStateEdit( $module )
{
    $module->addHook( 'post_fetch', 'stateEditPostFetch' );
    $module->addHook( 'pre_commit', 'stateEditPreCommit' );
    $module->addHook( 'action_check', 'stateEditActionCheck' );
    $module->addHook( 'pre_template', 'stateEditPreTemplate' );
}

?>
