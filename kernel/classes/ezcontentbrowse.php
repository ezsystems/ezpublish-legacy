<?php
//
// Definition of eZContentBrowse class
//
// Created on: <28-Apr-2003 11:04:47 sp>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*! \file ezcontentbrowse.php
*/

/*!
  \class eZContentBrowse ezcontentbrowse.php
  \brief The class eZContentBrowse does

*/

class eZContentBrowse
{
    /*!
     Constructor
    */
    function eZContentBrowse( $params = false )
    {
        $http =& eZHTTPTool::instance();
        if ( !$params && $http->hasSessionVariable( 'BrowseParameters' ) )
        {
            $this->Parameters =& $http->sessionVariable( 'BrowseParameters' );
        }
        else
        {
            $this->Parameters = $params;
        }
    }

    function attributes()
    {
        return array_keys( $this->Parameters );
    }

    function hasAttribute( $attributeName )
    {
        return array_key_exists( $attributeName, $this->Parameters );
    }

    function &attribute( $attributeName )
    {
        if ( isset( $this->Parameters[$attributeName] ) )
            return $this->Parameters[$attributeName];
        return null;
    }

    function browse( $params = array(), &$module )
    {
        $ini =& eZINI::instance( 'browse.ini' );

        if ( !isset( $params['action_name'] ) )
        {
            $params['action_name'] = $ini->variable( 'BrowseSettings', 'DefaultActionName' );
        }

        if ( !isset( $params['type'] ) )
        {
            $params['type'] = $params['action_name']; //$ini->variable( $params['action_name'], 'BrowseType' );
        }

        if ( !isset( $params['selection'] ) )
        {
            if ( $ini->hasVariable( $params['type'], 'SelectionType' ) )
            {
                $params['selection'] = $ini->variable( $params['type'], 'SelectionType' );
            }
            else
            {
                $params['selection'] = $ini->variable( 'BrowseSettings', 'DefaultSelectionType' );
            }
        }

        if ( !isset( $params['return_type'] ) )
        {
            if ( $ini->hasVariable( $params['type'], 'ReturnType' ) )
            {
                $params['return_type'] = $ini->variable( $params['type'], 'ReturnType' );
            }
            else
            {
                $params['return_type'] = $ini->variable( 'BrowseSettings', 'DefaultReturnType' );
            }

        }

        if ( !isset( $params['browse_custom_action'] ) )
        {
            $params['browse_custom_action'] = false;
        }

        if ( !isset( $params['custom_action_data'] ) )
        {
            $params['custom_action_data'] = false;
        }

        if ( !isset( $params['description_template'] ) )
            $params['description_template'] = false;

        $params['start_node'] = $ini->variable( $params['type'], 'StartNode' );

        if ( !isset( $params['top_level_nodes'] ) )
        {
            $params['top_level_nodes'] = $ini->variable( 'BrowseSettings', 'DefaultTopLevelNodes' );
            if ( $ini->hasVariable( $params['type'], 'TopLevelNodes' ) )
                $params['top_level_nodes'] = $ini->variable( $params['type'], 'TopLevelNodes' );
        }

        if ( !isset( $params['from_page'] ) )
        {
            //           $params['from_page'] = $ini->variable('BrowseSettings', 'DefaultSelectionType' );
            eZDebug::writeError( $params, 'eZContentBrowse::browse() $params[\'from_page\'] is not set' );
        }

        $http =& eZHTTPTool::instance();
        $http->setSessionVariable( 'BrowseParameters', $params );

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

    function setStartNode( $nodeID )
    {
        $this->Parameters['start_node'] = $nodeID;
    }

    var $Parameters = false;
}

?>
