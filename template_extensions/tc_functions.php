<?php

class TemplateConversionFunctions implements ezcTemplateCustomFunction
{

    public static $runtime;

    public static function getCustomFunctionDefinition($name)
    {
        if( method_exists(__CLASS__, $name) )
        {
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = __CLASS__;
                $def->method = $name;
                $def->variableArgumentList = true;
                return $def;
        }


    }

    public static function tc_is_set($var)
    {
        return isset($var);
    }

    public static function tc_is_set_access( $uniqueName, $in )
    {
        $allArguments = func_get_args();
        $allArguments = array_merge( array( true ), $allArguments );
        return call_user_func_array( array( __CLASS__, 'tc_access_private' ), $allArguments );
    }

    public static function tc_access( $uniqueName, $in )
    {
        $allArguments = func_get_args();
        $allArguments = array_merge( array( false ), $allArguments );
        return call_user_func_array( array( __CLASS__, 'tc_access_private' ), $allArguments );
    }

    private static function dump( $var )
    {
        return htmlspecialchars( var_export( $var, true ) );
    }

    public static function tc_access_private( $checkOnly, $uniqueName, $in )
    {
        $allArguments = func_get_args();
        $parameters = array_slice( $allArguments, 3 );
        
        $input = $in;
        $log = "#";
        foreach($parameters as $p)
        {
            if( is_object($in) )
            {
                if( isset($in->$p) )
                {
                    $in = $in->$p;
                }
                else if( method_exists($in, 'hasAttribute') &&
                         $in->hasAttribute($p) &&
                         method_exists($in, 'attribute') )
                { /* JB-TODO: This is a temporary fix until all classes are converted to __get/__set */
                    ezpTemplateFunctions::runtimeError( "{$uniqueName}: Property {$p} does not have a __get() method, but does have hasAttribute(): \$in->{$p}\n\$in=" . self::dump( $in ) );
                    $in = $in->attribute($p);
                }
                else
                {
                    if ( !$checkOnly )
                        ezpTemplateFunctions::runtimeError( "{$uniqueName}: Access failed on property {$p}: \$in->{$p}\n\$in=" . self::dump( $in ) );

                    $in = null;
                }
                $log .= "->%" ;
            }
            else if ( is_string($in) )
            {
                if ( !is_numeric($in) )
                {
                    if ( !$checkOnly )
                    {
                        ezpTemplateFunctions::runtimeError( "{$uniqueName}: Access failed on string index which is not a number " . self::dump( $p ) . " : \$in[" . self::dump( $p ) . "]\n\$in=" . self::dump( $in ) );
                    }
                    $in = null;
                    return $in;
                }
                else if( isset($in[$p] ) )
                {
                    $in = $in[$p];
                }
                else
                {
                    if ( !$checkOnly )
                        ezpTemplateFunctions::runtimeError( "{$uniqueName}: Access failed on array key " . self::dump( $p ) . " : \$in[" . self::dump( $p ) . "]\n\$in=" . self::dump( $in ) );
                    $in = null;
                }
                $log .= "[#]";
            }
            else
            {
                if( isset($in[$p] ) )
                {
                    $in = $in[$p];
                }
                else
                {
                    if ( !$checkOnly )
                        ezpTemplateFunctions::runtimeError( "{$uniqueName}: Access failed on array key " . self::dump( $p ) . " : \$in[" . self::dump( $p ) . "]\n\$in=" . self::dump( $in ) );
                    $in = null;
                }
                $log .= "[#]";
            }

        }

        self::$runtime->add( $uniqueName, $log );
        return $in;
    }

    public static function func_access( $in, $remaining )
    {
        $allArguments = func_get_args();
        $parameters = array_slice( $allArguments, 1 );
 
        foreach($parameters as $p)
        {
            if( is_object($in) )
            {
                $in = $in->$p;
            }
            else
            {
                $in = $in[$p];
            }
        }

        return $in;
    }


