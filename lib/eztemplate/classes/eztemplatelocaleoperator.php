<?php
//
// Definition of eZTemplateLocaleOperator class
//
// Created on: <01-Mar-2002 13:49:40 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*!
  \class eZTemplateLocaleOperator eztemplatelocaleoperator.php
  \ingroup eZTemplateOperators
  \brief Locale aware conversions and output using operator "l10n"

  This class takes care of converting variables and displaying them
  according to their locale settings.
  The class has one operator called l10n (short for localization) which
  takes one parameter which is localization type.
  Supported types are time, shorttime, date, shortdate, currency and number.

\code
// Example template code
{$curdate|l10n(date)}
{$cash|l10n(currency)}
\endcode
*/

include_once( "lib/ezlocale/classes/ezlocale.php" );

class eZTemplateLocaleOperator
{
    /*!
     Initializes the object with the default locale.
     \note Add support for specifying the locale object.
    */
    function eZTemplateLocaleOperator()
    {
        $this->Operators = array( "l10n", 'currentdate' );
        $this->Locale =& eZLocale::instance();
    }

    /*!
     Returns array with l10n.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( 'l10n' => array( "type" => array( "type" => "string",
                                                        "required" => true,
                                                        "default" => false ) ) );
    }

    /*!
     Converts the variable according to the locale type.
     Allowed types are:
     - time
     - shorttime
     - date
     - shortdate
     - currency
     - number
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$value, &$namedParameters )
    {
        if ( $operatorName == 'currentdate' )
        {
            include_once( 'lib/ezlocale/classes/ezdatetime.php' );
            $value = eZDateTime::currentTimestamp();
        }
        else
        {
            $type = $namedParameters["type"];
            if ( $type === null )
                return;
            switch ( $type )
            {
                case "time":
                {
                    $value = $this->Locale->formatTime( $value );
                } break;

                case "shorttime":
                {
                    $value = $this->Locale->formatShortTime( $value );
                } break;

                case "date":
                {
                    $value = $this->Locale->formatDate( $value );
                } break;

                case "shortdate":
                {
                    $value = $this->Locale->formatShortDate( $value );
                } break;

                case "datetime":
                {
                    $value = $this->Locale->formatDateTime( $value );
                } break;

                case "shortdatetime":
                {
                    $value = $this->Locale->formatShortDateTime( $value );
                } break;

                case "currency":
                {
                    $value = $this->Locale->formatCurrency( $value );
                } break;

                case "number":
                {
                    $value = $this->Locale->formatNumber( $value );
                } break;

                default:
                    $tpl->error( $operatorName, "Unknown locale type: '$type'" );
                break;
            }
        }
    }

    /// The operator array
    var $Operators;
    /// A reference to the locale object
    var $Locale;
}

?>
