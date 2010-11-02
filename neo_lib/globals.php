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
  \class ezpGlobals globals.php

  A system for keeping track of global variables separated in different
  sections. Each section refers to a specific ezpGlobalsStruct object
  which contains the global variables as member variables.

*/

class ezpGlobals
{
    /**
     * An array containing the global section objects
     *
     * @var array(string=>ezpGlobalsStruct)
     */
    private $sections = array();

    /**
     * An array containing the global section classes.
     *
     * @var array(string=>string)
     */
    private $sectionClasses = array();

    /**
     * The global instance of ezpGlobals, if any.
     *
     * @var ezpGlobals
     */
    private static $instance = null;

    /**
     */
    public function __construct()
    {
        if ( file_exists( 'globals.php' ) )
        {
            $this->sectionClasses = include( 'globals.php' );
        }
    }

    /**
     Returns the current global instance of ezpGlobals.
     If none exists a new one is created.
     */
    static public function instance()
    {
        if ( self::$instance === null )
            self::$instance = new ezpGlobals();

        return self::$instance;
    }

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
        if ( !isset( $this->sections[$name] ) )
        {
            if ( !isset( $this->sectionClasses[$name] ) )
                throw new ezcBasePropertyNotFoundException( $name );
            $this->sections[$name] = new $this->sectionClasses[$name];
            unset( $this->sectionClasses[$name] );
            if ( !isset( $this->sections[$name] ) )
                throw new ezcBasePropertyNotFoundException( $name );
        }
        return $this->sections[$name];
    }

    /**
     * Returns true if the property $name is set, otherwise false.
     * @return bool
     */
    public function __isset( $name )
    {
        return isset( $this->sections[$name] ) || isset( $this->sectionClasses[$name] );
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
        if ( $value instanceof ezpGlobalsStruct )
        {
            $this->sections[$name] = $value;
            unset( $this->sectionClasses[$name] );
        }
        else
        {
            throw new ezpGlobalsException( "Cannot initialize the global section '{$name}' with the class type '" . get_class( $value ) . "', it must be a sub-class of ezpGlobalsStruct" );
        }
    }

    /**
     * Unsets the property $name to $value.
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __unset( $name )
    {
        unset( $this->sections[$name] );
        unset( $this->sectionClasses[$name] );
    }

    /**
     * Removes all section objects and definitions.
     */
    public function clear()
    {
        $this->sections = array();
        $this->sectionClasses = array();
    }

    /**
     * Defines a new global section named $name and with class $className.
     * Any existing section object with the same name is removed.
     */
    public function define( $name, $className )
    {
        $this->sectionClasses[$name] = $className;
        unset( $this->sections[$name] );
    }
}

?>
