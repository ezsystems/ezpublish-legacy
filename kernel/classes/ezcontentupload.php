<?php
//
// Definition of eZContentUpload class
//
// Created on: <28-Apr-2003 11:04:47 sp>
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

/*! \file ezcontentupload.php
*/

/*!
  \class eZContentUpload ezcontentupload.php
  \brief Handles simple creation of content objects by uploading files

  This class makes it easy to use the start a new file upload
  and let it be created as a content object.

  Using it is simply to call the \link upload \endlink function with some parameters.

\code
eZContentUpload::upload( array( 'action_name' => 'MyActionName' ), $module );
\endcode

  It requires the module objects as the second parameter to redirect and the first
  define how the upload page should behave. Normally you just want to set \c action_name
  and define the behaviour of that action in settings/upload.ini.

  Fetching the result afterwards is done by calling the result() method, it will return
  the resulting node ID or object ID depending on the configuration of the upload action.

\code
eZContentUpload::result( 'MyActionName' );
\endcode

*/

class eZContentUpload
{
    /*!
     Initializes the object with the session data if they are found.
     If \a $params is supplied it used instead.
    */
    function eZContentUpload( $params = false )
    {
        $http =& eZHTTPTool::instance();
        if ( !$params && $http->hasSessionVariable( 'ContentUploadParameters' ) )
        {
            $this->Parameters =& $http->sessionVariable( 'ContentUploadParameters' );
        }
        else
        {
            $this->Parameters = $params;
        }
    }

    /*!
     \return an array with attribute names.
    */
    function attributes()
    {
        return array_keys( $this->Parameters );
    }

    /*!
     \return true if the attribute name \a $attributeName is among the upload parameters.
    */
    function hasAttribute( $attributeName )
    {
        return array_key_exists( $attributeName, $this->Parameters );
    }

    /*!
     \return the attribute value of the attribute named \a $attributeName or \c null if no such attribute.
    */
    function &attribute( $attributeName )
    {
        if ( isset( $this->Parameters[$attributeName] ) )
            return $this->Parameters[$attributeName];
        return null;
    }

    /*!
     \static
     Sets some session data taken from \a $parameters and start the upload module by redirecting to it using \a $module.
     Most data will be automatically derived from the \c action_name value taken from settings/upload.ini, other
     values will override default values.
    */
    function upload( $parameters = array(), &$module )
    {
        $ini =& eZINI::instance( 'upload.ini' );

        if ( !isset( $parameters['action_name'] ) )
            $parameters['action_name'] = $ini->variable( 'UploadSettings', 'DefaultActionName' );

        if ( !isset( $parameters['result_action_name'] ) )
            $parameters['result_action_name'] = $parameters['action_name'];

        if ( !isset( $parameters['navigation_part_identifier'] ) )
            $parameters['navigation_part_identifier'] = false;

        if ( !isset( $parameters['type'] ) )
            $parameters['type'] = $parameters['action_name'];

        if ( !isset( $parameters['return_type'] ) )
        {
            if ( $ini->hasVariable( $parameters['type'], 'ReturnType' ) )
                $parameters['return_type'] = $ini->variable( $parameters['type'], 'ReturnType' );
            else
                $parameters['return_type'] = $ini->variable( 'UploadSettings', 'DefaultReturnType' );
        }

        if ( !isset( $parameters['upload_custom_action'] ) )
            $parameters['upload_custom_action'] = false;

        if ( !isset( $parameters['custom_action_data'] ) )
            $parameters['custom_action_data'] = false;

        if ( !isset( $parameters['description_template'] ) )
            $parameters['description_template'] = false;

        if ( !isset( $parameters['parent_nodes'] ) )
        {
            $parameters['parent_nodes'] = false;
            if ( $ini->hasVariable( $parameters['type'], 'ParentNodes' ) )
            {
                $parameters['parent_nodes'] = $ini->variable( $parameters['type'], 'ParentNodes' );
            }
        }

        if ( isset( $parameters['keys'] ) )
        {
            $overrideStartNode = false;
            foreach ( $parameters['keys'] as $key => $keyValue )
            {
                $variableName = 'StartNode_' . $key;
                if ( !$ini->hasVariable( $parameters['type'], $variableName ) )
                    continue;
                $keyData = $ini->variable( $parameters['type'], $variableName );
                if ( is_array( $keyValue ) )
                {
                    foreach ( $keyValue as $keySubValue )
                    {
                        if ( isset( $keyData[$keySubValue] ) )
                            $overrideStartNode = $keyData[$keySubValue];
                    }
                }
                else if ( isset( $keyData[$keyValue] ) )
                {
                    $overrideStartNode = $keyData[$keyValue];
                }
                if ( $overrideStartNode )
                    break;
            }
//             if ( $overrideStartNode )
//                 $parameters['start_node'] = $overrideStartNode;
        }

        if ( !isset( $parameters['persistent_data'] ) )
            $parameters['persistent_data'] = false;

        foreach ( $parameters['parent_nodes'] as $key => $parentNode )
        {
            if ( !is_numeric( $parentNode ) )
            {
                $parameters['parent_nodes'][$key] = eZContentUpload::nodeAliasID( $parentNode );
            }
        }

        if ( !isset( $parameters['result_uri'] ) )
            $parameters['result_uri'] = false;

        if ( !isset( $parameters['result_module'] ) )
            $parameters['result_module'] = false;

        $parameters['result'] = false;

        $http =& eZHTTPTool::instance();
        $http->setSessionVariable( 'ContentUploadParameters', $parameters );

        if ( is_null( $module ) )
        {
            return "/content/upload/";
        }
        else
        {
            $module->redirectTo( "/content/upload/" );
            return "/content/upload/";
        }
    }

