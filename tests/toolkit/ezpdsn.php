<?php
/**
 * File containing the ezpDsn class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

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
        {
            $parts['host'] = $parts['hostspec'];
            $parts['server'] = $parts['hostspec'];
        }

        if ( isset( $parts['username'] ) )
            $parts['user'] = $parts['username'];

        return $parts;
    }
}

?>