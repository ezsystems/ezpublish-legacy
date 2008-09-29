<?php 

class ezpDsn
{
    public function __construct( $dsn )
    {
        $this->dsn = $dsn;
    }

    public function __get( $name )
    {
        switch ( $name )
        {
            case 'parts':
                return $this->parts;
            default:
                return $this->parts[$name];
        }
    }

    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'dsn':
                $this->dsn = $value;
                $this->parts = self::parseDSN( $value );
                break;
            default:
                $this->$name = $value;
        }
    }

    static public function parseDSN( $dsn )
    {
        $parts = ezcDbFactory::parseDSN( $dsn );

        if ( isset( $parts['hostspec'] ) )
            $parts['host'] = $parts['hostspec'];

        if ( isset( $parts['username'] ) )
            $parts['user'] = $parts['username'];

        return $parts;
    }
}

?>