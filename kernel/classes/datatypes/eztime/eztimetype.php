<?php
//
// Definition of eZTimeType class
//
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

//!! eZKernel
//! The class eZTimeType
/*!

*/
include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezlocale/classes/eztime.php" );

define( "EZ_DATATYPESTRING_TIME", "eztime" );
class eZTimeType extends eZDataType
{
    function eZTimeType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_TIME, "Time" );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $hour = $http->postVariable( $base . "_time_hour_" . $contentObjectAttribute->attribute( "id" ) );
        $minute = $http->postVariable( $base . "_time_minute_" . $contentObjectAttribute->attribute( "id" ) );

        $time = new eZTime();
        $time->setHMS( $hour, $minute, 0 );

        $contentObjectAttribute->setAttribute( "data_int", $time->timeStamp() );
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$attr )
    {
        $time = new eZTime( );
        $time->setTimeStamp( $attr->attribute( 'data_int' ) );

        return $time;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     Returns the date.
    */
    function title( &$contentObjectAttribute )
    {
        return "";
    }
}
eZDataType::register( EZ_DATATYPESTRING_TIME, "eztimetype" );

?>
