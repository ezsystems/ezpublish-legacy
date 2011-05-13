<?php
/**
 * File containing the eZCollaborationViewHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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

    /**
     * Returns a shared instance of the eZCollaborationViewHandler class
     * pr the two input params.
     *
     *
     * @param string $viewMode
     * @param int $type Is self::TYPE_STANDARD by default
     * @return eZCollaborationViewHandler
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
