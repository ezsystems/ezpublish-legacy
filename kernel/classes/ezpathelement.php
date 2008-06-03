<?php
//
// Definition of eZPathElement class
//
// Created on: <01-Aug-2003 16:44:56 bf>
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

/*! \file ezpathelement.php
*/

/*!
  \class eZPathElement ezpathelement.php
  \brief Handles singular path elements in URL aliases

  This class is similar to eZURLAliasML but is designed to work on single path
  elements instead of considering the whole url.

  The definition() of this class is the same as eZURLAliasML but it is not
  possible to store and remove items of this class.
*/

//include_once( "kernel/classes/ezpersistentobject.php" );
//include_once( "kernel/classes/ezcontentlanguage.php" );

class eZPathElement extends eZPersistentObject
{
    /*!
     Initializes a new path element.
    */
    function eZPathElement( $row )
    {
        $this->Path = null;
        $this->PathArray = null;
        if ( array_key_exists( 'always_available', $row )  )
        {
            $this->AlwaysAvailable = $row['always_available'];
        }
        $this->eZPersistentObject( $row );
    }

    /*!
     \reimp
    */
    static public function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "parent" => array( 'name' => 'Parent',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         "lang_mask" => array( 'name' => 'LangMask',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "text" => array( 'name' => 'Text',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "text_md5" => array( 'name' => 'TextMD5',
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         "action" => array( 'name' => 'Action',
                                                            'datatype' => 'string',
                                                            'default' => '',
                                                            'required' => true ),
                                         "action_type" => array( 'name' => 'ActionType',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "link" => array( 'name' => 'Link',
                                                          'datatype' => 'integer',
                                                          'default' => 0,
                                                          'required' => true ),
                                         "is_alias" => array( 'name' => 'IsAlias',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "is_original" => array( 'name' => 'IsOriginal',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ) ),
                      "keys" => array( "parent", "text" ),
                      "function_attributes" => array( "language_object" => "getLanguage",
                                                      "action_url" => "actionURL",
                                                      "path" => "getPath",
                                                      "always_available" => "alwaysAvailable",
                                                      "path_array" => "getPathArray" ),
                      "class_name" => "eZURLAliasML",
                      "name" => "ezurlalias_ml" );
    }

    /*!
     Storing of path elements is not allowed.
     */
    function store( $fieldFilters = null )
    {
        eZDebug::writeError( "Cannot store objects of eZPathElement, use eZURLAliasML instead" );
        return;
    }

    /*!
     Removal of path elements is not allowed.
     */
    function removeThis()
    {
        eZDebug::writeError( "Cannot remove objects of eZPathElement, use eZURLAliasML instead" );
        return;
    }

    /*!
     Returns the eZContentLanguage object which maches the element language mask.
     */
    function getLanguage()
    {
        return eZContentLanguage::fetch( $this->LangMask );
    }

    /*!
     Converts the action property into a real url which responds to the
     module/view on the site.
     */
    function actionURL()
    {
        return eZURLAliasML::actionToUrl( $this->Action );
    }

    /*!
     Fetches path elements which has the parent $parentID and name $name.
     \return An array of path element objects.
     */
    static public function fetchNamedByParentID( $parentID, $name )
    {
        //include_once( 'kernel/classes/ezurlaliasquery.php' );
        $filter = new eZURLAliasQuery();
        $filter->paren = $parentID;
        $filter->text  = $name;
        $filter->limit = false;
        return $filter->fetchAll();
    }

    /*!
     Calculates the full path for the current item and returns it.

     \note If you know the action values of the path use fetchPathByActionList() instead, it is more optimized.
     \note The calculated path is cached in $Path.
     */
    function getPath()
    {
        if ( $this->Path !== null )
            return $this->Path;

        // Fetch path 'text' elements of correct parent path
        $path = array( $this->Text );
        $id = (int)$this->Parent;
        $db = eZDB::instance();
        while ( $id != 0 )
        {
            $query = "SELECT parent, lang_mask, text FROM ezurlalias_ml WHERE id={$id}";
            $rows = $db->arrayQuery( $query );
            if ( count( $rows ) == 0 )
            {
                break;
            }
            $result = eZURLAliasML::choosePrioritizedRow( $rows );
            if ( !$result )
            {
                $result = $rows[0];
            }
            $id = (int)$result['parent'];
            array_unshift( $path, $result['text'] );
        }
        $this->Path = implode( '/', $path );
        return $this->Path;
    }

    function getPathArray()
    {
        if ( $this->PathArray !== null )
            return $this->PathArray;

        // Fetch path 'text' elements of correct parent path
        $path = array( $this );
        $id = (int)$this->Parent;
        $db = eZDB::instance();
        while ( $id != 0 )
        {
            $query = "SELECT * FROM ezurlalias_ml WHERE id={$id}";
            $rows = $db->arrayQuery( $query );
            if ( count( $rows ) == 0 )
            {
                break;
            }
            $result = eZURLAliasML::choosePrioritizedRow( $rows );
            if ( !$result )
            {
                $result = $rows[0];
            }
            $id = (int)$result['parent'];
            array_unshift( $path, new eZPathElement( $result ) );
        }
        $this->PathArray = $path;
        return $this->PathArray;
    }

    // Calculates always_available attribute from language mask
    function alwaysAvailable()
    {
        return $this->AlwaysAvailable;
    }

    public $AlwaysAvailable;
}

?>
