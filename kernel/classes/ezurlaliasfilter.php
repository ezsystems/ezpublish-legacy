<?php
//
// Definition of eZURLAliasFilter class
//
// Created on: <22-Jun-2007 09:03:31 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/**
 * The eZURLAliasFilter class the interface for all url alias filters,
 * the filter implementation must implement the process method.
 *
 * For execution of the configured filters use the static method processFilters.
 * Help with configuration is found in settings/site.ini under the group URLTranslator.
 *
 * @abstract
 */

abstract class eZURLAliasFilter
{
    /**
     * Initialize the filter object.
     */
    public function eZURLAliasFilter()
    {
    }

    /*
     *
     * Process the url alias element $text and return the new element as a string.
     * This method must be overriden in custom URL alias filters.
     *
     * This function has not been declared as "abstract" because of backward compatibility
     * but you should see it as an abstract method.
     *
     * @abstract
     * @param string $text           The URL alias
     * @param string $languageObject The current language object used for the string $text.
     * @param object $caller         The object which called the filtering process, can be null.
     * @return string the processed URL alias
     */
    public function process( $text, &$languageObject, &$caller )
    {
        return $text;
    }

    /**
     *
     * Process all configured filters and return the resulting text.
     *
     * Filters are found in the INI group URLTranslator and the setting Filters.
     * This is done in combination with the setting Extensions which controls
     * which extensions have filter classes.
     *
     * The parameters $text, $languageObject and $caller are sent to the method
     * process on the filter object.
     *
     * Note: The filter list will be cached in memory to improve performance of subsequent calls.
     *
     * @static
     * @param string $text           The URL alias
     * @param string $languageObject The current language object used for the string $text.
     * @param object $caller         The object which called the filtering process, can be null.
     * @return string the URL alias processed by the process() method
     */
    public static function processFilters( $text, $languageObject, $caller )
    {
        $ini = eZINI::instance( 'site.ini' );
        $filterClassList = $ini->variable( 'URLTranslator', 'FilterClasses' );

        foreach ( $filterClassList as $filterClass )
        {
            if( !class_exists( $filterClass ) )
            {
                eZDebug::writeError( $filterClass . ' does not exist, please run bin/php/ezpgenerateautoload.php', __METHOD__ );
                continue;
            }

            $filter = new $filterClass();
            $text = $filter->process( $text, $languageObject, $caller );
        }

        return $text;
    }
}

?>