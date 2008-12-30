<?php
//
// Definition of eZDateOperatorCollection class
//
// Created on: <07-Feb-2003 09:39:55 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

class eZDateOperatorCollection
{
    function eZDateOperatorCollection( $monthName = 'month_overview' )
    {
        $this->MonthOverviewName = $monthName;
        $this->Operators = array( $monthName );
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
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
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'month_overview' => array( 'field' => array( 'type' => 'string',
                                                                   'required' => true,
                                                                   'default' => false ),
                                                 'date' => array( 'type' => 'integer',
                                                                  'required' => true,
                                                                  'default' => false ),
                                                 'optional' => array( 'type' => 'array',
                                                                      'required' => false,
                                                                      'default' => false ) ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $locale = eZLocale::instance();
        if ( $operatorName == $this->MonthOverviewName )
        {
            $field = $namedParameters['field'];
            $date = $namedParameters['date'];
            if ( !$field )
                return $tpl->missingParameter( $operatorName, 'field' );
            if ( !$date )
                return $tpl->missingParameter( $operatorName, 'date' );
            $optional = $namedParameters['optional'];
            $dateInfo = getdate( $date );
            if ( is_array( $operatorValue ) )
            {
                $month = array();
                $month['year'] = $dateInfo['year'];
                $month['month'] = $locale->longMonthName( $dateInfo['mon'] );
                $weekDays = $locale->weekDays();
                $weekDaysMap = array();
                $i = 0;
                $dayNames = array( 0 => 'sun', 1 => 'mon', 2 => 'tue',
                                   3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat' );
                foreach ( $weekDays as $weekDay )
                {
                    $weekDaysMap[$weekDay] = $i;
                    $weekDayName = $locale->shortDayName( $weekDay );
                    $weekDayIdentifier = $dayNames[$weekDay];
                    $month['weekdays'][] = array( 'day' => $weekDayName,
                                                  'class' => $weekDayIdentifier );
                    ++$i;
                }
                $days = array();
                $lastDay = getdate( mktime( 0, 0, 0, $dateInfo['mon']+1, 0, $dateInfo['year'] ) );
                $lastDay = $lastDay['mday'];
                for ( $day = 1; $day <= $lastDay; ++$day )
                {
                    $days[$day] = false;
                }
                foreach ( $operatorValue as $item )
                {
                    $value = null;
                    if ( is_object( $item ) and
                         method_exists( $item, 'hasattribute' ) and
                         method_exists( $item, 'attribute' ) )
                    {
                        if ( $item->hasAttribute( $field ) )
                            $value = $item->attribute( $field );
                    }
                    else if ( is_array( $item ) )
                    {
                        if ( array_key_exists( $field, $item ) )
                            $value = $item[$field];
                    }
                    if ( $value !== null )
                    {
                        $info = getdate( $value );
                        if ( $info['year'] == $dateInfo['year'] and
                             $info['mon'] == $dateInfo['mon'] )
                        {
                            $days[$info['mday']] = true;
                        }
                    }
                }
                $currentDay = false;
                if ( isset( $optional['current'] ) and $optional['current'] !== false )
                {
                    $info = getdate( $optional['current'] );
                    $currentDay = $info['yday'];
                }
                $today = time();
                $todayInfo = getdate( $today );
                $todayClass = false;
                if ( isset( $optional['today_class'] ) )
                    $todayClass = $optional['today_class'];
                $dayClass = false;
                if ( isset( $optional['day_class'] ) )
                    $dayClass = $optional['day_class'];
                $link = false;
                if ( isset( $optional['link'] ) )
                    $link = $optional['link'];
                $yearLinkParameter = false;
                $monthLinkParameter = false;
                $dayLinkParameter = false;
                if ( isset( $optional['year_link'] ) )
                    $yearLinkParameter = $optional['year_link'];
                if ( isset( $optional['month_link'] ) )
                    $monthLinkParameter = $optional['month_link'];
                if ( isset( $optional['day_link'] ) )
                    $dayLinkParameter = $optional['day_link'];
                $weeks = array();
                $lastWeek = 0;
                for ( $day = 1; $day <= $lastDay; ++$day )
                {
                    $timestamp = mktime( 0, 0, 0, $dateInfo['mon'], $day, $dateInfo['year'] );
                    $info = getdate( $timestamp );
                    $weekDay = $weekDaysMap[$info['wday']];

                    /*
                     * Attention: date('W') returns the week number according to
                     * ISO, which states that the week with the first Thursday
                     * in the new year is week 1.
                     */
                    $week = date( 'W', $timestamp );

                    if ( $weekDay == 0 && $weekDaysMap[0] == 0 )
                    {
                        ++$week;
                    }

                    /*
                     * This checks for a year switch within a week. Routine
                     * takes care that first days in January might still belong
                     * to the last week of the old year (according to ISO week
                     * number), thus be part of week 52 or 53.
                     */
                    if ($week >= 52 || $week == 1)
                    {
                        // See if it's a new year by comparing the year of the previous week with the
                        // current one.
                        $timestampPrevWeek = mktime( 0, 0, 0, $dateInfo['mon'], $day-7, $dateInfo['year'] );
                        $isNewYear = date('Y', $timestampPrevWeek) < date('Y', $timestamp);
                        if ($isNewYear && $week != 1)
                        {
                            // A new year with the first week having last year's final week number (52 or 53),
                            // because the week's Thursday lies in the old year.
                            $week = $lastWeek;
                        }
                        else
                        {
                            // The last week of December having the week number 1, because
                            // the week's Thursday lies in the new year.
                            $week = $lastWeek;
                        }
                        if ($weekDay == 0)
                        {
                            ++$week;
                        }
                    }

                    $lastWeek = $week;

                    if ( !isset( $weeks[$week] ) )
                    {
                        for ( $i = 0; $i < 7; ++$i )
                        {
                            $weeks[$week][] = false;
                        }
                    }
                    $dayData = array( 'day' => $day,
                                      'link' => false,
                                      'class' => $dayClass,
                                      'highlight' => false );
                    if ( $currentDay == $info['yday'] )
                    {
                        if ( isset( $optional['current_class'] ) )
                            $dayData['class'] = $optional['current_class'];
                        $dayData['highlight'] = true;
                    }
                    if ( $dateInfo['year'] == $todayInfo['year'] and
                         $dateInfo['mon'] == $todayInfo['mon'] and
                         $day == $todayInfo['mday'] )
                    {
                        if ( $dayData['class'] )
                            $dayData['class'] .= '-' . $todayClass;
                        else
                            $dayData['class'] = $todayClass;
                    }
                    if ( $days[$day] )
                    {
                        $dayLink = $link;
                        if ( $dayLink )
                        {
                            $dayLink .= '/(year)/' . $info['year'];
                            $dayLink .= '/(month)/' . $info['mon'];
                            $dayLink .= '/(day)/' . $info['mday'];
                        }
                        $dayData['link'] = $dayLink;
                    }
                    $weeks[$week][$weekDay] = $dayData;
                }

                $next = false;
                if ( isset( $optional['next'] ) )
                    $next = $optional['next'];
                if ( $next )
                {
                    $nextTimestamp = mktime( 0, 0, 0, $dateInfo['mon'] + 1, 1, $dateInfo['year'] );
                    $nextInfo = getdate( $nextTimestamp );
                    $month['next'] = array( 'month' => $locale->longMonthName( $nextInfo['mon'] ),
                                            'year' => $nextInfo['year'] );
                    $nextLink = $next['link'];
                    $nextLink .= '/(year)/' . $nextInfo['year'];
                    $nextLink .= '/(month)/' . $nextInfo['mon'];
                    $month['next']['link'] = $nextLink;
                }
                else
                    $month['next'] = false;

                $month['current'] = array( 'month' => $locale->longMonthName( $info['mon'] ),
                                           'year' => $info['year'] );
                $currentLink = $next['link'];
                $currentLink .= '/(year)/' . $info['year'];
                $currentLink .= '/(month)/' . $info['mon'];
                $month['current']['link'] = $currentLink;

                $previous = false;
                if ( isset( $optional['previous'] ) )
                {
                    $previous = $optional['previous'];
                }
                if ( $previous )
                {
                    $previousTimestamp = mktime( 0, 0, 0, $dateInfo['mon'] - 1, 1, $dateInfo['year'] );
                    $previousInfo = getdate( $previousTimestamp );
                    $month['previous'] = array( 'month' => $locale->longMonthName( $previousInfo['mon'] ),
                                                'year' => $previousInfo['year'] );
                    $previousLink = $previous['link'];
                    $previousLink .= '/(year)/' . $previousInfo['year'];
                    $previousLink .= '/(month)/' . $previousInfo['mon'];
                    $month['previous']['link'] = $previousLink;
                }
                else
                {
                    $month['previous'] = false;
                }
                $month['weeks'] = $weeks;
                $operatorValue = $month;
            }
        }
    }

    /// \privatesection
    public $Operators;
};

?>
