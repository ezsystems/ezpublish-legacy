<?php
//
// Definition of eZObjectRelationType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

/*!
  \class eZObjectRelationType ezobjectrelationtype.php
  \ingroup eZKernel
  \brief A content datatype which handles object relations

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezutils/classes/ezintegervalidator.php" );
include_once( "lib/ezi18n/classes/eztranslatormanager.php" );

define( "EZ_DATATYPESTRING_OBJECT_RELATION", "ezobjectrelation" );

class eZObjectRelationType extends eZDataType
{
    /*!
     Initializes with a string id and a description.
    */
    function eZObjectRelationType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_OBJECT_RELATION, ezi18n( 'kernel/classes/datatypes', "Object relation", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $postVariableName = $base . "_data_object_relation_id_" . $contentObjectAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $postVariableName ) )
        {
            $relatedObjectID =& $http->postVariable( $postVariableName );
            $contentObjectAttribute->setAttribute( 'data_int', $relatedObjectID );
            return true;
        }
        return false;
    }

    /*!
     Does nothing since it uses the data_text field in the content object attribute.
     See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( &$attribute )
    {

    }

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_VALID;
    }

    /*!
     \reimp
    */
    function fixupClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {

    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        return true;
    }

    /*!
    */
    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute, $parameters )
    {
        switch ( $action )
        {
            case "set_object_relation" :
            {
                if ( $http->hasPostVariable( 'RemoveObjectButton_' . $contentObjectAttribute->attribute( 'id' ) ) )
                {
                    $contentObjectAttribute->setAttribute( 'data_int', 0 );
                    $contentObjectAttribute->store();
                }

                if ( $http->hasPostVariable( 'BrowseObjectButton_' . $contentObjectAttribute->attribute( 'id' ) ) )
                {
                    $module =& $parameters['module'];
                    $redirectionURI = $parameters['current-redirection-uri'];
/*                    $http->setSessionVariable( 'BrowseFromPage', $redirectionURI );
                    $http->removeSessionVariable( 'CustomBrowseActionAttributeID' );

                    $http->setSessionVariable( 'BrowseActionName', 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ) );
                    $http->setSessionVariable( 'BrowseSelectionType', 'Single' );
                    $http->setSessionVariable( 'BrowseReturnType', 'ObjectID' );

                    $http->setSessionVariable( 'BrowseCustomAction', array( 'name' => 'CustomActionButton[' . $contentObjectAttribute->attribute( 'id' ) . '_set_object_relation]',
                                                                            'value' => $contentObjectAttribute->attribute( 'id' ) ) );

                    $nodeID = 2;
//                    $module->redirectToView( 'browse', array( $nodeID ) );
                    */

                    eZContentBrowse::browse( array( 'action_name' => 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ),
                                                    'type' => 'AddRelatedObjectToDataType',
                                                    'browse_custom_action' => array( 'name' => 'CustomActionButton[' . $contentObjectAttribute->attribute( 'id' ) . '_set_object_relation]',
                                                                                     'value' => $contentObjectAttribute->attribute( 'id' ) ),
                                                    'persistent_data' => array( 'HasObjectInput' => 0 ),
                                                    'from_page' => $redirectionURI ),
                                             $module );

                }
                else if ( $http->hasPostVariable( 'BrowseActionName' ) and
                          $http->postVariable( 'BrowseActionName' ) == ( 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ) ) and
                          $http->hasPostVariable( "SelectedObjectIDArray" ) )
                {
                    $selectedObjectArray = $http->hasPostVariable( "SelectedObjectIDArray" );
                    $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );

                    $objectID = $selectedObjectIDArray[0];
//                     $contentObjectAttribute->setContent( $objectID );
                    $contentObjectAttribute->setAttribute( 'data_int', $objectID );
                    $contentObjectAttribute->store();
                    $http->removeSessionVariable( 'BrowseCustomAction' );
                }
            } break;
            default :
            {
                eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZObjectRelationType" );
            } break;
        }
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $objectID = $contentObjectAttribute->attribute( "data_int" );
        if ( $objectID != 0 )
            return eZContentObject::fetch( $contentObjectAttribute->attribute( "data_int" ) );
        else
            return false;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return false;
    }

    /*!
     Returns the content of the string for use as a title
    */
    function title( &$contentObjectAttribute )
    {
        $object = $this->objectAttributeContent( $contentObjectAttribute );
        if ( $object )
        {
            return $object->attribute( 'name' );
        }
        return false;
    }

    /// \privatesection
}

eZDataType::register( EZ_DATATYPESTRING_OBJECT_RELATION, "ezobjectrelationtype" );

?>
