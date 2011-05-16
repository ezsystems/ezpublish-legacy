<?php
/**
 * File containing the eZStepSecurity class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZStepSecurity ezstep_security.php
  \brief The class eZStepSecurity does

*/

class eZStepSecurity extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSecurity( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'security', 'Security' );
    }

    function processPostData()
    {
        return true; // Always continue
    }

    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            return $this->kickstartContinueNextStep();
        }

        if ( file_exists( '.htaccess' ) )
        {
            return true;
        }
        return eZSys::indexFileName() == '' ; // If in virtual host mode, continue (return true)
    }

    function display()
    {
        $this->Tpl->setVariable( 'setup_previous_step', 'Security' );
        $this->Tpl->setVariable( 'setup_next_step', 'Registration' );

        $this->Tpl->setVariable( 'path', realpath( '.' ) );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/security.tpl' );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Securing site' ),
                                        'url' => false ) );
        return $result;
    }
}

?>
