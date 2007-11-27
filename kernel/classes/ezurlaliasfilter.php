<?php
//
// Definition of eZURLAliasFilter class
//
// Created on: <22-Jun-2007 09:03:31 amos>
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

/*! \file ezurlaliasfilter.php
*/

/*!
  \class eZURLAliasFilter ezurlaliasfilter.php
  \brief Handles filtering of URL aliases

  This class defines the interface for all url alias filters, the filter implementation must implement the process method.

  For execution of the configured filters use the static method processFilters.
  Help with configuration is found in settings/site.ini under the group URLTranslator.
*/

class eZURLAliasFilter
{
    /**
     Initialize the filter object.
     */
    function eZURLAliasFilter()
    {
    }

    /*!
     \abstract
     Process the url alias element $text and return the new element as a string.

     \param $languageObject The current language object used for the string $text.
     \param $caller The object which called the filtering process, can be null.
     */
    function process( $text, &$languageObject, &$caller )
    {
        return $text;
    }

    /*!
     \static
     Process all configured filters and return the resulting text.

     Filters are found in the INI group URLTranslator and the setting Filters.
     This is done in combination with the setting Extensions which controls
     which extensions have filter classes.

     The parameters $text, $languageObject and $caller are sent to the method
     process on the filter object.

     Note: The filter list will be cached in memory to improve performance of subsequent calls.
     */
    static function processFilters( $text, $languageObject, $caller )
    {
        $filters = array();
        if ( isset( $GLOBALS['eZURLAliasFilters'] ) )
        {
            $filters = $GLOBALS['eZURLAliasFilters'];
        }
        else
        {
            // No filters are cached in memory, load them and cache for later use

            $ini = eZINI::instance();
            $extensionList = $ini->variable( 'URLTranslator', 'Extensions' );
            //include_once( 'lib/ezutils/classes/ezextension.php' );
            $pathList = eZExtension::expandedPathList( $extensionList, 'urlfilters' );
            $filterNames = $ini->variable( 'URLTranslator', 'Filters' );
            foreach ( $filterNames as $filterName )
            {
                foreach ( $pathList as $path )
                {
                    $filterPath = $path . '/' . strtolower( $filterName ) . '.php';
                    if ( !file_exists( $filterPath ) )
                        continue;
                    include_once( $filterPath );
                    if ( !class_exists( $filterName ) )
                    {
                        eZDebug::writeError( "URLAlias filter class named '$filterName' does not exist after loading PHP file $filterPath, ignoring entry." );
                        break;
                    }
                    $filters[] = new $filterName;
                }
            }
            $GLOBALS['eZURLAliasFilters'] = $filters;
        }

        foreach ( $filters as $filter )
        {
            $text = $filter->process( $text, $languageObject, $caller );
        }
        return $text;
    }
}

?>
