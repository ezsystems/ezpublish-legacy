<?php
//
// Definition of eZModuleOperator class
//
// Created on: <21-Jun-2007 00:00:00 rl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezmoduleoperator.php
*/

/*!
  \class eZModuleOperator ezmoduleoperator.php
  \brief The class eZModuleOperator does

*/
require_once( 'kernel/common/i18n.php' );

class eZModuleOperator
{
    /*!
     Constructor
    */
    function eZModuleOperator( $name = 'ezmodule' )
    {
        $this->Operators = array( $name );
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'uri' => array( 'type' => 'string',
                                      'required' => false,
                                      'default' => false ) );
    }
    /*!
     \reimp
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $uri = new eZURI( $namedParameters[ 'uri' ] );
        $check = accessAllowed( $uri );
        $operatorValue = $check['result'];
    }

    /// \privatesection
    public $Operators;
}


?>
