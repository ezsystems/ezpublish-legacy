<?php
/**
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
