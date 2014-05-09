<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

function sectionEditPostFetch( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage, &$validation )
{
}

function sectionEditPreCommit( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage )
{
}

function sectionEditActionCheck( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage )
{
    if ( !$module->isCurrentAction( 'SectionEdit' ) )
        return;

    $http = eZHTTPTool::instance();
    if ( !$http->hasPostVariable( 'SelectedSectionId' ) )
        return;

    $selectedSection = eZSection::fetch( (int)$http->postVariable( 'SelectedSectionId' ) );
    if ( !$selectedSection instanceof eZSection )
        return;

    $selectedSection->applyTo( $object );
                    eZContentCacheManager::clearContentCacheIfNeeded( $object->attribute( 'id' ) );
    $module->redirectToView( 'edit', array( $object->attribute( 'id' ), $editVersion, $editLanguage, $fromLanguage ) );
}

function sectionEditPreTemplate( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $tpl )
{
}

function initializeSectionEdit( $module )
{
    $module->addHook( 'post_fetch', 'sectionEditPostFetch' );
    $module->addHook( 'pre_commit', 'sectionEditPreCommit' );
    $module->addHook( 'action_check', 'sectionEditActionCheck' );
    $module->addHook( 'pre_template', 'sectionEditPreTemplate' );
}

?>
