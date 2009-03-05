<?php
//
// Definition of eZHTTPPersistence class
//
// Created on: <19-Apr-2002 16:14:47 amos>
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

/*!
  \class eZHTTPPersistence ezhttppersistence.php
  \ingroup eZHTTP
  \brief Object persistence using HTTP post variables

  This class allows objects or data to exist between page views.
  It can read HTTP post variables and set them in existing objects
  to override data. This is useful if you want to keep changes in a
  page but don't want to store the changes in a DB. It also makes it
  easier to fetch the changes by the user before an object is stored.

*/

class eZHTTPPersistence
{
    /*!
     Initializes the class.
    */
    function eZHTTPPersistence()
    {
    }

    /*!
     Fetches the HTTP post variables using the base name $base_name and stores
     them in the object $objects, if $is_array is true then $objects is assumed
     to be an array and all objects are updated.
    */
    static function fetch( $base_name,
                    /*! The definition of the objects, uses the same syntax as eZPersistentObject */
                    $def,
                    $objects,
                    /*! The eZHTTPTool object */
                    $http,
                    $is_array )
    {
        if ( $is_array )
        {
            for ( $i = 0; $i < count( $objects ); ++$i )
            {
                $object = $objects[$i];
                eZHTTPPersistence::fetchElement( $base_name, $def, $object, $http, $i );
            }
        }
        else
            eZHTTPPersistence::fetchElement( $base_name, $def, $objects, $http, false );
    }

    /*!
     \private
     Helper function for fetch().
    */
    static function fetchElement( $base_name, $def,
                                  $object, $http, $index )
    {
        $fields = $def["fields"];
        $keys = $def["keys"];
        foreach ( $fields as $field_name => $field_member )
        {
            if ( !in_array( $field_name, $keys ) )
            {
                $post_var = $base_name . "_" . $field_name;
                if ( $http->hasPostVariable( $post_var ) )
                {
                    $post_value = $http->postVariable( $post_var );
                    if ( $index === false )
                        $object->setAttribute( $field_name, $post_value );
                    else
                        $object->setAttribute( $field_name, $post_value[$index] );
                }
            }
        }
    }

    /*!
     \deprecated This function has some serious flaws and will be removed in a future release
     Goes trough all fields defined in \a $def and tries to find a post variable
     which is named \a $base_name, field name and "checked" with _ between items.
     If the post variable is an array the id of the current object is matched against
     that array, if one is found the matched field is set to be true otherwise false.
     If no post variable was found with that signature the field is ignored.
     Example of name:
     \code
       In the HTML code use:<br/>
       <input type="checkbox" name="ContentClassAttribute_is_searchable_checked[]" value="some_id" />
     \endcode
    */
    static function handleChecked( $base_name,
                            /*! The definition of the objects, uses the same syntax as eZPersistentObject */
                            $def,
                            $objects,
                            $http,
                            $is_array = true )
    {
        if ( $is_array )
        {
            foreach( $objects as $object )
            {
                eZHTTPPersistence::handleCheckedElement( $base_name, $def, $object, $http );
            }
        }
        else
            eZHTTPPersistence::handleCheckedElement( $base_name, $def, $objects, $http );
    }

    /*!
     \private
     Helper function for handleChecked().
     \deprecated This function has some serious flaws and will be removed in a future release
    */
    static function handleCheckedElement( $base_name, $def,
                                   $object, $http )
    {
        $fields = $def["fields"];
        $keys = $def["keys"];
        $id = $object->attribute( "id" );
        foreach ( $fields as $field_name => $field_member )
        {
            if ( !in_array( $field_name, $keys ) )
            {
                $post_var = $base_name . "_" . $field_name . "_checked";
                if ( $http->hasPostVariable( $post_var ) or $field_name == "is_searchable" or $field_name == "is_required"   )
                {
                    $value = false;
                    $post_value = $http->postVariable( $post_var );
                    if ( is_array( $post_value ) &&
                         in_array( $id, $post_value ) )
                    {
                        $value = true;
                    }
                    else
                    {
                         $value = false;
                    }
                    $object->setAttribute( $field_name, $value );
                }
            }
        }
    }

    /*!
     Loops over the HTTP post variables with $base_name as the base.
     It examines the HTTP post variable $base_name "_" $cond "_checked"
     which should contain an array of ids. The ids are then matched against
     the objects attribute $cond. If they match the object is moved to the
     $rejects array otherwise the $keepers array.
    */
    static function splitSelected( $base_name,
                            $objects, /*! The eZHTTPTool object */ $http, $cond,
                            &$keepers, &$rejects )
    {
        $keepers = array();
        $rejects = array();
        $post_var = $base_name . "_" . $cond . "_checked";
        if ( $http->hasPostVariable( $post_var ) )
        {
            $checks = $http->postVariable( $post_var );
        }
        else
        {
            return false;
        }
        foreach( $objects as $object )
        {
            if ( $object->hasAttribute( $cond ) )
            {
                $val = $object->attribute( $cond );
                if ( in_array( $val, $checks ) )
                {
                    $rejects[] = $object;
                }
                else
                {
                    $keepers[] = $object;
                }
            }
            else
            {
                $keepers[] = $object;
            }
        }
        return true;
    }

}

?>