    public static function tc_make_array( $uniqueName, $value )
    {
        if( is_array($value) )
        {
            self::$runtime->add( $uniqueName, "#" );
            return $value;
        }
        else if ( is_string( $value ) )
        {
            self::$runtime->add( $uniqueName, "str_split(#, \"\")" );
            return str_split( $value );
        }
        else if ( is_numeric( $value ) )
        {
            self::$runtime->add( $uniqueName, "0..#" );
            return range(0, $value);
        }
        else
        {
            ezpTemplateFunctions::runtimeError("tc_make_array( $uniqueName, ..) did not get a array, string, or numeric value.");
            self::$runtime->add( $uniqueName, "INVALID VALUE" );
            return array();
        }
    }


    public static function tc_append($uniqueName, $remaining)
    {
        $paramArray = array_slice(func_get_args(), 1);

        if( is_array($paramArray[0]) )
        {
            $in = $paramArray[0];

            $out = array();
            foreach( array_slice($paramArray, 1) as $p )
            {
                $in[] = $p;
            }

            self::$runtime->add( $uniqueName, "array_append(#". str_repeat(", #", sizeof($paramArray) - 1).")" );
            return $in;
        }
        else
        {
            $out = "";
            foreach( $paramArray as $p)
            {
                $out .= $p;
            }

            self::$runtime->add( $uniqueName, "#". str_repeat(". #", sizeof($paramArray) - 1) );
            return $out;
        }
    }
 
    public static function tc_count($uniqueName, $remaining)
    {
        $paramArray = array_slice(func_get_args(), 1);
        if( is_array($paramArray[0]) )
        {
            self::$runtime->add( $uniqueName, "array_count(#)" );
            return sizeof( $paramArray[0]);
        }
        elseif( is_string($paramArray[0]) )
        {
            self::$runtime->add( $uniqueName, "str_len(#)" );
            return strlen( $paramArray[0]);
        }
    }

    public static function tc_contains($uniqueName, $remaining)
    {
        $paramArray = array_slice(func_get_args(), 1);
        if( is_array($paramArray[0]) )
        {
            self::$runtime->add( $uniqueName, "array_contains(#, #)" );
            // in_array( needle, haystack);
            return in_array( $paramArray[1], $paramArray[0]);
        }
        elseif( is_string($paramArray[0]) )
        {
            self::$runtime->add( $uniqueName, "str_contains(#, #)" );
            // strpos(haystack, needle);
            return strpos( $paramArray[0], $parameter[1]) !== false;
        }
    }

    public static function tc_repeat($uniqueName, $val, $count)
    {
        if ( is_array( $val ) )
        {
            self::$runtime->add( $uniqueName, "array_repeat(#, #)" );

            $out = array(); 
            for( $i = 0; $i < $count; ++$i)
            {
                $out = array_merge( $out, $val );
            }

            return $out;
        }
        else
        {
            self::$runtime->add( $uniqueName, "str_repeat(#, #)" );
            return str_repeat($val, $count);
        }
    }
 
    public static function tc_prepend($uniqueName, $lhs, $rhs)
    {
        if ( is_array( $lhs ) )
        {
            self::$runtime->add( $uniqueName, "array_prepend(#, #)" );
            return array_merge($rhs, $lhs);
        }
        else
        {
            self::$runtime->add( $uniqueName, "str_prepend(#, #)" );
            return $rhs . $lhs;
        }
    }


    // XXX Move to separate file.
    public static function array_starts_with($lhs, $rhs)
    {
        return array_slice($lhs, 0, sizeof($rhs)) == $rhs;
    } 


    public static function tc_starts_with($uniqueName, $lhs, $rhs)
    {
        if ( is_array( $lhs ) )
        {
            self::$runtime->add( $uniqueName, "array_starts_with(#, #)" );
            return self::array_starts_with($lhs, $rhs);
        }
        else
        {
            self::$runtime->add( $uniqueName, "str_starts_with(#, #)" );
            return substr($lhs, 0, strlen($rhs)) == $rhs;
        }
    }

    // XXX Move to separate file.
    public static function array_ends_with($lhs, $rhs)
    {
        return array_slice($lhs, -sizeof($rhs)) == $rhs;
    } 


    public static function tc_ends_with($uniqueName, $lhs, $rhs)
    {
        if ( is_array( $lhs ) )
        {
            self::$runtime->add( $uniqueName, "array_ends_with(#, #)" );
            return self::array_ends_with($lhs, $rhs);
        }
        else
        {
            self::$runtime->add( $uniqueName, "str_ends_with(#, #)" );
            return substr($lhs, -strlen($rhs)) == $rhs;
        }
    }


