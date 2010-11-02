<?php
//
// Definition of ezpGlobals class
//
// Created on: <10-Dec-2007 15:21:40 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
  \class ezpGlobalsStruct globals_struct.php

  Base class for all the section objects contained in the class
  ezpGlobals. It takes care of getting, setting, checking for existance
  and has helper methods for dealing with templates.
  The sub-class only needs to define all its global variables as protected
  member variables.

*/

class ezpGlobalsStruct
{
    /**
     * Returns the value of the property $name.
     *
     * The properties that can be retrieved are:
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @param string $name
     * @return mixed
     */
    public function __get( $name )
    {
        $vars = get_object_vars( $this );
        if ( array_key_exists( $name, $vars ) )
        {
            return $this->$name;
        }
        throw new ezcBasePropertyNotFoundException( $name );
    }

    /**
     * Returns true if the property $name is set, otherwise false.
     * @return bool
     */
    public function __isset( $name )
    {
        $vars = get_object_vars( $this );
        return array_key_exists( $name, $vars );
    }

    /**
     * Sets the property $name to $value.
     *
     * The properties that can be set are:
     *
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set( $name, $value )
    {
        $vars = get_object_vars( $this );
        if ( array_key_exists( $name, $vars ) )
        {
            $this->$name = $value;
            return;
        }
        throw new ezcBasePropertyNotFoundException( $name );
    }

    /**
     * @throws ezcBasePropertyPermissionException since no properties can be unset.
     */
    public function __unset( $name )
    {
        throw new ezcBasePropertyPermissionException( $name, ezcBasePropertyPermissionException::READ  );
    }

    /**
     * Returns the properties as an associative array.
     */
    public function vars()
    {
        return get_object_vars( $this );
    }

    /**
     * Resets all properties to contain the value null.
     */
    public function reset()
    {
        foreach ( $this->vars() as $key => $tmp )
        {
            $this->$key = null;
        }
    }

    /**
     * Reads all variables found in $tpl which matches the properties in this
     * object and sets them.
     */
    public function fromTemplate( $tpl )
    {
        foreach ( $this->vars() as $key => $tmp )
        {
            if ( $tpl->hasVariable( $key ) )
                $this->$key = $tpl->variable( $key );
        }
    }

    /**
     * Transfers all properties in this object as template variables in $tpl.
     */
    public function toTemplate( $tpl )
    {
        foreach ( $this->vars() as $key => $var )
        {
            $tpl->setVariable( $key, $var );
        }
    }

}

?>
