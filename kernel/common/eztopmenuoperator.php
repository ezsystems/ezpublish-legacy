<?php
//
// Definition of eZTopMenuOperator class
//
// Created on: <09-Nov-2004 14:33:28 sp>
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

/*! \file eztopmenuoperator.php
*/

/*!
  \class eZTopMenuOperator eztopmenuoperator.php
  \brief The class eZTopMenuOperator does

*/
class eZTopMenuOperator
{
    /*!
     Constructor
    */
    function eZTopMenuOperator( $name = 'topmenu' )
    {
        $this->Operators = array( $name );
        $this->DefaultNames = array(
            'content' => array( 'name' => ezi18n( 'design/admin/pagelayout',
                                                  'Content structure' ),
                                'tooltip'=> ezi18n( 'design/admin/pagelayout',
                                                    'Manage the main content structure of the site.' ) ),
            'media' => array( 'name' => ezi18n( 'design/admin/pagelayout',
                                                'Media library' ),
                              'tooltip'=> ezi18n( 'design/admin/pagelayout',
                                                  'Manage images, files, documents, etc.' ) ),
            'users' => array( 'name' => ezi18n( 'design/admin/pagelayout',
                                                'User accounts' ),
                              'tooltip'=> ezi18n( 'design/admin/pagelayout',
                                                  'Manage users, user groups and permission settings.' ) ),
            'shop' => array( 'name' => ezi18n( 'design/admin/pagelayout',
                                               'Webshop' ),
                             'tooltip'=> ezi18n( 'design/admin/pagelayout',
                                                 'Manage customers, orders, discounts and VAT types; view sales statistics.' ) ),
            'design' => array( 'name' => ezi18n( 'design/admin/pagelayout',
                                                 'Design' ),
                               'tooltip'=> ezi18n( 'design/admin/pagelayout',
                                                   'Manage templates, menus, toolbars and other things related to appearence.' ) ),
            'setup' => array( 'name' => ezi18n( 'design/admin/pagelayout',
                                                'Setup' ),
                              'tooltip'=> ezi18n( 'design/admin/pagelayout',
                                                  'Configure settings and manage advanced functionality.' ) ),
            'my_account' => array( 'name' => ezi18n( 'design/admin/pagelayout',
                                                     'My account' ),
                                   'tooltip'=> ezi18n( 'design/admin/pagelayout',
                                                       'Manage items and settings that belong to your account.' ) ) );
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'context' => array( 'type' => 'string',
                                          'required' => true,
                                          'default' => 'content' ) );
    }
    /*!
     \reimp
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {

        $ini =& eZINI::instance( 'menu.ini' );

        if ( !$ini->hasVariable( 'TopAdminMenu', 'Tabs' ) )
        {
            eZDebug::writeError( "Top Admin menu is not configured. Ini  setting [TopAdminMenu] Tabs[] is missing" );
            $operatorValue = array();
            return;
        }

        $context = $namedParameters['context'];

        $tabIDs = $ini->variable( 'TopAdminMenu', 'Tabs' );

        $menu = array();

        foreach ( $tabIDs as $tabID )
        {
            $shownList = $ini->variable( 'Topmenu_' . $tabID , "Shown" );
            if ( isset( $shownList[$context] ) && $shownList[$context] === 'false' )
            {
                continue;
            }

            $menuItem = array();
            $urlList = $ini->variable( 'Topmenu_' . $tabID , "URL" );
            if ( isset( $urlList[$context] ) )
            {
                $menuItem['url'] = $urlList[$context];
            }
            else
            {
                $menuItem['url'] = $urlList['default'];
            }

            $enabledList = $ini->variable( 'Topmenu_' . $tabID , "Enabled" );
            if ( isset( $enabledList[$context] ) )
            {
                if ( $enabledList[$context] == 'true' )
                    $menuItem['enabled'] = true;
                else
                    $menuItem['enabled'] = false;
            }
            else
            {
                if ( $enabledList['default'] == true )
                    $menuItem['enabled'] = true;
                else
                    $menuItem['enabled'] = false;
            }

            if ( $ini->hasVariable( 'Topmenu_' . $tabID , 'Name' ) &&  $ini->variable( 'Topmenu_' . $tabID , "Name" ) != '' )
            {
                $menuItem['name'] = $ini->variable( 'Topmenu_' . $tabID , "Name" );
            }
            else
            {
                $menuItem['name'] = $this->DefaultNames[$tabID]['name'];
            }
            if ( $ini->hasVariable( 'Topmenu_' . $tabID , 'Tooltip' ) &&  $ini->variable( 'Topmenu_' . $tabID , "Tooltip" ) != '' )
            {
                $menuItem['tooltip'] =  $ini->variable( 'Topmenu_' . $tabID , "Tooltip" );
            }
            else
            {
                $menuItem['tooltip'] = $this->DefaultNames[$tabID]['tooltip'];
            }
            $menuItem['navigationpart_identifier'] =  $ini->variable( 'Topmenu_' . $tabID , "NavigationPartIdentifier" );
            $menuItem['possition'] = 'middle';
            $menu[] = $menuItem;

        }
        $menu[0]['possition'] = 'first';
        $menu[count($menu) - 1]['possition'] = 'last';

        $operatorValue = $menu;
    }

    /// \privatesection
    var $Operators;
    var $DefaultNames;
}


?>
