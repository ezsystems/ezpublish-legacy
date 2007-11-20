<?php
//
// Definition of eZURLAliasFilter class
//
// Created on: <28-Jun-2007 19:50:27 amos>
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

/*! \file ezurlaliasquery.php
*/

/*!
  \class eZURLAliasQuery ezurlaliasquery.php
  \brief Handles querying of URL aliases with different filters

  To use this class instantiate it and then fill in the public
  properties with the wanted values. When finished call count()
  and/or fetchAll() to perform the wanted operation. Repeated
  calls will simply return the cached values.

  If you change the properties afterwards call prepare() to reset
  internally cached values.

  Objects of this class can also be sent to the template system
  as a variable and the properties can be accessed directly
  including 'count' for the count() function and 'items' for the
  'fetchAll' function.
*/


/*!
 \todo The hasAttribute, attribute and setAttribute functions can be turned into properties for PHP 5.
 */
class eZURLAliasQuery
{
    /*!
     Array of action values to include, set to null to fetch all kinds.
     e.g. eznode:60
     */
    var $actions;
    /*!
     Array of action types to include, set to null to fetch all kinds.
     e.g. eznode
     */
    var $actionTypes;
    /*!
     Array of action types to exclude, set to null to disable.
     e.g. eznode
     */
    var $actionTypesEx;
    /*!
     If non-null it forces only elements with this parent to be considered.
     */
    var $paren;
    /*!
     If non-null it forces only elements with this text to be considered.
     */
    var $text;
    /*!
     Type of elements to count, use 'name' for only real names for actions, 'alias' for only aliases to the actions or 'all' for real and aliases.
     */
    var $type      = 'alias';
    /*!
     If true languages are filtered, otherwise all languages are fetched.
     */
    var $languages = true;
    /*!
     The offset to start the fetch.
     \note It only applies to fetchAll()
     */
    var $offset    = 0;
    /*!
     The max limit of the fetch.
     \note It only applies to fetchAll()
     */
    var $limit     = 15;
    /*!
     The order in which elements are fetched, refers the the DB column of the table.
     \note It only applies to fetchAll()
     */
    var $order     = 'text';

    /*!
     \private
     Cached value of partial query, used for both count() and fetchAll().
     */
    var $query;
    /*!
     \private
     Cached value of the total count.
     */
    var $count;
    /*!
     \private
     Cached value of the fetch items.
     */
    var $items;

    function eZURLAliasQuery()
    {
    }

    function hasAttribute( $name )
    {
        return in_array( $name,
                         array_diff( get_object_vars( $this ),
                                     array( 'query' ) ) );
    }

    function attribute( $name )
    {
        switch ( $name )
        {
            case 'count':
            {
                return $this->count();
            } break;
            case 'items':
            {
                return $this->fetchAll();
            } break;
            default:
            {
                return $this->$name;
            } break;
        }
    }

    function setAttribute( $name, $value )
    {
        $this->$name = $value;
        $this->query = null;
        $this->count = null;
        $this->items = null;
    }

    /*!
     Resets all internally cached values. This must be called
     if the properties have been changed after count() or
     fetchAll() has been used.
     */
    function prepare()
    {
        $this->query = null;
        $this->count = null;
        $this->items = null;
    }

    /*!
     Counts the total number of items available using the current
     filters as specified with the properties.
     \note Can also be fetched from templates by using the 'count' property.
     */
    function count()
    {
        if ( $this->count !== null )
            return $this->count;

        if ( $this->query === null )
        {
            $this->query = $this->generateSQL();
        }
        if ( $this->query === false )
            return 0;
        $query = "SELECT count(*) AS count {$this->query}";
        $db = eZDB::instance();
        $rows = $db->arrayQuery( $query );
        if ( count( $rows ) == 0 )
            $this->count = 0;
        else
            $this->count = $rows[0]['count'];
        return $this->count;
    }

    /*!
     Fetches the items in the current range (offset/limit) using the current
     filters as specified with the properties.
     \note Can also be fetched from templates by using the 'items' property.
     */
    function fetchAll()
    {
        if ( $this->items !== null )
            return $this->items;

        if ( $this->query === null )
        {
            $this->query = $this->generateSQL();
        }
        if ( $this->query === false )
            return array();
        $query = "SELECT * {$this->query} ORDER BY {$this->order}";
        $params = array( 'offset' => $this->offset,
                         'limit'  => $this->limit );
        $db = eZDB::instance();
        $rows = $db->arrayQuery( $query, $params );
        if ( count( $rows ) == 0 )
            $this->items = array();
        else
            $this->items = eZURLAliasQuery::makeList( $rows );
        return $this->items;
    }

