<?php
//
// Definition of eZCollaborationViewHandler class
//
// Created on: <23-Jan-2003 11:59:34 amos>
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

/*! \file ezcollaborationviewhandler.php
*/

/*!
  \class eZCollaborationViewHandler ezcollaborationviewhandler.php
  \brief The class eZCollaborationViewHandler does

*/

class eZCollaborationViewHandler
{
    const TYPE_STANDARD = 1;
    const TYPE_GROUP = 2;

    /*!
     Initializes the view mode.
    */
    function eZCollaborationViewHandler( $viewMode, $viewType )
    {
        $this->ViewMode = $viewMode;
        $this->ViewType = $viewType;
        $this->TemplateName = $viewMode;
        $ini = $this->ini();
        if ( $viewType == self::TYPE_STANDARD )
        {
            $this->TemplatePrefix = "design:collaboration/view/";
            $viewGroup = $viewMode . "View";
        }
        else if ( $viewType == self::TYPE_GROUP )
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
    static function ini()
    {
        return eZINI::instance( 'collaboration.ini' );
    }

    /*!
     \static
     \return true if the viewmode \a $viewMode exists with the current configuration
    */
    static function exists( $viewMode )
    {
        $list = eZCollaborationViewHandler::fetchList();
        return in_array( $viewMode, $list );
    }

    /*!
     \static
     \return true if the viewmode \a $viewMode exists for groups with the current configuration
    */
    static function groupExists( $viewMode )
    {
        $list = eZCollaborationViewHandler::fetchGroupList();
        return in_array( $viewMode, $list );
    }

    /*!
     \static
     \return a list of active viewmodes.
    */
    static function fetchList()
    {
        return eZCollaborationViewHandler::ini()->variable( 'ViewSettings', 'ViewList' );
    }

    /*!
     \static
     \return a list of active viewmodes for groups.
    */
    static function fetchGroupList()
    {
        return eZCollaborationViewHandler::ini()->variable( 'ViewSettings', 'GroupViewList' );
    }

    /*!
     \static
     \return the single instance of the viewmode \a $viewMode.
    */
    static function instance( $viewMode, $type = self::TYPE_STANDARD )
    {
        if ( $type == self::TYPE_STANDARD )
            $instance =& $GLOBALS["eZCollaborationView"][$viewMode];
        else if ( $type == self::TYPE_GROUP )
            $instance =& $GLOBALS["eZCollaborationGroupView"][$viewMode];
        else
        {
            return null;
        }
        if ( !isset( $instance ) )
        {
            $instance = new eZCollaborationViewHandler( $viewMode, $type );
        }
        return $instance;
    }

    /// \privatesection
    /// The viewmode
    public $ViewMode;
    public $ViewType;
    public $TemplateName;
    public $TemplatePrefix;
}

?>
