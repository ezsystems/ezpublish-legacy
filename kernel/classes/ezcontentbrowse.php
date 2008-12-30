<?php
//
// Definition of eZContentBrowse class
//
// Created on: <28-Apr-2003 11:04:47 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZContentBrowse ezcontentbrowse.php
  \brief Handles browsing of content in the node tree

  This class makes it easy to use the browse system to
  search for content objects or nodes. The class will take
  care of storing the necessary session variables and redirect
  to the browse page.

  Using it is simply to call the \link browse \endlink function with some parameters.

\code
eZContentBrowse::browse( array( 'action_name' => 'MyActionName' ), $module );
\endcode

  It requires the module objects as the second parameter to redirect and the first
  define how the browse page should behave. Normally you just want to set \c action_name
  and define the behaviour of that action in settings/browse.ini.

*/

class eZContentBrowse
{
    /*!
     Initializes the object with the session data if they are found.
     If \a $params is supplied it used instead.
    */
    function eZContentBrowse( $params = false )
    {
        $http = eZHTTPTool::instance();
        if ( !$params && $http->hasSessionVariable( 'BrowseParameters' ) )
        {
            $this->Parameters =& $http->sessionVariable( 'BrowseParameters' );
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
     \return true if the attribute name \a $attributeName is among the browse parameters.
    */
    function hasAttribute( $attributeName )
    {
        return array_key_exists( $attributeName, $this->Parameters );
    }

    /*!
     \return the attribute value of the attribute named \a $attributeName or \c null if no such attribute.
    */
    function attribute( $attributeName )
    {
        if ( isset( $this->Parameters[$attributeName] ) )
        {
            return $this->Parameters[$attributeName];
        }

        eZDebug::writeError( "Attribute '$attributeName' does not exist", 'eZContentBrowse::attribute' );
        return null;
    }

    /*!
     \static
     Sets some session data taken from \a $parameters and start the browse module by redirecting to it using \a $module.
     Most data will be automatically derived from the \c action_name value taken from settings/browse.ini, other
     values will override default values.
    */
    static function browse( $parameters = array(), &$module )
    {
        $ini = eZINI::instance( 'browse.ini' );

        if ( !isset( $parameters['action_name'] ) )
            $parameters['action_name'] = $ini->variable( 'BrowseSettings', 'DefaultActionName' );

        if ( !isset( $parameters['type'] ) )
            $parameters['type'] = $parameters['action_name']; //$ini->variable( $parameters['action_name'], 'BrowseType' );

        if ( !isset( $parameters['selection'] ) )
        {
            if ( $ini->hasVariable( $parameters['type'], 'SelectionType' ) )
                $parameters['selection'] = $ini->variable( $parameters['type'], 'SelectionType' );
            else
                $parameters['selection'] = $ini->variable( 'BrowseSettings', 'DefaultSelectionType' );
        }

        if ( !isset( $parameters['return_type'] ) )
        {
            if ( $ini->hasVariable( $parameters['type'], 'ReturnType' ) )
                $parameters['return_type'] = $ini->variable( $parameters['type'], 'ReturnType' );
            else
                $parameters['return_type'] = $ini->variable( 'BrowseSettings', 'DefaultReturnType' );
        }

        if ( !isset( $parameters['browse_custom_action'] ) )
            $parameters['browse_custom_action'] = false;

        if ( !isset( $parameters['custom_action_data'] ) )
            $parameters['custom_action_data'] = false;

        if ( !isset( $parameters['description_template'] ) )
            $parameters['description_template'] = false;

        if ( !isset( $parameters['start_node'] ) )
            $parameters['start_node'] = $ini->variable( $parameters['type'], 'StartNode' );

        if ( !isset( $parameters['ignore_nodes_select'] ) )
            $parameters['ignore_nodes_select'] = array();

        if ( !isset( $parameters['ignore_nodes_select_subtree'] ) )
            $parameters['ignore_nodes_select_subtree'] = array();

        if ( !isset( $parameters['ignore_nodes_click'] ) )
            $parameters['ignore_nodes_click'] = array();

        if ( !isset( $parameters['class_array'] ) )
        {
            if ( $ini->hasVariable( $parameters['type'], 'Class' ) )
            {
                $parameters['class_array'] = $ini->variable( $parameters['type'], 'Class' );
            }
            else
            {
                $parameters['class_array'] = false;
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
            if ( $overrideStartNode )
                $parameters['start_node'] = $overrideStartNode;
        }

        if ( !isset( $parameters['persistent_data'] ) )
            $parameters['persistent_data'] = false;

        if ( !isset( $parameters['permission'] ) )
            $parameters['permission'] = false;

        if ( !isset( $parameters['top_level_nodes'] ) )
        {
            $parameters['top_level_nodes'] = $ini->variable( 'BrowseSettings', 'DefaultTopLevelNodes' );
            if ( $ini->hasVariable( $parameters['type'], 'TopLevelNodes' ) )
                $parameters['top_level_nodes'] = $ini->variable( $parameters['type'], 'TopLevelNodes' );
        }

        if ( !is_numeric( $parameters['start_node'] ) )
            $parameters['start_node'] = eZContentBrowse::nodeAliasID( $parameters['start_node'] );

        for ( $i =0; $i < count( $parameters['top_level_nodes'] ); $i++ )
        {
            if ( !is_numeric( $parameters['top_level_nodes'][$i] ) )
                $parameters['top_level_nodes'][$i] = eZContentBrowse::nodeAliasID( $parameters['top_level_nodes'][$i] );
        }

        if ( !isset( $parameters['cancel_page'] ) )
            $parameters['cancel_page'] = false;

        if ( !isset( $parameters['from_page'] ) )
        {
            eZDebug::writeError( $parameters, 'eZContentBrowse::browse() $parameters[\'from_page\'] is not set' );
        }

        $http = eZHTTPTool::instance();
        $http->setSessionVariable( 'BrowseParameters', $parameters );

        if ( is_null( $module ) )
        {
            return "/content/browse/";
        }
        else
        {
            $module->redirectTo( "/content/browse/" );
            return "/content/browse/";
        }
    }

    /*!
     \static
     \return the node ID for the node alias \a $nodeName or \c false if no ID could be found.
    */
    static function nodeAliasID( $nodeName )
    {
        if ( is_numeric( $nodeName ) )
            return $nodeName;
        $browseINI = eZINI::instance( 'browse.ini' );
        $aliasList = $browseINI->variable( 'BrowseSettings', 'AliasList' );
        if ( isset( $aliasList[$nodeName] ) )
            return $aliasList[$nodeName];
        $contentINI = eZINI::instance( 'content.ini' );
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
     Sets the node ID where browsing starts.
    */
    function setStartNode( $nodeID )
    {
        $this->Parameters['start_node'] = $nodeID;
    }

    /*!
     \static
     \return the result of the previous browse operation or \c false if no result was found.
             It uses the action name \a $actionName to determine which result to look for.
    */
    static function result( $actionName, $asObject = false )
    {
        $ini = eZINI::instance( 'browse.ini' );
        $isNodeSelection = $ini->variable( $actionName, 'ReturnType' ) == 'NodeID';
        if ( $isNodeSelection )
            $postName = 'SelectedNodeIDArray';
        else
            $postName = 'SelectedObjectIDArray';
        $http = eZHTTPTool::instance();
        if ( $http->hasPostVariable( $postName ) && !$http->hasPostVariable( 'BrowseCancelButton' ) )
        {
            $postList = $http->postVariable( $postName );
            $list = array();
            foreach ( $postList as $value )
            {
                if ( !is_numeric( $value ) )
                {
                    eZDebug::writeError( "Non-numeric value ($value) found for POST variable $postName for browse action '$actionName', the value will be excluded",
                                         'eZContentBrowse::result' );
                    continue;
                }
                // Append the value as a real integer, avoids XSS problems.
                $intValue = (int)$value;
                if ( $value != $intValue )
                {
                    eZDebug::writeError( "Non-integer value ($value) found for POST variable $postName for browse action '$actionName', the value will be excluded",
                                         'eZContentBrowse::result' );
                    continue;
                }
                $list[] = $intValue;
            }
            return array_unique( $list );
        }
        return false;
    }

    /// \privatesection
    /// The browse parameters.
    public $Parameters = false;
}

?>