    /*!
     \private
     Generates the common part of the SQL using the properties as filters and returns it.
     */
    private function generateSQL()
    {
        if ( !in_array( $this->type, array( 'name', 'alias', 'all' ) ) )
        {
            eZDebug::writeError( "Parameter \$type must be one of name, alias or all. The value which was used was '{$this->type}'." );
            return null;
        }

        $db = eZDB::instance();
        if ( $this->languages === true )
        {
            $langMask = trim( eZContentLanguage::languagesSQLFilter( 'ezurlalias_ml', 'lang_mask' ) );
            $conds[] = "($langMask)";
        }

        if ( $this->paren !== null )
        {
            $conds[] = "parent = {$this->paren}";
        }

        if ( $this->text !== null )
        {
            $conds[] = "text_md5 = " . $db->md5( "'" . $db->escapeString( $this->text ) . "'" );
        }

        if ( $this->actions !== null )
        {
            // Check for conditions which will return no rows.
            if ( count( $this->actions ) == 0 )
                return false;

            if ( count( $this->actions ) == 1 )
            {
                $action = $this->actions[0];
                $actionStr = $db->escapeString( $action );
                $conds[] = "action = '$actionStr'";
            }
            else
            {
                $actions = array();
                foreach ( $this->actions as $action )
                {
                    $actions[] = "'" . $db->escapeString( $action ) . "'";
                }
                $conds[] = "action IN (" . join( ", ", $actions ) . ")";
            }
        }
        $actionTypes = null;
        if ( $this->actionTypes !== null )
        {
            $actionTypes = $this->actionTypes;
        }
        if ( $this->actionTypesEx !== null )
        {
            if ( $actionTypes == null )
            {
                $rows = $db->arrayQuery( "SELECT DISTINCT action_type FROM ezurlalias_ml" );
                $actionTypes = array();
                foreach ( $rows as $row )
                {
                    $actionTypes[] = $row['action_type'];
                }
            }
            $actionTypes = array_values( array_diff( $actionTypes, $this->actionTypesEx ) );
        }
        if ( $actionTypes !== null )
        {
            // Check for conditions which will return no rows.
            if ( count( $actionTypes ) == 0 )
                return false;

            if ( count( $actionTypes ) == 1 )
            {
                $action = $actionTypes[0];
                $actionStr = $db->escapeString( $action );
                $conds[] = "action_type = '$actionStr'";
            }
            else
            {
                $actions = array();
                foreach ( $actionTypes as $action )
                {
                    $actions[] = "'" . $db->escapeString( $action ) . "'";
                }
                $conds[] = "action_type IN (" . join( ", ", $actions ) . ")";
            }
        }

        $conds[] = 'is_original = 1';
        if ( $this->type == 'name' )
        {
            $conds[] = 'is_alias = 0';
        }
        else if ( $this->type == 'alias' )
        {
            $conds[] = 'is_alias = 1';
        }
        else // 'all'
        {
        }

        return "FROM ezurlalias_ml WHERE " . join( " AND ", $conds );
    }

    /*!
     \static
     Takes an array with database data in $row and turns them into eZPathElement objects.
     Entries which have multiple languages will be turned into multiple objects.
     */
    static public function makeList( $rows )
    {
        if ( !is_array( $rows ) || count( $rows ) == 0 )
            return array();
        $list = array();
        foreach ( $rows as $row )
        {
            $row['always_available'] = $row['lang_mask'] % 2;
            $mask = $row['lang_mask'] & ~1;
            for ( $i = 1; $i < 30; ++$i )
            {
                $newMask = (1 << $i);
                if ( ($newMask & $mask) > 0 )
                {
                    $row['lang_mask'] = (1 << $i);
                    $list[] = $row;
                }
            }
        }
        //include_once( 'kernel/classes/ezpathelement.php' );
        $objectList = eZPersistentObject::handleRows( $list, 'eZPathElement', true );
        return $objectList;
    }
}

?>