    // XXX Move to separate file.
    public static function array_explode($array, $index)
    {
        return array(array_slice($array, 0, $index), array_slice($array, $index));
    } 


    public static function tc_explode($uniqueName, $lhs, $rhs)
    {
        if ( is_array( $lhs ) )
        {
            self::$runtime->add( $uniqueName, "array_explode(#, #)" );
            return self::array_explode($lhs, $rhs);
        }
        else
        {
            self::$runtime->add( $uniqueName, "str_split(#, #)" );
            return explode($rhs, $lhs);
        }
    }

    public static function tc_reverse($uniqueName, $operand)
    {
        if ( is_array( $operand ) )
        {
            self::$runtime->add( $uniqueName, "array_reverse(#)" );
            return array_reverse($operand);
        }
        else
        {
            self::$runtime->add( $uniqueName, "str_reverse(#)" );
            return strrev($rhs, $operand);
        }
    }

    /* JB-TODO: Replaces 'extract' function. */
    public static function tc_var_mid($uniqueName, $input, $offset, $len = false)
    {
        if ( is_array( $input ) )
        {
            if ( $len === false )
            {
                // JB: Temporarily disabled, only enable when array_mid in ezcTemplate is fixed to allow optional length
                // self::$runtime->add( $uniqueName, "array_mid(#, #)" );
                return array_slice($input, $offset);
            }
            else
            {
                self::$runtime->add( $uniqueName, "array_mid(#, #, #)" );
                return array_slice($input, $offset, $len);
            }
        }
        else if ( is_string( $input ) )
        {
            if ( $len === false )
            {
                // JB: Temporarily disabled, only enable when array_mid in ezcTemplate is fixed to allow optional length
                // self::$runtime->add( $uniqueName, "str_mid(#, #)" );
                return substr($input, $offset);
            }
            else
            {
                self::$runtime->add( $uniqueName, "str_mid(#, #, #)" );
                return substr($input, $offset, $len);
            }
        }
        else
        {
            throw new ezcTemplateRuntimeException( "tc_var_mid: Input must either by array or string, got " . gettype( $input ) );
        }
    }

    /* JB-TODO: Replaces 'extract_left' function. */
    public static function tc_var_left($uniqueName, $input, $len)
    {
        if ( is_array( $input ) )
        {
            self::$runtime->add( $uniqueName, "array_left(#, #)" );
            return array_slice($input, 0, $len);
        }
        else if ( is_string( $input ) )
        {
            self::$runtime->add( $uniqueName, "str_left(#, #)" );
            return substr($input, 0, $len);
        }
        else
        {
            throw new ezcTemplateRuntimeException( "tc_var_left: Input must either by array or string, got " . gettype( $input ) );
        }
    }

    /* JB-TODO: Replaces 'extract_right' function. */
    public static function tc_var_right($uniqueName, $input, $len)
    {
        if ( is_array( $input ) )
        {
            self::$runtime->add( $uniqueName, "array_right(#, #)" );
            return array_slice($input, count($input) - $len, $len);
        }
        else if ( is_string( $input ) )
        {
            self::$runtime->add( $uniqueName, "str_right(#, #)" );
            return substr($input, strlen($input) - $len, $len);
        }
        else
        {
            throw new ezcTemplateRuntimeException( "tc_var_right: Input must either by array or string, got " . gettype( $input ) );
        }
    }

    /* JB-TODO: Temporary function for 'wash'. */
    public static function tc_wash($uniqueName, $input, $type = 'xhtml')
    {
        if ( $type == 'xhtml' )
        {
            self::$runtime->add( $uniqueName, "#" );
            return $input;
        }
        elseif ( $type == 'email' )
        {
            self::$runtime->add( $uniqueName, "escape_email(#)");
            return TemplateEscapeBlock::escape_email($input);
        }
        else
        {
            throw new ezcTemplateRuntimeException( "tc_wash: The wash type '" . $type . "' must be manually fixed in the template code." );
        }
    }
}


?>
