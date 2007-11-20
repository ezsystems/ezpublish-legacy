<?php
//
// Definition of eZTemplateUnitOperator class
//
// Created on: <09-Apr-2002 11:14:30 amos>
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

//include_once( "lib/ezutils/classes/ezini.php" );

class eZTemplateUnitOperator
{
    /*!
     Initializes the operator with the name $name, default is "si"
    */
    function eZTemplateUnitOperator( $name = "si" )
    {
        $this->SIName = $name;
        $this->Operators = array( $name );
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    function operatorTemplateHints()
    {
        return array( $this->SIName => array( 'input' => true,
                                              'output' => true,
                                              'parameters' => true,
                                              'element-transformation' => true,
                                              'transform-parameters' => true,
                                              'input-as-parameter' => 'always',
                                              'element-transformation-func' => 'operatorTransform' ) );
    }

    /*!
      \reimp
    */
    function operatorTransform( $operatorName, &$node, $tpl, &$resourceData,
                                $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        if ( !eZTemplateNodeTool::isStaticElement( $parameters[1] ) ||
             ( count( $parameters ) > 2 && !eZTemplateNodeTool::isStaticElement( $parameters[2] ) ) )
        {
            return false;
        }

        // We do not support non-static values for decimal_count, decimal_symbol and thousands_separator
        if ( count( $parameters ) > 3 and
             !eZTemplateNodeTool::isStaticElement( $parameters[3] ) )
            return false;
        if ( count( $parameters ) > 4 and
             !eZTemplateNodeTool::isStaticElement( $parameters[4] ) )
            return false;
        if ( count( $parameters ) > 5 and
             !eZTemplateNodeTool::isStaticElement( $parameters[5] ) )
            return false;

        //include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $locale = eZLocale::instance();
        $decimalCount = $locale->decimalCount();
        $decimalSymbol = $locale->decimalSymbol();
        $decimalThousandsSeparator = $locale->thousandsSeparator();

        if ( count( $parameters ) > 2 )
        {
            $prefix = eZTemplateNodeTool::elementStaticValue( $parameters[2] );
        }
        else
        {
            $prefix = 'auto';
        }

        if ( count( $parameters ) > 3 )
            $decimalCount = eZTemplateNodeTool::elementStaticValue( $parameters[3] );
        elseif ( $prefix == 'none' )
            $decimalCount = 0;

        if ( count( $parameters ) > 4 )
            $decimalSymbol = eZTemplateNodeTool::elementStaticValue( $parameters[4] );
        if ( count( $parameters ) > 5 )
            $decimalThousandsSeparator = eZTemplateNodeTool::elementStaticValue( $parameters[5] );

        $decimalSymbolText = eZPHPCreator::variableText( $decimalSymbol, 0, 0, false );
        $decimalThousandsSeparatorText = eZPHPCreator::variableText( $decimalThousandsSeparator, 0, 0, false );

        $unit = eZTemplateNodeTool::elementStaticValue( $parameters[1] );

        $ini = eZINI::instance();
        if ( $prefix == "auto" )
        {
            $prefixes = $ini->variableArray( "UnitSettings", "BinaryUnits" );
            if ( in_array( $unit, $prefixes ) )
                $prefix = "binary";
            else
                $prefix = "decimal";
        }

        $unit_ini = eZINI::instance( "units.ini" );
        $use_si = $ini->variable( "UnitSettings", "UseSIUnits" ) == "true";
        $fake = $use_si ? "" : "Fake";
        if ( $unit_ini->hasVariable( "Base", $unit ) )
        {
            $base = $unit_ini->variable( "Base", $unit );
        }

        $hasInput = false;
        $output = false;
        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
        {
            $output = eZTemplateNodeTool::elementStaticValue( $parameters[0] );
            $hasInput = true;
        }

        $prefix_var = "";
        if ( $prefix == "decimal" )
        {
            $prefixes = $unit_ini->group( "DecimalPrefixes" );

            $prefix_group = $unit_ini->group( "DecimalPrefixes" );
            $prefixes = array();
            foreach ( $prefix_group as $prefix_item )
            {
                $prefixes[] = explode( ";", $prefix_item );
            }
            usort( $prefixes, "eZTemplateUnitCompareFactor" );
            $prefix_var = "";

            if ( $hasInput )
            {
                if ( $output >= 0 and $output < 10 )
                {
                    $prefix_var = '';
                }
                else
                {
                    foreach ( $prefixes as $prefix )
                    {
                        $val = pow( 10, (int)$prefix[0] );
                        if ( $val <= $output )
                        {
                            $prefix_var = $prefix[1];
                            $output = number_format( $output / $val, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
                            break;
                        }
                    }
                }
            }
            else
            {
                $values = array();
                $values[] = $parameters[0];
                $values[] = array( eZTemplateNodeTool::createArrayElement( $prefixes ) );
                $values[] = array( eZTemplateNodeTool::createStringElement( $base ) );

                $code = 'if ( %1% >= 0 and %1% < 10 )' . "\n" .
                     '{' . "\n" .
                     '    %tmp3% = \'\';' . "\n" .
                     '}' . "\n" .
                     'else' . "\n" .
                     '{' . "\n" .
                     '    %tmp3% = "";' . "\n" .
                     '    foreach ( %2% as %tmp1% )' . "\n" .
                     '    {' . "\n" .
                     '        %tmp2% = pow( 10, (int)%tmp1%[0] );' . "\n" .
                     '        if ( %tmp2% <= %1% )' . "\n" .
                     '        {' . "\n" .
                     '            %tmp3% = %tmp1%[1];' . "\n" .
                     '            %1% = number_format( %1% / %tmp2%, ' . $decimalCount . ', ' . $decimalSymbolText . ', ' . $decimalThousandsSeparatorText . ' );' . "\n" .
                     '            break;' . "\n" .
                     '        }' . "\n" .
                     '    }' . "\n" .
                     '}' . "\n" .
                     '%output% = %1% . \' \' . %tmp3% . %3%;';

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values, false, 3 ) );
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

            if ( $hasInput )
            {
                if ( $output >= 0 and $output < 10 )
                {
                    $prefix_var = '';
                }
                {
                    foreach ( $prefixes as $prefix )
                    {
                        $val = pow( 2, (int)$prefix[0] );
                        if ( $val <= $output )
                        {
                            $prefix_var = $prefix[1];
                            $output = number_format( $output / $val, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
                            break;
                        }
                    }
                }
            }
            else
            {
                $values = array();
                $values[] = $parameters[0];
                $values[] = array( eZTemplateNodeTool::createArrayElement( $prefixes ) );
                $values[] = array( eZTemplateNodeTool::createStringElement( $base ) );

                $code = 'if ( %1% >= 0 and %1% < 10 )' . "\n" .
                     '{' . "\n" .
                     '    %tmp3% = \'\';' . "\n" .
                     '}' . "\n" .
                     'else' . "\n" .
                     '{' . "\n" .
                     '    %tmp3% = "";' . "\n" .
                     '    foreach ( %2% as %tmp1% )' . "\n" .
                     '    {' . "\n" .
                     '      %tmp2% = pow( 2, (int)%tmp1%[0] );' . "\n" .
                     '      if ( %tmp2% <= %1% )' . "\n" .
                     '      {' . "\n" .
                     '        %tmp3% = %tmp1%[1];' . "\n" .
                     '        %1% = number_format( %1% / %tmp2%, ' . $decimalCount . ', ' . $decimalSymbolText . ', ' . $decimalThousandsSeparatorText . ' );' . "\n" .
                     '        break;' . "\n" .
                     '      }' . "\n" .
                     '    }' . "\n" .
                     '}' . "\n" .
                     '%output% = %1% . \' \' . %tmp3% . %3%;';

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values, false, 3 ) );
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
                $prefix_var = '';
                if ( $hasInput )
                {
                    $output = number_format( $output, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
                }
                else
                {
                    $values = array();
                    $values[] = $parameters[0];
                    $values[] = array( eZTemplateNodeTool::createStringElement( '' ) );
                    $values[] = array( eZTemplateNodeTool::createStringElement( $base ) );

                    $code = '%output% = number_format( %1%, ' . $decimalCount . ', ' . $decimalSymbolText . ', ' . $decimalThousandsSeparatorText . ' ) . \' \' . %2% . %3%;';

                    return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
                }
            }

            if ( is_array( $prefix_var ) )
            {
                if ( $hasInput )
                {
                    $val = pow( $prefix_base, (int)$prefix_var[0] );
                    $output = number_format( $output / $val, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
                    $prefix_var = $prefix_var[1];
                }
                else
                {
                    $values = array();
                    $values[] = $parameters[0];
                    $values[] = array( eZTemplateNodeTool::createNumericElement( pow( $prefix_base, (int)$prefix_var[0] ) ) );
                    $values[] = array( eZTemplateNodeTool::createStringElement( $prefix_var[1] ) );
                    $values[] = array( eZTemplateNodeTool::createStringElement( $base ) );

                    $code = '%output% = number_format( %1% / %2%, ' . $decimalCount . ', ' . $decimalSymbolText . ', ' . $decimalThousandsSeparatorText . ' ) . \' \' . %3% . %4%;';

                    return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
                }
            }
        }

        if ( $hasInput )
        {
            return array( eZTemplateNodeTool::createStringElement( $output . ' ' . $prefix_var . $base ) );
        }

        $values = array();
        $values[] = $parameters[0];
        $values[] = array( eZTemplateNodeTool::createStringElement( $prefix_var ) );
        $values[] = array( eZTemplateNodeTool::createStringElement( $base ) );

        $code = '%output% = %1% . \' \' . %2% . %3%;';

        return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
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
                                         "default" => "auto" ),
                      "decimal_count" => array( "type" => "integer",
                                                "required" => false,
                                                "default" => false ),
                      "decimal_symbol" => array( "type" => "string",
                                                 "required" => false,
                                                 "default" => false ),
                      "thousands_separator" => array( "type" => "string",
                                                      "required" => false,
                                                      "default" => false ) );
    }

