<?php
//
// Definition of eZCollaborationViewHandler class
//
// Created on: <23-Jan-2003 11:59:34 amos>
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

/*! \file ezcollaborationviewhandler.php
*/

/*!
  \class eZCollaborationViewHandler ezcollaborationviewhandler.php
  \brief The class eZCollaborationViewHandler does

*/

define( 'EZ_COLLABORATION_VIEW_TYPE_STANDARD', 1 );
define( 'EZ_COLLABORATION_VIEW_TYPE_GROUP', 2 );

class eZCollaborationViewHandler
{
    /*!
     Initializes the view mode.
    */
    function eZCollaborationViewHandler( $viewMode, $viewType )
    {
        $this->ViewMode = $viewMode;
        $this->ViewType = $viewType;
        $this->TemplateName = $viewMode;
        $ini =& $this->ini();
        if ( $viewType == EZ_COLLABORATION_VIEW_TYPE_STANDARD )
        {
            $this->TemplatePrefix = "design:collaboration/view/";
            $viewGroup = $viewMode . "View";
        }
        else if ( $viewType == EZ_COLLABORATION_VIEW_TYPE_GROUP )
        {
            $this->TemplatePrefix = "design:collaboration/group/view/";
            $viewGroup = $viewMode . "GroupView";
        }
        if ( $ini->hasGroup( $viewGroup ) )
        {
            if ( $ini->hasVariable( $viewGroup, 'TemplateName' ) )
                $this->TemplateName = $ini->variable( $viewGroup, 'TemplateName' );
        }
    }

    /*!
     \return the template which is used for viewing the collaborations.
    */
    function template()
    {
        return $this->TemplatePrefix . $this->TemplateName . ".tpl";
    }

    /*!
     \static
     \return the ini object for collaboration.ini
    */
    function &ini()
    {
        return eZINI::instance( 'collaboration.ini' );
    }

    /*!
     \static
     \return true if the viewmode \a $viewMode exists with the current configuration
    */
    function exists( $viewMode )
    {
        $list =& eZCollaborationViewHandler::fetchList();
        return in_array( $viewMode, $list );
    }

    /*!
     \static
     \return true if the viewmode \a $viewMode exists for groups with the current configuration
    */
    function groupExists( $viewMode )
    {
        $list =& eZCollaborationViewHandler::fetchGroupList();
        return in_array( $viewMode, $list );
    }

    /*!
     \static
     \return a list of active viewmodes.
    */
    function fetchList()
    {
        $ini =& eZCollaborationViewHandler::ini();
        return $ini->variable( 'ViewSettings', 'ViewList' );
    }

    /*!
     \static
     \return a list of active viewmodes for groups.
    */
    function fetchGroupList()
    {
        $ini =& eZCollaborationViewHandler::ini();
        return $ini->variable( 'ViewSettings', 'GroupViewList' );
    }

    /*!
     \static
     \return the single instance of the viewmode \a $viewMode.
    */
    function instance( $viewMode, $type = EZ_COLLABORATION_VIEW_TYPE_STANDARD )
    {
        if ( $type == EZ_COLLABORATION_VIEW_TYPE_STANDARD )
            $instance =& $GLOBALS["eZCollaborationView"][$viewMode];
        else if ( $type == EZ_COLLABORATION_VIEW_TYPE_GROUP )
            $instance =& $GLOBALS["eZCollaborationGroupView"][$viewMode];
        else
            return null;
        if ( !isset( $instance ) )
        {
            $instance = new eZCollaborationViewHandler( $viewMode, $type );
        }
        return $instance;
    }

    /// \privatesection
    /// The viewmode
    var $ViewMode;
    var $ViewType;
    var $TemplateName;
    var $TemplatePrefix;
}

?>
