<?php
//
// Created on: <21-Nov-2002 18:27:06 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
        $contentObjectAttribute =& $contentObjectAttributes[$key];
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();

        if ( $contentClassAttribute->attribute( 'is_information_collector' ) )
        {
            // Collect the information for the current attribute
            if ( $contentObjectAttribute->fetchInput( $http, "ContentObjectAttribute" ) )
            {
                print( "Storing info:<br/>" );
                print( $contentObjectAttribute->content() . "<br/>" );

                $collectionAttribute =& eZInformationCollectionAttribute::create( $collection->attribute( 'id' ) );
                $collectionAttribute->setAttribute( 'data_text', $contentObjectAttribute->content() );
                $collectionAttribute->store();
            }
        }
    }
}

?>