    /*!
     Performs unit conversion.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters,
                     $placement )
    {
        $unit = $namedParameters["unit"];
        $prefix = $namedParameters["prefix"];

        //include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $locale = eZLocale::instance();
        $decimalCount = $locale->decimalCount();
        $decimalSymbol = $locale->decimalSymbol();
        $decimalThousandsSeparator = $locale->thousandsSeparator();

        if ( $namedParameters['decimal_count'] !== false )
            $decimalCount = $namedParameters['decimal_count'];
        elseif ( $prefix == 'none' )
            $decimalCount = 0;

        if ( strlen( $namedParameters['decimal_symbol'] ) > 0 )
            $decimalSymbol = $namedParameters['decimal_symbol'];
        if ( strlen( $namedParameters['thousands_separator'] ) > 0 )
            $decimalThousandsSeparator = $namedParameters['thousands_separator'];

        $ini = eZINI::instance();
        if ( $prefix == "auto" )
        {
            $prefixes = $ini->variableArray( "UnitSettings", "BinaryUnits" );
            if ( in_array( $unit, $prefixes ) )
                $prefix = "binary";
            else
                $prefix = "decimal";
        }
        $unit_ini = eZINI::instance( "units.ini" );
        $use_si = $ini->variable( "UnitSettings", "UseSIUnits" ) == "true";
        $fake = $use_si ? "" : "Fake";
        if ( $unit_ini->hasVariable( "Base", $unit ) )
        {
            $base = $unit_ini->variable( "Base", $unit );
        }
        else
        {
            $tpl->warning( "eZTemplateUnitOperator", "No such unit '$unit'", $placement );
            return;
        }
        $prefix_var = "";
        if ( $prefix == "decimal" )
        {
            if ( $operatorValue >= 0 and $operatorValue < 10 )
            {
                $prefix_var = '';
            }
            else
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
                        $operatorValue = number_format( $operatorValue / $val, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
                        break;
                    }
                }
            }
        }
        else if ( $prefix == "binary" )
        {
            if ( $operatorValue >= 0 and $operatorValue < 10 )
            {
                $prefix_var = '';
            }
            else
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
                        $operatorValue = number_format( $operatorValue / $val, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
                        break;
                    }
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
                $operatorValue = number_format( $operatorValue, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
            }
            else
                $tpl->warning( $operatorName, "Prefix \"$prefix\" for unit \"$unit\" not found", $placement );
            if ( is_array( $prefix_var ) )
            {
                $val = pow( $prefix_base, (int)$prefix_var[0] );
                $operatorValue = number_format( $operatorValue / $val, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
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
