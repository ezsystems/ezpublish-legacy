<?php
//
// Created on: <22-Apr-2002 15:41:30 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );

include_once( 'kernel/common/template.php' );
include_once( "lib/ezutils/classes/ezini.php" );

$tpl =& templateInit();

$ObjectID = $Params['ObjectID'];
$EditVersion = $Params['EditVersion'];
$EditLanguage = $Params['EditLanguage'];

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$object =& eZContentObject::fetch( $ObjectID );
$editWarning = false;

$canEdit = false;
$canRemove = false;

if ( $object === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$object->attribute( 'can_read' ) )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( $object->attribute( 'can_edit' ) )
    $canEdit = true;

if ( $object->attribute( 'can_remove' ) )
    $canRemove = true;
$http =& eZHTTPTool::instance();

if ( $http->hasSessionVariable( 'ExcessVersionHistoryLimit' ) )
{
    $excessLimit = $http->sessionVariable( 'ExcessVersionHistoryLimit' );
    if ( $excessLimit )
        $editWarning = 3;
    $http->removeSessionVariable( 'ExcessVersionHistoryLimit' );
}

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
    if ( !$canEdit )
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
        foreach ( $deleteIDArray as $deleteID )
        {
            $version =& eZContentObjectVersion::fetch( $deleteID );
            if ( $version != null )
                $version->remove();
        }
    }
}

if ( $Module->isCurrentAction( 'Edit' )  )
{
    if ( !$canEdit )
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

    $versionID = false;
    if ( $Module->hasActionParameter( 'VersionID' ) )
        $versionID = $Module->actionParameter( 'VersionID' );
    if ( $Module->hasActionParameter( 'EditLanguage' ) and
         $Module->actionParameter( 'EditLanguage' ) )
        $EditLanguage = $Module->actionParameter( 'EditLanguage' );
    $version =& $object->version( $versionID );
    if ( $version === null )
        $versionID = false;

    $user =& eZUser::currentUser();

    if ( $versionID !== false and
         $version->attribute( 'status' ) != EZ_VERSION_STATUS_DRAFT )
    {
        $editWarning = 1;
        $EditVersion = $versionID;
    }
    else if ( $versionID !== false and
              $version->attribute( 'creator_id' ) != $user->attribute( 'contentobject_id' ) )
    {
        $editWarning = 2;
        $EditVersion = $versionID;
    }
    else
    {
        return $Module->redirectToView( 'edit', array( $ObjectID, $versionID, $EditLanguage ) );
    }
}

$versions =& $object->versions();

if ( $Module->isCurrentAction( 'CopyVersion' )  )
{
    if ( !$canEdit )
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

    $contentINI =& eZINI::instance( 'content.ini' );
    $versionlimit = $contentINI->variable( 'VersionManagement', 'DefaultVersionHistoryLimit' );

    $limitList =& $contentINI->variable( 'VersionManagement', 'VersionHistoryClass' );

    $classID = $object->attribute( 'contentclass_id' );
    foreach ( array_keys ( $limitList ) as $key )
    {
        if ( $classID == $key )
            $versionlimit =& $limitList[$key];
    }
    if ( $versionlimit < 2 )
        $versionlimit = 2;

    $versionCount = $object->getVersionCount();
    if ( $versionCount < $versionlimit )
    {
        $versionID = $Module->actionParameter( 'VersionID' );
        foreach ( array_keys( $versions ) as $versionKey )
        {
            $version =& $versions[$versionKey];
            if ( $version->attribute( 'version' ) == $versionID )
            {
                $newVersionID = $object->copyRevertTo( $versionID );
                if ( $Module->hasActionParameter( 'EditLanguage' ) and
                     $Module->actionParameter( 'EditLanguage' ) )
                    $EditLanguage = $Module->actionParameter( 'EditLanguage' );
                return $Module->redirectToView( 'edit', array( $ObjectID, $newVersionID, $EditLanguage ) );
            }
        }
    }
    else
    {
        // Remove oldest archived version first
        $params = array( 'conditions'=>array( 'status'=>3 ) );
        $versions =& $object->versions( true, $params );
        if ( count( $versions ) > 0 )
        {
            $modified = $versions[0]->attribute( 'modified' );
            $removeVersion =& $versions[0];
            foreach ( array_keys( $versions ) as $versionKey )
            {
                $version =& $versions[$versionKey];
                $currentModified = $version->attribute( 'modified' );
                if ( $currentModified < $modified )
                {
                    $modified = $currentModified;
                    $removeVersion = $version;
                }
            }
            $removeVersion->remove();
            $versionID = $Module->actionParameter( 'VersionID' );

            $versions =& $object->versions();
            foreach ( array_keys( $versions ) as $versionKey )
            {
                $version =& $versions[$versionKey];
                if ( $version->attribute( 'version' ) == $versionID )
                {
                    $newVersionID = $object->copyRevertTo( $versionID );
                    if ( $Module->hasActionParameter( 'EditLanguage' ) and
                         $Module->actionParameter( 'EditLanguage' ) )
                        $EditLanguage = $Module->actionParameter( 'EditLanguage' );
                    return $Module->redirectToView( 'edit', array( $ObjectID, $newVersionID, $EditLanguage ) );
                }
            }
        }
        else
        {
            $http->setSessionVariable( 'ExcessVersionHistoryLimit', true );
            $currentVersion = $object->attribute( 'current_version' );
            $Module->redirectToView( 'versions', array( $ObjectID, $currentVersion, $editLanguage ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }
    }
}

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'object', $object->attribute( 'id' ) ), // Object ID
                      array( 'class', $object->attribute( 'contentclass_id' ) ), // Class ID
                      array( 'section_id', $object->attribute( 'section_id' ) ) // Section ID
                      ) ); // Section ID, 0 so far

include_once( 'kernel/classes/ezsection.php' );
eZSection::setGlobalID( $object->attribute( 'section_id' ) );

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'edit_version', $EditVersion );
$tpl->setVariable( 'edit_language', $EditLanguage );
$tpl->setVariable( 'versions', $versions );
$tpl->setVariable( 'edit_warning', $editWarning );
$tpl->setVariable( 'can_edit', $canEdit );
$tpl->setVariable( 'can_remove', $canRemove );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/versions.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Versions' ),
                                'url' => false ) );

?>