    /*!
     \static
     \return the node ID for the node alias \a $nodeName or \c false if no ID could be found.
    */
    function nodeAliasID( $nodeName )
    {
        if ( is_numeric( $nodeName ) )
            return $nodeName;
        $uploadINI =& eZINI::instance( 'upload.ini' );
        $aliasList = $uploadINI->variable( 'UploadSettings', 'AliasList' );
        if ( isset( $aliasList[$nodeName] ) )
            return $aliasList[$nodeName];
        $contentINI =& eZINI::instance( 'content.ini' );
        if ( $nodeName == 'content' )
            return $contentINI->variable( 'NodeSettings', 'RootNode' );
        else if ( $nodeName == 'users' )
            return $contentINI->variable( 'NodeSettings', 'UserRootNode' );
        else if ( $nodeName == 'media' )
            return $contentINI->variable( 'NodeSettings', 'MediaRootNode' );
        else if ( $nodeName == 'setup' )
            return $contentINI->variable( 'NodeSettings', 'SetupRootNode' );
        else
            return false;
    }

    /*!
     Sets the result array to \a $result and stores the session variable.
    */
    function setResult( $result )
    {
        $this->Parameters['result'] = $result;
        $http =& eZHTTPTool::instance();
        $http->setSessionVariable( 'ContentUploadParameters', $this->Parameters );
    }

    /*!
     \static
     \return the result of the previous upload operation or \c false if no result was found.
             It uses the action name \a $actionName to determine which result to look for.
     \param $cleanup If \c true it the persisten data is cleaned up by calling cleanup().
    */
    function result( $actionName, $cleanup = true )
    {
        if ( isset( $this ) and
             get_class( $this) == 'ezcontentupload' )
            $upload =& $this;
        else
            $upload = new eZContentUpload();

        $isNodeSelection = $upload->attribute( 'return_type' ) == 'NodeID';
        $resultData = $upload->attribute( 'result' );
        $result = false;
        if ( $isNodeSelection )
        {
            $result = $resultData['node_id'];
        }
        else
        {
            $result = $resultData['object_id'];
        }
        if ( $cleanup )
            eZContentUpload::cleanup( $actionName );
        return $result;
    }

    /*!
     \static
     Cleans up the persistent data and result for action named \a $actionName
    */
    function cleanup( $actionName )
    {
        $http =& eZHTTPTool::instance();
        $http->removeSessionVariable( 'ContentUploadParameters' );
    }

    /*!
     \static
     Similar to cleanup() but removes persistent data from all actions.
    */
    function cleanupAll()
    {
        $http =& eZHTTPTool::instance();
        $http->removeSessionVariable( 'ContentUploadParameters' );
    }

    /// \privatesection
    /// The upload parameters.
    var $Parameters = false;
}

?>
