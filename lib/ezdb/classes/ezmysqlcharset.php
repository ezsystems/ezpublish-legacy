<?php
/**
 * File containing the eZMySQLCharset class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package lib
 * @since 4.4
 */

abstract class eZMySQLCharset
{
    /**
     * Mapping between internal charset and MySQL ones.
     * @var array
     */
    protected static $mapping = array(
        'iso-8859-1' => 'latin1',
        'iso-8859-2' => 'latin2',
        'iso-8859-8' => 'hebrew',
        'iso-8859-7' => 'greek',
        'iso-8859-9' => 'latin5',
        'iso-8859-13' => 'latin7',
        'windows-1250' => 'cp1250',
        'windows-1251' => 'cp1251',
        'windows-1256' => 'cp1256',
        'windows-1257' => 'cp1257',
        'utf-8' => 'utf8',
        'koi8-r' => 'koi8r',
        'koi8-u' => 'koi8u'
    );

    /**
     * Mapping between MySQL charset and internal ones.
     * @var array
     */
    protected static $reverseMapping = array(
        'latin1' => 'iso-8859-1',
        'latin2' => 'iso-8859-2',
        'hebrew' => 'iso-8859-8',
        'greek' => 'iso-8859-7',
        'latin5' => 'iso-8859-9',
        'latin7' => 'iso-8859-13',
        'cp1250' => 'windows-1250',
        'cp1251' => 'windows-1251',
        'cp1256' => 'windows-1256',
        'cp1257' => 'windows-1257',
        'utf8' => 'utf-8',
        'koi8r' => 'koi8-r',
        'koi8u' => 'koi8-u'
    );

    /**
     * Maps an internal charset to one understood by MySQL.
     * If the charset is unknown, it will be returned as is.
     *
     * @param string $charset Charset to map.
     *
     * @return string The converted charset.
     *
     * @since 4.4
     */
    public static function mapTo( $charset )
    {
        $lowerCharset = strtolower( $charset );
        return isset( self::$mapping[$lowerCharset] ) ? self::$mapping[$lowerCharset] : $charset;
    }

    /**
     * Maps a MySQL charset to an internal one.
     * If the charset is unknown, it will be returned as is.
     *
     * @param string $charset Charset to map.
     *
     * @return string The converted charset.
     *
     * @since 4.4
     */
    public static function mapFrom( $charset )
    {
        $lowerCharset = strtolower( $charset );
        return isset( self::$reverseMapping[$lowerCharset] ) ? self::$reverseMapping[$lowerCharset] : $charset;
    }
}
?>
