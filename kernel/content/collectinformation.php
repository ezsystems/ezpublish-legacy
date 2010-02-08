<?php
//
// Created on: <21-Nov-2002 18:27:06 bf>
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

require_once( 'kernel/common/template.php' );

$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$userParameters = array();

if ( isset( $Params['UserParameters'] ) )
{
    $userParameters = $Params['UserParameters'];
}

if ( $Module->isCurrentAction( 'CollectInformation' ) )
{
    $ObjectID = $Module->actionParameter( 'ContentObjectID' );
    $NodeID = $Module->actionParameter( 'ContentNodeID' );
    $ViewMode = 'full';
    if ( $Module->hasActionParameter( 'ViewMode' ) )
        $ViewMode = $Module->actionParameter( 'ViewMode' );

    $object = eZContentObject::fetch( $ObjectID );
    if ( !$object )
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    if ( !$object->attribute( 'can_read' ) )
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    $version = $object->currentVersion();
    $contentObjectAttributes = $version->contentObjectAttributes();

    $user = eZUser::currentUser();
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
        $collection = eZInformationCollection::fetchByUserIdentifier( eZInformationCollection::currentUserIdentifier(), $object->attribute( 'id' ) );
    if ( ( !$isLoggedIn and
           !$allowAnonymous ) or
         ( $userDataHandling == 'unique' and
           $collection ) )
    {
        $tpl = templateInit();

        $attributeHideList = eZInformationCollection::attributeHideList();
        $informationCollectionTemplate = eZInformationCollection::templateForObject( $object );

        $node = eZContentObjectTreeNode::fetch( $NodeID );

        $collectionID = false;
        if ( $collection )
            $collectionID = $collection->attribute( 'id' );

        $tpl->setVariable( 'node_id', $node->attribute( 'node_id' ) );
        $tpl->setVariable( 'collection_id', $collectionID );
        $tpl->setVariable( 'collection', $collection );
        $tpl->setVariable( 'node', $node );
        $tpl->setVariable( 'object', $object );
        $tpl->setVariable( 'viewmode', $ViewMode );
        $tpl->setVariable( 'view_parameters', $userParameters );
        $tpl->setVariable( 'attribute_hide_list', $attributeHideList );
        $tpl->setVariable( 'error', true );
        $tpl->setVariable( 'error_existing_data', ( $userDataHandling == 'unique' and $collection ) );
        $tpl->setVariable( 'error_anonymous_user', ( !$isLoggedIn and !$allowAnonymous ) );

        $section = eZSection::fetch( $object->attribute( 'section_id' ) );
        if ( $section )
            $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );

        $res = eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                              array( 'node', $node->attribute( 'node_id' ) ),
                              array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                              array( 'class', $object->attribute( 'contentclass_id' ) ),
                              array( 'class_identifier', $object->attribute( 'class_identifier' ) ),
                              array( 'viewmode', $ViewMode ),
                              array( 'remote_id', $object->attribute( 'remote_id' ) ),
                              array( 'node_remote_id', $node->attribute( 'remote_id' ) ),
                              array( 'navigation_part_identifier', $navigationPartIdentifier ),
                              array( 'depth', $node->attribute( 'depth' ) ),
                              array( 'url_alias', $node->attribute( 'url_alias' ) ),
                              array( 'class_group', $object->attribute( 'match_ingroup_id_list' ) ),
                              array( 'state', $object->attribute( 'state_id_array' ) ),
                              array( 'state_identifier', $object->attribute( 'state_identifier_array' ) )
                              ) );

        $Result = array();
        $Result['content'] = $tpl->fetch( 'design:content/collectedinfo/' . $informationCollectionTemplate . '.tpl' );
        $Result['section_id'] = $object->attribute( 'section_id' );
        $Result['node_id'] = $node->attribute( 'node_id' );
        $Result['view_parameters'] = $userParameters;
        $Result['navigation_part'] = $navigationPartIdentifier;

        $title = $object->attribute( 'name' );
        if ( $tpl->hasVariable( 'title' ) )
            $title = $tpl->variable( 'title' );

        // create path
        $parents = $node->attribute( 'path' );

        $path = array();
        foreach ( $parents as $parent )
        {
            $path[] = array( 'text' => $parent->attribute( 'name' ),
                             'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                             'url_alias' => $parent->attribute( 'url_alias' ),
                             'node_id' => $parent->attribute( 'node_id' ) );
        }

        $titlePath = $path;
        $path[] = array( 'text' => $object->attribute( 'name' ),
                         'url' => false,
                         'url_alias' => false,
                         'node_id' => $node->attribute( 'node_id' ) );

        $titlePath[] = array( 'text' => $title,
                              'url' => false,
                              'url_alias' => false );

        $Result['path'] = $path;
        $Result['title_path'] = $titlePath;

        return $Result;
    }
    if ( !$collection )
    {
        $collection = eZInformationCollection::create( $ObjectID, eZInformationCollection::currentUserIdentifier() );
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
        $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();

        if ( $contentClassAttribute->attribute( 'is_information_collector' ) )
        {
            $inputParameters = null;
            $status = $contentObjectAttribute->validateInformation( $http, $attributeDataBaseName, $inputParameters );
            if ( $status == eZInputValidator::STATE_INTERMEDIATE )
                $requireFixup = true;
            else if ( $status == eZInputValidator::STATE_INVALID )
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
            else if ( $status == eZInputValidator::STATE_ACCEPTED )
            {
            }
        }
    }
    $collectionAttributes = array();

    $db = eZDB::instance();
    $db->begin();

    foreach ( array_keys( $contentObjectAttributes ) as $key )
    {
        $contentObjectAttribute = $contentObjectAttributes[$key];
        $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();

        if ( $contentClassAttribute->attribute( 'is_information_collector' ) )
        {
            // Collect the information for the current attribute
            if ( $newCollection )
                $collectionAttribute = eZInformationCollectionAttribute::create( $collection->attribute( 'id' ) );
            else
                $collectionAttribute = eZInformationCollectionAttribute::fetchByObjectAttributeID( $collection->attribute( 'id' ), $contentObjectAttribute->attribute( 'id' ) );
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
            $collectionAttributes[$contentObjectAttribute->attribute( 'id' )] = $collectionAttribute;
        }
    }
    $db->commit();

    if ( $canCollect )
    {
        $collection->sync();

        $sendEmail = eZInformationCollection::sendOutEmail( $object );
        $redirectToNodeID = false;

        if ( $sendEmail )
        {
            $tpl = templateInit();

            $attributeHideList = eZInformationCollection::attributeHideList();
            $informationCollectionTemplate = eZInformationCollection::templateForObject( $object );

            $node = eZContentObjectTreeNode::fetch( $NodeID );

            $section = eZSection::fetch( $object->attribute( 'section_id' ) );
            if ( $section )
                $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );

            $res = eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                                  array( 'node', $node->attribute( 'node_id' ) ),
                                  array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                                  array( 'class', $object->attribute( 'contentclass_id' ) ),
                                  array( 'class_identifier', $object->attribute( 'class_identifier' ) ),
                                  array( 'viewmode', $ViewMode ),
                                  array( 'remote_id', $object->attribute( 'remote_id' ) ),
                                  array( 'node_remote_id', $node->attribute( 'remote_id' ) ),
                                  array( 'navigation_part_identifier', $navigationPartIdentifier ),
                                  array( 'depth', $node->attribute( 'depth' ) ),
                                  array( 'url_alias', $node->attribute( 'url_alias' ) ),
                                  array( 'class_group', $object->attribute( 'match_ingroup_id_list' ) ),
                                  array( 'state', $object->attribute( 'state_id_array' ) ),
                                  array( 'state_identifier', $object->attribute( 'state_identifier_array' ) )
                                  ) );

            $tpl->setVariable( 'node_id', $node->attribute( 'node_id' ) );
            $tpl->setVariable( 'collection_id', $collection->attribute( 'id' ) );
            $tpl->setVariable( 'collection', $collection );
            $tpl->setVariable( 'node', $node );
            $tpl->setVariable( 'viewmode', $ViewMode );
            $tpl->setVariable( 'view_parameters', $userParameters );
            $tpl->setVariable( 'object', $object );
            $tpl->setVariable( 'attribute_hide_list', $attributeHideList );

            $tpl->setVariable( 'collection', $collection );
            $tpl->setVariable( 'object', $object );
            $templateResult = $tpl->fetch( 'design:content/collectedinfomail/' . $informationCollectionTemplate . '.tpl' );

            $subject = $tpl->variable( 'subject' );
            $receiver = $tpl->variable( 'email_receiver' );
            $ccReceivers = $tpl->variable( 'email_cc_receivers' );
            $bccReceivers = $tpl->variable( 'email_bcc_receivers' );
            $sender = $tpl->variable( 'email_sender' );
            $replyTo = $tpl->variable( 'email_reply_to' );
            $redirectToNodeID = $tpl->variable( 'redirect_to_node_id' );

            $ini = eZINI::instance();
            $mail = new eZMail();

            if ( $tpl->hasVariable( 'content_type' ) )
                $mail->setContentType( $tpl->variable( 'content_type' ) );

            if ( !$mail->validate( $receiver ) )
            {
                $receiver = $ini->variable( "InformationCollectionSettings", "EmailReceiver" );
                if ( !$receiver )
                    $receiver = $ini->variable( "MailSettings", "AdminEmail" );
            }
            $mail->setReceiver( $receiver );

            if ( !$mail->validate( $sender ) )
            {
                $sender = $ini->variable( "MailSettings", "EmailSender" );
            }
            $mail->setSender( $sender );

            if ( !$mail->validate( $replyTo ) )
            {
                // If replyTo address is not set in the template, take it from the settings
                $replyTo = $ini->variable( "MailSettings", "EmailReplyTo" );
                if ( !$mail->validate( $replyTo ) )
                {
                    // If replyTo address is not set in the settings, use the sender address
                    $replyTo = $sender;
                }
            }
            $mail->setReplyTo( $replyTo );

            // Handle CC recipients
            if ( $ccReceivers )
            {
                if ( !is_array( $ccReceivers ) )
                    $ccReceivers = array( $ccReceivers );
                foreach ( $ccReceivers as $ccReceiver )
                {
                    if ( $mail->validate( $ccReceiver ) )
                        $mail->addCc( $ccReceiver );
                }
            }

            // Handle BCC recipients
            if ( $bccReceivers )
            {
                if ( !is_array( $bccReceivers ) )
                    $bccReceivers = array( $bccReceivers );

                foreach ( $bccReceivers as $bccReceiver )
                {
                    if ( $mail->validate( $bccReceiver ) )
                        $mail->addBcc( $bccReceiver );
                }
            }

            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );
        }

        $icMap = array();
        if ( $http->hasSessionVariable( 'InformationCollectionMap' ) )
            $icMap = $http->sessionVariable( 'InformationCollectionMap' );
        $icMap[$object->attribute( 'id' )] = $collection->attribute( 'id' );
        $http->setSessionVariable( 'InformationCollectionMap', $icMap );

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

    return eZModule::HOOK_STATUS_CANCEL_RUN;
}

?>
