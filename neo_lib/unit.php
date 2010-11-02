<?php

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
{si( 1025, 'byte')}
{si( 1025, 'byte', 'binary')}
{si( 1025, 'byte', 'decimal')}
{si( 1025, 'byte', 'none')}
{si( 1025, 'byte', 'auto')}
{si( 1025, 'byte', 'mebi')}
\endcode
*/

class ezpMathUnit
{
    public static function format( $value, $unit, $prefix = 'auto', $decimal_count = false, $decimal_symbol = false, $thousands_separator = false )
    {
        $locale = eZLocale::instance();
        $decimalCount = $locale->decimalCount();
        $decimalSymbol = $locale->decimalSymbol();
        $decimalThousandsSeparator = $locale->thousandsSeparator();

        if ( $decimal_count !== false )
            $decimalCount = $decimal_count;
        elseif ( $prefix == 'none' )
            $decimalCount = 0;

        if ( strlen( $decimal_symbol ) > 0 )
            $decimalSymbol = $decimal_symbol;
        if ( strlen( $thousands_separator ) > 0 )
            $decimalThousandsSeparator = $thousands_separator;

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
            throw new Exception( "No such unit '$unit'" );
        }
        $prefix_var = "";
        if ( $prefix == "decimal" )
        {
            if ( $value >= 0 and $value < 10 )
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
                usort( $prefixes, array( __CLASS__, "compareFactor" ) );
                $prefix_var = "";
                $divider = false;
                foreach ( $prefixes as $prefix )
                {
                    $val = pow( 10, (int)$prefix[0] );
                    if ( $val <= $value )
                    {
                        $prefix_var = $prefix[1];
                        $value = number_format( $value / $val, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
                        break;
                    }
                }
            }
        }
        else if ( $prefix == "binary" )
        {
            if ( $value >= 0 and $value < 10 )
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
                usort( $prefixes, array( __CLASS__, "compareFactor" ) );
                $prefix_var = "";
                foreach ( $prefixes as $prefix )
                {
                    $val = pow( 2, (int)$prefix[0] );
                    if ( $val <= $value )
                    {
                        $prefix_var = $prefix[1];
                        $value = number_format( $value / $val, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
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
                $value = number_format( $value, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
            }
            else
            {
                throw new Exception( "Prefix \"$prefix\" for unit \"$unit\" not found" );
            }

            if ( is_array( $prefix_var ) )
            {
                $val = pow( $prefix_base, (int)$prefix_var[0] );
                $value = number_format( $value / $val, $decimalCount, $decimalSymbol, $decimalThousandsSeparator );
                $prefix_var = $prefix_var[1];
            }
        }
        $value = "$value $prefix_var" . $base;
        return $value;
    }

    /**
     * Helper function for eZTemplateUnitOperator which sorts array elements.
     * Sorts on index 0 of $a and $b.
     */
    private static function compareFactor( $a, $b )
    {
        if ( $a[0] == $b[0] )
            return 0;
        return ( $a[0] > $b[0] ) ? -1 : 1;
    }
}

?>
