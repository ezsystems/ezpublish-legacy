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

include_once( 'kernel/classes/ezinformationcollection.php' );
include_once( 'lib/ezutils/classes/ezmail.php' );
include_once( 'lib/ezutils/classes/ezmailtransport.php' );
include_once( 'kernel/common/template.php' );

$Module =& $Params['Module'];
$http =& eZHTTPTool::instance();

if ( $Module->isCurrentAction( 'CollectInformation' ) )
{
    $ObjectID = $Module->actionParameter( 'ContentObjectID' );

    $object =& eZContentObject::fetch( $ObjectID );
    $version =& $object->currentVersion();
    $contentObjectAttributes =& $version->contentObjectAttributes();

    // Create a new collection
    $collection =& eZInformationCollection::create( $ObjectID );
    $collection->store();

    // Check every attribute if it's supposed to collect information
    $unvalidatedAttributes = array();
    foreach ( array_keys( $contentObjectAttributes ) as $key )
    {
        $contentObjectAttribute = $contentObjectAttributes[$key];
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();

        if ( $contentClassAttribute->attribute( 'is_information_collector' ) )
        {
            // Collect the information for the current attribute
            if ( $contentObjectAttribute->fetchInput( $http, "ContentObjectAttribute" ) )
            {
                // \todo add abstraction for data types
                $content =& $contentObjectAttribute->content();

                $collectionAttribute = eZInformationCollectionAttribute::create( $collection->attribute( 'id' ) );
                $collectionAttribute->setAttribute( 'data_text', $content );
                $attr =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
                $collectionAttribute->setAttribute( 'contentclass_attribute_id', $attr->attribute( 'id' ) );
                $collectionAttribute->store();
            }
        }
    }

    // Send e-mail
    $tpl =& templateInit();

    // Set override keys for template
    $res =& eZTemplateDesignResource::instance();
    $res->setKeys( array( array( 'class', $object->attribute( 'contentclass_id' ) ),
                          array( 'object', $object->attribute( 'id' ) )
                          ) );

    $tpl->setVariable( 'collection', $collection );
    $tpl->setVariable( 'object', $object );
    $templateResult =& $tpl->fetch( 'design:content/collectedinfomail.tpl' );

    $subject =& $tpl->variable( 'subject' );
    $receiver =& $tpl->variable( 'email_receiver' );
    $redirectToNodeID =& $tpl->variable( 'redirect_to_node_id' );

    $ini =& eZINI::instance();
    $mail = new eZMail();

    if ( !$mail->validate( $receiver ) )
    {
        // receiver does not contain a valid email address, get the default one
        $receiver = $ini->variable( "InformationCollectionSettings", "EmailReceiver" );
        if ( !$receiver )
            $receiver = $ini->variable( "MailSettings", "AdminEmail" );
    }

    $mail->setReceiver( $receiver );
    $mail->setSubject( $subject );
    $mail->setBody( $templateResult );
    $mailResult = eZMailTransport::send( $mail );

    if ( is_numeric( $redirectToNodeID ) )
    {
        $Module->redirectToView( 'view', array( 'full', $redirectToNodeID ) );
    }
    else
    {
        $Module->redirectToView( 'view', array( 'full', $object->attribute( 'main_node_id' ) ) );
    }
    return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
}

?>
