<?php
//
// Definition of eZPaymentLogger class
//
// Created on: <11-Jun-2004 14:18:58 dl>
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

/*! \file ezpaymentlogger.php
*/

/*!
  \class eZPaymentLogger
*/

class eZPaymentLogger
{
    function eZPaymentLogger( $fileName, $mode )
    {
        $this->file = fopen( $fileName, $mode );
    }

    static function CreateNew($fileName)
    {
        return new eZPaymentLogger( $fileName, "wt" );
    }

    static function CreateForAdd($fileName)
    {
        return new eZPaymentLogger( $fileName, "a+t" );
    }

    function writeString( $string, $label='' )
    {
        if( $this->file )
        {
            if ( is_object( $string ) || is_array( $string ) )
                $string = eZDebug::dumpVariable( $string );

            if( $label == '' )
                fputs( $this->file, $string."\r\n" );
            else
                fputs( $this->file, $label . ': ' . $string."\r\n" );
        }
    }

    function writeTimedString( $string, $label='' )
    {
        if( $this->file )
        {
            $time = $this->getTime();

            if ( is_object( $string ) || is_array( $string ) )
                $string = eZDebug::dumpVariable( $string );

            if( $label == '' )
                fputs( $this->file, $time. '  '. $string. "\n" );
            else
                fputs( $this->file, $time. '  '. $label. ': '. $string. "\n" );
        }
    }

    static function getTime()
    {
        $time = strftime( "%d-%m-%Y %H-%M" );
        return $time;
    }

    public $file;
}
?>
