<?php
//
// Definition of eZDateType class
//
// Created on: <26-Apr-2002 16:53:04 bf>
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
//! The class eZDateType
/*!

*/

include_once( "kernel/classes/ezdatatype.php" );

define( "EZ_DATATYPESTRING_DATE", "ezdate" );
include_once( "lib/ezlocale/classes/ezdate.php" );

class eZDateType extends eZDataType
{
    function eZDateType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_DATE, "Date field" );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $year = $http->postVariable( $base . "_date_year_" . $contentObjectAttribute->attribute( "id" ) );
        $month = $http->postVariable( $base . "_date_month_" . $contentObjectAttribute->attribute( "id" ) );
        $day = $http->postVariable( $base . "_date_day_" . $contentObjectAttribute->attribute( "id" ) );
        $date = $year.$month.$day;
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        if( ( $classAttribute->attribute( "is_required" ) == false ) &&  ( $date == "" ) )
        {
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }
        if ( preg_match( "#^[1-2]{1}[0-9]{3}[0-9]{1,2}[0-9]{1,2}$#", $date ) )
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $year = $http->postVariable( $base . "_date_year_" . $contentObjectAttribute->attribute( "id" ) );
        $month = $http->postVariable( $base . "_date_month_" . $contentObjectAttribute->attribute( "id" ) );
        $day = $http->postVariable( $base . "_date_day_" . $contentObjectAttribute->attribute( "id" ) );
        $date = new eZDate();
        $date->setMDY( $month, $day, $year );
        $contentObjectAttribute->setAttribute( "data_int", $date->timeStamp() );
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $date = new eZDate( );
        $date->setTimeStamp( $contentObjectAttribute->attribute( 'data_int' ) );
        return $date;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    /*!
     Returns the date.
    */
    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }
}

eZDataType::register( EZ_DATATYPESTRING_DATE, "ezdatetype" );

?>
