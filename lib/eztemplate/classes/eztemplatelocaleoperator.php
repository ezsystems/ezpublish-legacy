<?php
//
// Definition of eZTemplateLocaleOperator class
//
// Created on: <01-Mar-2002 13:49:40 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
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
    function eZTemplateLocaleOperator()
    {
        $this->Operators = array( 'l10n', 'locale', 'datetime', 'currentdate', 'maketime', 'makedate', 'gettime' );
        $this->LocaleName = 'l10n';
        $this->LocaleFetchName = 'locale';
        $this->DateTimeName = 'datetime';
        $this->CurrentDateName = 'currentdate';
        $this->MakeTimeName = 'maketime';
        $this->MakeDateName = 'makedate';
        $this->GetTimeName = 'gettime';
    }

    /*!
     Returns array with l10n.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     Returns a list with hints for the template compiler.
    */
    function operatorTemplateHints()
    {
        $hints = array(
            $this->LocaleName      => array( 'input' => true, 'output' => true, 'parameters' => true,
                                             'transform-parameters' => true, 'input-as-parameter' => 'always',
                                             'element-transformation' => true,
                                             'element-transformation-func' => 'l10nTransformation' ),
            $this->LocaleFetchName      => array( 'input' => true, 'output' => true, 'parameters' => true,
                                                  'transform-parameters' => true, 'input-as-parameter' => 'always',
                                                  'element-transformation' => false ),
            $this->DateTimeName    => array( 'input' => true, 'output' => true, 'parameters' => true,
                                             'transform-parameters' => true, 'input-as-parameter' => 'always',
                                             'element-transformation' => true,
                                             'element-transformation-func' => 'dateTimeTransformation' ),
            $this->CurrentDateName => array( 'input' => false, 'output' => true, 'parameters' => false,
                                             'transform-parameters' => true, 'input-as-parameter' => false,
                                             'element-transformation' => true,
                                             'element-transformation-func' => 'currentDateTransformation' ),
            $this->MakeTimeName    => array( 'input' => true, 'output' => true, 'parameters' => true,
                                             'transform-parameters' => true, 'input-as-parameter' => false,
                                             'element-transformation' => true,
                                             'element-transformation-func' => 'makeDateTimeTransformation' ),
            $this->MakeDateName    => array( 'input' => true, 'output' => true, 'parameters' => true,
                                             'transform-parameters' => true, 'input-as-parameter' => false,
                                             'element-transformation' => true,
                                             'element-transformation-func' => 'makeDateTimeTransformation' ),
            $this->GetTimeName     => array( 'input' => true, 'output' => true, 'parameters' => 1,
                                             'transform-parameters' => true, 'input-as-parameter' => 'always',
                                             'element-transformation' => true,
                                             'element-transformation-func' => 'getTimeTransformation' ),
        );
        return $hints;
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
        return array( 'l10n' =>     array( 'type' =>      array( 'type' => 'string',  'required' => true,  'default' => false ) ),
                      'datetime' => array( 'class' =>     array( 'type' => 'string',  'required' => true,  'default' => false ),
                                           'data' =>      array( 'type' => 'mixed',   'required' => false, 'default' => false ) ),
                      'gettime' =>  array( 'timestamp' => array( 'type' => 'integer', 'required' => false, 'default' => false ) ),
                      'maketime' => array( 'hour' =>      array( 'type' => 'integer', 'required' => false, 'default' => false ),
                                           'minute' =>    array( 'type' => 'integer', 'required' => false, 'default' => false ),
                                           'second' =>    array( 'type' => 'integer', 'required' => false, 'default' => false ),
                                           'month' =>     array( 'type' => 'integer', 'required' => false, 'default' => false ),
                                           'day' =>       array( 'type' => 'integer', 'required' => false, 'default' => false ),
                                           'year' =>      array( 'type' => 'integer', 'required' => false, 'default' => false ),
                                           'dst' =>       array( 'type' => 'integer', 'required' => false, 'default' => false ) ),
                      'makedate' => array( 'month' =>     array( 'type' => 'integer', 'required' => false, 'default' => false ),
                                           'day' =>       array( 'type' => 'integer', 'required' => false, 'default' => false ),
                                           'year' =>      array( 'type' => 'integer', 'required' => false, 'default' => false ),
                                           'dst' =>       array( 'type' => 'integer', 'required' => false, 'default' => false ) ) );
    }

    /*!
     Transforms
     */
    function l10nTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                 &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        $newElements = array();

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( 'include_once("lib/ezlocale/classes/ezlocale.php");' . "\n" );
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( '$locale =& eZLocale::instance();' . "\n" );
        $values[] = $parameters[0];

        if ( !eZTemplateNodeTool::isStaticElement( $parameters[1] ) )
        {
            $values[] = $parameters[1];
            $newElements[] = eZTemplateNodeTool::createCodePieceElement( "%tmp1% = \$locale->getFormattingFunction( %2% );\n%output% = \$locale->%tmp1%( %1% );\n", $values, false, 1 );

            return $newElements;
        }
        else
        {
            if ( ( $function = eZTemplateNodeTool::elementStaticValue( $parameters[1] ) ) !== false )
            {
                $locale =& eZLocale::instance();
                $method = $locale->getFormattingFunction( $function );
                if ( $method )
                {
                    $newElements[] = eZTemplateNodeTool::createCodePieceElement( "%output% = \$locale->$method( %1% );\n", $values, false, 1 );
                    return $newElements;
                }
            }
        }
        return false;
    }

    function dateTimeTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                     &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        $newElements = array();
        $paramCount = count( $parameters );
        if ( $paramCount < 2 )
        {
            return false;
        }
        if ( !eZTemplateNodeTool::isStaticElement( $parameters[1] ) )
        {
            return false;
        }
        else
        {
            $class = eZTemplateNodeTool::elementStaticValue( $parameters[1] );
        }
        if ( ( $class == 'custom' ) && ( $paramCount != 3 ) )
        {
            return false;
        }

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( 'include_once("lib/ezlocale/classes/ezlocale.php");' . "\n" );
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( '$locale =& eZLocale::instance();' . "\n" );

        if ( $class == 'custom' )
        {
            $values[] = $parameters[0];
            $values[] = $parameters[2];
            $newElements[] = eZTemplateNodeTool::createCodePieceElement( "%output% = \$locale->formatDateTimeType( %2%, %1% );\n", $values );
            return $newElements;

        }
        else
        {
            $dtINI =& eZINI::instance( 'datetime.ini' );
            $formats = $dtINI->variable( 'ClassSettings', 'Formats' );
            if ( array_key_exists( $class, $formats ) )
            {
                $classFormat = addcslashes( $formats[$class], "'" );
                $values[] = $parameters[0];
                $newElements[] = eZTemplateNodeTool::createCodePieceElement( "%output% = \$locale->formatDateTimeType( '$classFormat', %1% );\n", $values );
                return $newElements;
            }
        }
        return false;
    }

    function currentDateTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                        &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $newElements = array();
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( "%output% = time();\n" );
        return $newElements;
    }

    function makeDateTimeTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                         &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        $arguments = array();
        $newElements = array();
        $paramCount = count( $parameters );

        $code = '%output% = mktime( ';
        if ( $operatorName == 'makedate' )
        {
            $arguments = array ( 0, 0, 0 );
        }
        for ( $i = 0; $i < $paramCount; ++$i )
        {
            if ( $parameters[$i] === null )
            {
                break;
            }
            $values[] = $parameters[$i];
            $arguments[] = '%' . ($i + 1) . '%';
        }
        $code .= implode( ', ', $arguments ) . " );\n";
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function getTimeTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                    &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $newElements = array();
        $values = array();
        $paramCount = count( $parameters );

        if (count( $parameters ) == 1 )
        {
            $values[] = $parameters[0];
            $code = "%tmp1% = %1%;\n";
        }
        else if ( $paramCount == 0 )
        {
            $code = "%tmp1% = time();\n";
        }
        else
        {
            return false;
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement(
            $code .
            "%tmp2% = getdate( %tmp1% );\n".
            "%tmp3% = date( 'W', %tmp1% );\n".
            "if ( %tmp2%['wday'] == 0 )\n{\n\t++%tmp3%;\n}\n".
            "%output% = array( 'seconds' => %tmp2%['seconds'],
              'minutes' => %tmp2%['minutes'],
              'hours' => %tmp2%['hours'],
              'day' => %tmp2%['mday'],
              'month' => %tmp2%['mon'],
              'year' => %tmp2%['year'],
              'weekday' => %tmp3%,
              'yearday' => %tmp2%['yday'],
              'epoch' => %tmp2%[0] );\n", $values, false, 3);
        return $newElements;
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
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters,
                     $placement )
    {
        if ( $operatorName == $this->LocaleFetchName )
        {
            if ( $operatorValue !== null )
            {
                $localeString = $operatorValue;
            }
            else
            {
                if ( count( $operatorParameters ) < 1 )
                {
                    $tpl->missingParameter( $operatorName, 'localestring' );
                    return;
                }
                $localeString = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement, true );
            }
            $locale =& eZLocale::instance( $localeString );
            $operatorValue = $locale;
            return;
        }
        $locale =& eZLocale::instance();
        if ( $operatorName == $this->GetTimeName )
        {
            $timestamp = $operatorValue;
            if ( $timestamp === null )
                $timestamp = $namedParameters['timestamp'];
            $info = getdate( $timestamp );
            $week = date( 'W', $timestamp );
            if ( $info['wday'] == 0 )
                ++$week;
            $operatorValue = array( 'seconds' => $info['seconds'],
                                    'minutes' => $info['minutes'],
                                    'hours' => $info['hours'],
                                    'day' => $info['mday'],
                                    'month' => $info['mon'],
                                    'year' => $info['year'],
                                    'weekday' => $week,
                                    'yearday' => $info['yday'],
                                    'epoch' => $info[0] );
        }
        else if ( $operatorName == $this->MakeTimeName )
        {
            $parameters = array();
            if ( $namedParameters['hour'] !== false )
                $parameters[] = $namedParameters['hour'];
            if ( $namedParameters['minute'] !== false )
                $parameters[] = $namedParameters['minute'];
            if ( $namedParameters['second'] !== false )
                $parameters[] = $namedParameters['second'];
            if ( $namedParameters['month'] !== false )
                $parameters[] = $namedParameters['month'];
            {
                if ( $namedParameters['day'] !== false )
                    $parameters[] = $namedParameters['day'];
                {
                    if ( $namedParameters['year'] !== false )
                        $parameters[] = $namedParameters['year'];
                    {
                        if ( $namedParameters['dst'] !== false )
                            $parameters[] = $namedParameters['dst'];
                    }
                }
            }
            $operatorValue = call_user_func_array( 'mktime', $parameters );
        }
        else if ( $operatorName == $this->MakeDateName )
        {
            $parameters = array( 0, 0, 0 );
            if ( $namedParameters['month'] !== false )
                $parameters[] = $namedParameters['month'];
            {
                if ( $namedParameters['day'] !== false )
                    $parameters[] = $namedParameters['day'];
                {
                    if ( $namedParameters['year'] !== false )
                        $parameters[] = $namedParameters['year'];
                    {
                        if ( $namedParameters['dst'] !== false )
                            $parameters[] = $namedParameters['dst'];
                    }
                }
            }
            $operatorValue = call_user_func_array( 'mktime', $parameters );
        }
        else if ( $operatorName == $this->CurrentDateName )
        {
            $operatorValue = time();
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
                    $tpl->error( $operatorName, "DateTime class '$class' is not defined", $placement );
            }
        }
        else if ( $operatorName == $this->LocaleName )
        {
            $type = $namedParameters['type'];
            if ( $type === null )
                return;
            $method = $locale->getFormattingFunction( $type );
            if ( $method )
            {
                $operatorValue = $locale->$method( $operatorValue );
            }
            else
            {
                $tpl->error( $operatorName, "Unknown locale type: '$type'", $placement );
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
