<?php
//
// Definition of eZAutoLinkOperator class
//
// Created on: <07-Feb-2003 09:39:55 bf>
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

class eZAutoLinkOperator
{
    /*!
     */
    function eZAutoLinkOperator( $name = 'autolink' )
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
        return array( );
    }

    /*!
     \reimp
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        // Replace mail
        $operatorValue = preg_replace( "#(([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+@([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+)#", "<a href='mailto:\\1'>\\1</a>", $operatorValue );

        // Replace http link
        $operatorValue = preg_replace( "#(http://([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+([\/a-zA-Z0-9_-]+\\.)*([\/a-zA-Z0-9_-])*\?*([a-zA-Z0-9_-]+=[a-zA-Z0-9_-]+&*)*)#", "<a href='\\1' target='_blank'>\\1</a>", $operatorValue );

        // Replace ftp link
        $operatorValue = preg_replace( "#(ftp://([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+([\/a-zA-Z0-9_-]+\\.)*([\/a-zA-Z0-9_-])*)#", "<a href='\\1' target='_blank'>\\1</a>", $operatorValue );
    }

    /// \privatesection
    var $Operators;
};

?>
