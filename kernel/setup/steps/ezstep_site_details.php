<?php
//
// Definition of eZStepSiteDetails class
//
// Created on: <12-Aug-2003 18:30:57 kk>
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

/*! \file ezstep_site_details.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );

/*!
  \class eZStepSiteDetails ezstep_site_details.php
  \brief The class eZStepSiteDetails does

*/

class eZStepSiteDetails extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSiteDetails( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
    }

    /*!
     \reimp
    */
    function processPostData()
    {
        //todo : check input values

        for ( $counter = 0; $counter < $this->PersistenceList['site_templates']['count']; $counter++ )
        {
            $this->PersistenceList['site_templates_'.$counter]['title'] =
                 $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_title' );
            $this->PersistenceList['site_templates_'.$counter]['url'] =
                 $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_url' );
            $this->PersistenceList['site_templates_'.$counter]['email'] =
                 $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_email' );
            $this->PersistenceList['site_templates_'.$counter]['access_type_value'] =
                 $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_value' );
        }
        return true;
    }

    /*!
     \reimp
     */
    function init()
    {
        return false; // Always show site details
    }

    /*!
     \reimp
    */
    function &display()
    {
        $templates = array();
        for ( $counter = 0; $counter < $this->PersistenceList['site_templates']['count']; $counter++ )
        {
            $templates[$counter] = $this->PersistenceList['site_templates_'.$counter];
            if ( !isset( $templates[$counter]['url'] ) )
                $templates[$counter]['url'] = 'http://';
            if ( !isset( $templates[$counter]['email'] ) )
                $templates[$counter]['email'] = 'admin@localhost';
        }

        $this->Tpl->setVariable( 'site_templates', $templates );

        $this->Tpl->setVariable( 'setup_previous_step', 'SiteDetails' );
        $this->Tpl->setVariable( 'setup_next_step', 'Security' );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_details.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Site details' ),
                                        'url' => false ) );
        return $result;
    }
    }

?>
