<?php
//
// Definition of eZTemplateUnitOperator class
//
// Created on: <09-Apr-2002 11:14:30 amos>
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

/*!
  \class eZTemplateUnitOperator eztemplateunitoperator.php
  \ingroup eZTemplateOperators
  \brief Handles unit conversion and display using the operator "si"

  The operator reads two parameters. The first tells the kind of unit type
  we're dealing with, for instance: byte, length.
  The second determines the behaviour of prefixes and is optional.

  The available units are defined in the settings/unit.ini file. The bases
  are read from the Base group.

  The unit operator supports both traditional 10^n based prefixes as well
  as binary prefixes(2^n n=10,20..), both old names and new names.
  See <a href="http://physics.nist.gov/cuu/Units/">International Systems of Units</a>

\code
// Example of template code
{1025|si(byte)}
{1025|si(byte,binary)}
{1025|si(byte,decimal)}
{1025|si(byte,none)}
{1025|si(byte,auto)}
{1025|si(byte,mebi)}
\endcode
*/

include_once( "lib/ezutils/classes/ezini.php" );

class eZTemplateUnitOperator
{
    /*!
     Initializes the operator with the name $name, default is "si"
    */
    function eZTemplateUnitOperator( $name = "si" )
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
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( "unit" => array( "type" => "string",
                                       "required" => true,
                                       "default" => false ),
                      "prefix" => array( "type" => "string",
                                         "required" => false,
                                         "default" => "auto" ) );
    }

    /*!
     Performs unit conversion.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $unit = $namedParameters["unit"];
        $prefix = $namedParameters["prefix"];
        $ini =& eZINI::instance();
        if ( $prefix == "auto" )
        {
            $prefixes = $ini->variableArray( "UnitSettings", "BinaryUnits" );
            if ( in_array( $unit, $prefixes ) )
                $prefix = "binary";
            else
                $prefix = "decimal";
        }
        $unit_ini =& eZINI::instance( "units.ini" );
        $use_si = $ini->variable( "UnitSettings", "UseSIUnits" ) == "true";
        $fake = $use_si ? "" : "Fake";
        if ( $unit_ini->hasVariable( "Base", $unit ) )
        {
            $base = $unit_ini->variable( "Base", $unit );
        }
        else
        {
            $tpl->warning( "eZTemplateUnitOperator", "No such unit '$unit'" );
            return;
        }
        $prefix_var = "";
        if ( $prefix == "decimal" )
        {
            $prefix_group = $unit_ini->group( "DecimalPrefixes" );
            $prefixes = array();
            foreach ( $prefix_group as $prefix_item )
            {
                $prefixes[] = explode( ";", $prefix_item );
            }
            usort( $prefixes, "eZTemplateUnitCompareFactor" );
            $prefix_var = "";
            $divider = false;
            foreach ( $prefixes as $prefix )
            {
                $val = pow( 10, (int)$prefix[0] );
                if ( $val <= $operatorValue )
                {
                    $prefix_var = $prefix[1];
                    $operatorValue = number_format( $operatorValue / $val, 2 );
                    break;
                }
            }
        }
        else if ( $prefix == "binary" )
        {
            $prefix_group = $unit_ini->group( $fake . "BinaryPrefixes" );
            $prefixes = array();
            foreach ( $prefix_group as $prefix_item )
            {
                $prefixes[] = explode( ";", $prefix_item );
            }
            usort( $prefixes, "eZTemplateUnitCompareFactor" );
            $prefix_var = "";
            foreach ( $prefixes as $prefix )
            {
                $val = pow( 2, (int)$prefix[0] );
                if ( $val <= $operatorValue )
                {
                    $prefix_var = $prefix[1];
                    $operatorValue = number_format( $operatorValue / $val, 2 );
                    break;
                }
            }
        }
        else
        {
            if ( $unit_ini->hasVariable( "BinaryPrefixes", $prefix ) )
            {
                $prefix_base = 2;
                $prefix_var = $unit_ini->variableArray( "BinaryPrefixes", $prefix );
            }
            else if ( $unit_ini->hasVariable( "DecimalPrefixes", $prefix ) )
            {
                $prefix_base = 10;
                $prefix_var = $unit_ini->variableArray( "DecimalPrefixes", $prefix );
            }
            else if ( $prefix == "none" )
            {
                $prefix_var = "";
            }
            else
                $tpl->warning( $operatorName, "Prefix \"$prefix\" for unit \"$unit\" not found" );
            if ( is_array( $prefix_var ) )
            {
                $val = pow( $prefix_base, (int)$prefix_var[0] );
                $operatorValue = number_format( $operatorValue / $val, 2 );
                $prefix_var = $prefix_var[1];
            }
        }
        $operatorValue = "$operatorValue $prefix_var" . $base;
    }

}

/*!
 Helper function for eZTemplateUnitOperator which sorts array elements.
 Sorts on index 0 of $a and $b.
*/
    function eZTemplateUnitCompareFactor( $a, $b )
    {
        if ( $a[0] == $b[0] )
            return 0;
        return ( $a[0] > $b[0] ) ? -1 : 1;
    }
?>
