<?php
//
// Definition of eZTemplateLocaleOperator class
//
// Created on: <01-Mar-2002 13:49:40 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
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
include_once( 'lib/ezlocale/classes/ezdatetime.php' );

class eZTemplateLocaleOperator
{
    /*!
     Initializes the object with the default locale.
     \note Add support for specifying the locale object.
    */
    function eZTemplateLocaleOperator( $localeName = 'l10n', $dateTimeName = 'datetime',
                                       $currentDateName = 'currentdate' )
    {
        $this->Operators = array( $localeName, $dateTimeName,
                                  $currentDateName );
        $this->LocaleName = $localeName;
        $this->DateTimeName = $dateTimeName;
        $this->CurrentDateName = $currentDateName;
//         $this->Locale =& eZLocale::instance();
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
        return array( 'l10n' => array( 'type' => array( 'type' => 'string',
                                                        'required' => true,
                                                        'default' => false ) ),
                      'datetime' => array( 'class' => array( 'type' => 'string',
                                                             'required' => true,
                                                             'default' => false ),
                                           'data' => array( 'type' => 'mixed',
                                                            'required' => false,
                                                            'default' => false ) ) );
    }

    /*!
     Converts the variable according to the locale type.
     Allowed types are:
     - time
     - shorttime
     - date
     - shortdate
     - currency
     - clean_currency
     - number
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $locale =& eZLocale::instance();
        if ( $operatorName == $this->CurrentDateName )
        {
            $operatorValue = eZDateTime::currentTimestamp();
        }
        else if ( $operatorName == $this->DateTimeName )
        {
            $class = $namedParameters['class'];
            if ( $class === null )
                return;
            if ( $class == 'custom' )
            {
                $operatorValue = $locale->formatDateTimeType( $namedParameters['data'], $operatorValue );
            }
            else
            {
                $dtINI =& eZINI::instance( 'datetime.ini' );
                $formats = $dtINI->variable( 'ClassSettings', 'Formats' );
                if ( array_key_exists( $class, $formats ) )
                {
                    $classFormat = $formats[$class];
                    $operatorValue = $locale->formatDateTimeType( $classFormat, $operatorValue );
                }
                else
                    $tpl->error( $operatorName, "DateTime class '$class' is not defined" );
            }
        }
        else if ( $operatorName == $this->LocaleName )
        {
            $type = $namedParameters['type'];
            if ( $type === null )
                return;
            switch ( $type )
            {
                case 'time':
                {
                    $operatorValue = $locale->formatTime( $operatorValue );
                } break;

                case 'shorttime':
                {
                    $operatorValue = $locale->formatShortTime( $operatorValue );
                } break;

                case 'date':
                {
                    $operatorValue = $locale->formatDate( $operatorValue );
                } break;

                case 'shortdate':
                {
                    $operatorValue = $locale->formatShortDate( $operatorValue );
                } break;

                case 'datetime':
                {
                    $operatorValue = $locale->formatDateTime( $operatorValue );
                } break;

                case 'shortdatetime':
                {
                    $operatorValue = $locale->formatShortDateTime( $operatorValue );
                } break;

                case 'currency':
                {
                    $operatorValue = $locale->formatCurrency( $operatorValue );
                } break;

                case 'clean_currency':
                {
                    $operatorValue = $locale->formatCleanCurrency( $operatorValue );
                } break;

                case 'number':
                {
                    $operatorValue = $locale->formatNumber( $operatorValue );
                } break;

                default:
                    $tpl->error( $operatorName, "Unknown locale type: '$type'" );
                break;
            }
        }
    }

    /// \privatesection
    /// The operator array
    var $Operators;
    /// A reference to the locale object
    var $Locale;

    var $LocaleName;
    var $DateTimeName;
    var $CurrentDateName;
}

?>
