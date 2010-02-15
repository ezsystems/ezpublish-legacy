<?php
//
// Definition of eZStepSecurity class
//
// Created on: <13-Aug-2003 10:42:32 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
        $result['path'] = array( array( 'text' => eZi18n::translate( 'design/standard/setup/init',
                                                          'Securing site' ),
                                        'url' => false ) );
        return $result;
    }
}

?>
