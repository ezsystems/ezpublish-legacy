<?php

class ezpRomanNumber
{
    private $value;
    private $number;

    public function __construct( $value )
    {
        $this->value = $value;
    }

    public function __tostring()
    {
        if ( $this->number !== null )
            return $this->number;
        $this->number = self::generate( $this->value );
        return $this->number;
    }

    public function __get( $name )
    {
        switch ( $name )
        {
            case 'value':
                return $this->value;
            case 'number':
                if ( $this->number !== null )
                    return $this->number;
                return $this->__tostring();
        }
        throw new ezcBasePropertyNotFoundException( $name );
    }

    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'value':
                $this->value = $value;
                $this->number = null;
                return;
            case 'number':
                throw new ezcBasePropertyPermissionException( $name, ezcBasePropertyPermissionException::READ );
        }
        throw new ezcBasePropertyNotFoundException( $name );
    }

    public function __unset( $name )
    {
        switch ( $name )
        {
            case 'value':
            case 'number':
                throw new ezcBasePropertyPermissionException( $name, ezcBasePropertyPermissionException::READ );
        }
        throw new ezcBasePropertyNotFoundException( $name );
    }

    public function __isset( $name )
    {
        return in_array( array( 'value', 'number' ), $name );
    }

    public static function generate( $value )
    {
        if ( $value >= 1000 )
            return 'M' . self::generate( $value - 1000 );
        if ( $value >= 500 )
        {
            if ( $value >= 900 )
                return 'CM' . self::generate( $value - 900 );
            else
                return 'D' . self::generate( $value - 500 );
        }
        if ( $value >= 100 )
        {
            if( $value >= 400 )
                return 'CD' . self::generate( $value - 400 );
            else
                return 'C' . self::generate( $value - 100 );
        }
        if ( $value >= 50 )
        {
            if( $value >= 90 )
                return 'XC' . self::generate( $value - 90 );
            else
                return 'L' . self::generate( $value - 50 );
        }
        if ( $value >= 10 )
        {
            if( $value >= 40 )
                return 'XL' . self::generate( $value - 40 );
            else
                return 'X' . self::generate( $value - 10 );
        }
        if ( $value >= 5 )
        {
            if( $value == 9 )
                return 'IX' . self::generate( $value - 9 );
            else
                return 'V' . self::generate( $value - 5 );
        }
        if ( $value >= 1 )
        {
            if( $value == 4 )
                return 'IV' . self::generate( $value - 4 );
            else
                return 'I' . self::generate( $value - 1 );
        }
        return '';
    }
}

?>
