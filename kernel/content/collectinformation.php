<?php
//
// Created on: <21-Nov-2002 18:27:06 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'kernel/classes/ezinformationcollection.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'lib/ezutils/classes/ezmail.php' );
include_once( 'lib/ezutils/classes/ezmailtransport.php' );
include_once( 'kernel/common/template.php' );

$Module =& $Params['Module'];
$http =& eZHTTPTool::instance();

if ( $Module->isCurrentAction( 'CollectInformation' ) )
{
    $ObjectID = $Module->actionParameter( 'ContentObjectID' );
    $NodeID = $Module->actionParameter( 'ContentNodeID' );
    $ViewMode = 'full';
    if ( $Module->hasActionParameter( 'ViewMode' ) )
        $ViewMode = $Module->actionParameter( 'ViewMode' );

    $object =& eZContentObject::fetch( $ObjectID );
    if ( !$object )
        return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
    if ( !$object->attribute( 'can_read' ) )
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
    $version =& $object->currentVersion();
    $contentObjectAttributes =& $version->contentObjectAttributes();

    $user =& eZUser::currentUser();
    $isLoggedIn = $user->attribute( 'is_logged_in' );
    $allowAnonymous = true;
    if ( !$isLoggedIn )
    {
        $allowAnonymous = eZInformationCollection::allowAnonymous( $object );
    }

    $newCollection = false;
    $collection = false;
    $userDataHandling = eZInformationCollection::userDataHandling( $object );
    if ( $userDataHandling == 'unique' or
         $userDataHandling == 'overwrite'  )
        $collection =& eZInformationCollection::fetchByUserIdentifier( eZInformationCollection::currentUserIdentifier(), $object->attribute( 'id' ) );
    if ( ( !$isLoggedIn and
           !$allowAnonymous ) or
         ( $userDataHandling == 'unique' and
           $collection ) )
    {
        $tpl =& templateInit();

        $attributeHideList = eZInformationCollection::attributeHideList();
        $informationCollectionTemplate = eZInformationCollection::templateForObject( $object );

        $node =& eZContentObjectTreeNode::fetch( $NodeID );

        $collectionID = false;
        if ( $collection )
            $collectionID = $collection->attribute( 'id' );

        $tpl->setVariable( 'node_id', $node->attribute( 'node_id' ) );
        $tpl->setVariable( 'collection_id', $collectionID );
        $tpl->setVariable( 'collection', $collection );
        $tpl->setVariable( 'node', $node );
        $tpl->setVariable( 'object', $object );
        $tpl->setVariable( 'attribute_hide_list', $attributeHideList );
        $tpl->setVariable( 'error', true );
        $tpl->setVariable( 'error_existing_data', ( $userDataHandling == 'unique' and $collection ) );
        $tpl->setVariable( 'error_anonymous_user', ( !$isLoggedIn and !$allowAnonymous ) );

        $section =& eZSection::fetch( $object->attribute( 'section_id' ) );
        if ( $section )
            $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );

        $res =& eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                              array( 'node', $node->attribute( 'node_id' ) ),
                              array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                              array( 'class', $object->attribute( 'contentclass_id' ) ),
                              array( 'class_identifier', $object->attribute( 'class_identifier' ) ),
                              array( 'navigation_part_identifier', $navigationPartIdentifier ),
                              array( 'depth', $node->attribute( 'depth' ) ),
                              array( 'url_alias', $node->attribute( 'url_alias' ) )
                              ) );

        $Result['content'] =& $tpl->fetch( 'design:content/collectedinfo/' . $informationCollectionTemplate . '.tpl' );

        $title = $object->attribute( 'name' );
        if ( $tpl->hasVariable( 'title' ) )
            $title = $tpl->variable( 'title' );

        // create path
        $parents =& $node->attribute( 'path' );

        $path = array();
        $titlePath = array();
        foreach ( $parents as $parent )
        {
            $path[] = array( 'text' => $parent->attribute( 'name' ),
                             'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                             'url_alias' => $parent->attribute( 'url_alias' ),
                             'node_id' => $parent->attribute( 'node_id' )
                             );
        }
        $path[] = array( 'text' => $object->attribute( 'name' ),
                         'url' => '/content/view/full/' . $node->attribute( 'node_id' ),
                         'url_alias' => $node->attribute( 'url_alias' ),
                         'node_id' => $node->attribute( 'node_id' ) );

        array_shift( $parents );
        foreach ( $parents as $parent )
        {
            $titlePath[] = array( 'text' => $parent->attribute( 'name' ),
                                  'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                                  'url_alias' => $parent->attribute( 'url_alias' ),
                                  'node_id' => $parent->attribute( 'node_id' )
                                  );
        }
        $titlePath[] = array( 'text' => $title,
                              'url' => '/content/view/full/' . $node->attribute( 'node_id' ),
                              'url_alias' => $node->attribute( 'url_alias' ),
                              'node_id' => $node->attribute( 'node_id' ) );

        $Result['path'] =& $path;
        $Result['title_path'] =& $titlePath;

        return $Result;
    }
    if ( !$collection )
    {
        $collection =& eZInformationCollection::create( $ObjectID, eZInformationCollection::currentUserIdentifier() );
        $collection->store();
        $newCollection = true;
    }
    else
        $collection->setAttribute( 'modified', time() );


    // Check every attribute if it's supposed to collect information
    $attributeDataBaseName = 'ContentObjectAttribute';
    $unvalidatedAttributes = array();
    $canCollect = true;
    $requireFixup = false;
    foreach ( array_keys( $contentObjectAttributes ) as $key )
    {
        $contentObjectAttribute = $contentObjectAttributes[$key];
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();

        if ( $contentClassAttribute->attribute( 'is_information_collector' ) )
        {
            $inputParameters = null;
            $status = $contentObjectAttribute->validateInformation( $http, $attributeDataBaseName, $inputParameters );
            if ( $status == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
                $requireFixup = true;
            else if ( $status == EZ_INPUT_VALIDATOR_STATE_INVALID )
            {
                $canCollect = false;
                $description = $contentObjectAttribute->attribute( 'validation_error' );
                $hasValidationError = $contentObjectAttribute->attribute( 'has_validation_error' );
                if ( $hasValidationError )
                {
                    if ( !$description )
                        $description = false;
                    $validationName = $contentClassAttribute->attribute( 'name' );
                    $unvalidatedAttributes[] = array( 'id' => $contentObjectAttribute->attribute( 'id' ),
                                                      'identifier' => $contentClassAttribute->attribute( 'identifier' ),
                                                      'name' => $validationName,
                                                      'description' => $description );
                }
            }
            else if ( $status == EZ_INPUT_VALIDATOR_STATE_ACCEPTED )
            {
            }
        }
    }
    $collectionAttributes = array();
    foreach ( array_keys( $contentObjectAttributes ) as $key )
    {
        $contentObjectAttribute = $contentObjectAttributes[$key];
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();

        if ( $contentClassAttribute->attribute( 'is_information_collector' ) )
        {
            // Collect the information for the current attribute
            if ( $newCollection )
                $collectionAttribute =& eZInformationCollectionAttribute::create( $collection->attribute( 'id' ) );
            else
                $collectionAttribute =& eZInformationCollectionAttribute::fetchByObjectAttributeID( $collection->attribute( 'id' ), $contentObjectAttribute->attribute( 'id' ) );
            if ( $collectionAttribute and $contentObjectAttribute->collectInformation( $collection, $collectionAttribute, $http, "ContentObjectAttribute" ) )
            {
                if ( $canCollect )
                {
                    $collectionAttribute->store();
                }
            }
            else
            {
            }
            $collectionAttributes[$contentObjectAttribute->attribute( 'id' )] =& $collectionAttribute;
        }
    }

    if ( $canCollect )
    {
        $collection->sync();

        $sendEmail = eZInformationCollection::sendOutEmail( $object );
        $redirectToNodeID = false;

        if ( $sendEmail )
        {
            $tpl =& templateInit();

            $attributeHideList = eZInformationCollection::attributeHideList();
            $informationCollectionTemplate = eZInformationCollection::templateForObject( $object );

            $node =& eZContentObjectTreeNode::fetch( $NodeID );

            $section =& eZSection::fetch( $object->attribute( 'section_id' ) );
            if ( $section )
                $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );

            $res =& eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                                  array( 'node', $node->attribute( 'node_id' ) ),
                                  array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                                  array( 'class', $object->attribute( 'contentclass_id' ) ),
                                  array( 'class_identifier', $object->attribute( 'class_identifier' ) ),
                                  array( 'navigation_part_identifier', $navigationPartIdentifier ),
                                  array( 'depth', $node->attribute( 'depth' ) ),
                                  array( 'url_alias', $node->attribute( 'url_alias' ) )
                                  ) );

            $tpl->setVariable( 'node_id', $node->attribute( 'node_id' ) );
            $tpl->setVariable( 'collection_id', $collection->attribute( 'id' ) );
            $tpl->setVariable( 'collection', $collection );
            $tpl->setVariable( 'node', $node );
            $tpl->setVariable( 'object', $object );
            $tpl->setVariable( 'attribute_hide_list', $attributeHideList );

            $tpl->setVariable( 'collection', $collection );
            $tpl->setVariable( 'object', $object );
            $templateResult =& $tpl->fetch( 'design:content/collectedinfomail/' . $informationCollectionTemplate . '.tpl' );

            $subject =& $tpl->variable( 'subject' );
            $receiver =& $tpl->variable( 'email_receiver' );
            $redirectToNodeID =& $tpl->variable( 'redirect_to_node_id' );

            $ini =& eZINI::instance();
            $mail = new eZMail();

            if ( !$mail->validate( $receiver ) )
            {
                $receiver = $ini->variable( "InformationCollectionSettings", "EmailReceiver" );
                if ( !$receiver )
                    $receiver = $ini->variable( "MailSettings", "AdminEmail" );
            }
	    $mail->setReceiver( $receiver );      

	    $sender = $ini->variable( "MailSettings", "EmailSender" );
            $mail->setSender( $sender );
            $mail->setReplyTo( $sender );
        
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );
        }

        $icMap = array();
        if ( eZHTTPTool::hasSessionVariable( 'InformationCollectionMap' ) )
            $icMap = eZHTTPTool::sessionVariable( 'InformationCollectionMap' );
        $icMap[$object->attribute( 'id' )] = $collection->attribute( 'id' );
        eZHTTPTool::setSessionVariable( 'InformationCollectionMap', $icMap );

        if ( is_numeric( $redirectToNodeID ) )
        {
            $Module->redirectToView( 'view', array( 'full', $redirectToNodeID ) );
        }
        else
        {
            $display = eZInformationCollection::displayHandling( $object );
            if ( $display == 'node' )
            {
                $Module->redirectToView( 'view', array( $ViewMode, $NodeID ) );
            }
            else if ( $display == 'redirect' )
            {
                $redirectURL = eZInformationCollection::redirectURL( $object );
                $Module->redirectTo( $redirectURL );
            }
            else
            {
                $Module->redirectToView( 'collectedinfo', array( $NodeID ) );
            }
        }
    }
    else
    {
        $collection->remove();

        return $Module->run( 'view', array( $ViewMode, $NodeID ),
                             array( 'ViewCache' => false,
                                    'AttributeValidation' => array( 'processed' => true,
                                                                    'attributes' => $unvalidatedAttributes ),
                                    'CollectionAttributes' => $collectionAttributes ) );
    }

    return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
}

?>
