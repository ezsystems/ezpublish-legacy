<?php
//
// Definition of eZTopmenuUrlOperator class
//
// Created on: <02-Nov-2004 18:54:40 sp>
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

/*! \file eztopmenuurloperator.php
*/

/*!
  \class eZTopMenuUrlOperator eztopmenuurloperator.php
  \brief The class eZTopMenuUrlOperator does

*/

class eZTopMenuUrlOperator
{
    /*!
     Constructor
    */
    function eZTopMenuUrlOperator( $name = 'topmenu_url' )
    {
        $this->Operators = array( $name );
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
        return array( 'menu_item' => array( 'type' => 'string',
                                            'required' => true,
                                            'default' => 'content' ) );
    }
    /*!
     \reimp
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $menuItem = $namedParameters['menu_item'];
        $ini =& eZINI::instance( 'menu.ini' );
        $topMenuItemList = $ini->variable( 'TopAdminMenu','MenuItemList');
        if ( array_key_exists( $menuItem, $topMenuItemList  ) )
        {
            $operatorValue = $topMenuItemList[$menuItem];
        }
        else
        {
            eZDebug::writeError( $topMenuItemList, "Url for menu item \"$menuItem\" is not defined " );
            $operatorValue = '';
        }
    }

    /// \privatesection
    var $Operators;

}

?>
