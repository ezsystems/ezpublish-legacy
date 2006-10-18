<?php
//
// Definition of eZTestUnit class
//
// Created on: <30-Jan-2004 08:57:09 >
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*! \file eztestunit.php
*/

/*!
  \class eZTestUnit eztestunit.php
  \ingroup eZTest
  \brief eZTestUnit defines the basis for a unit test

  This class provides basic functionality and interface
  for creating test units. It keeps a list of tests and
  a name which are accessible with testList() and name().

  To add new tests use addTestEntry() with the appropriate
  test data. What data to add to the entry is decided
  by the test runner, see eZTestRunner for more information.

  The methods setup() and teardown() will be called before
  and after the test itself is run. This allows for common
  initialization and cleanup code.

  For more convenient test handling use the eZTestCase class,
  it has ready made functionality for placing test code
  in methods.

*/

class eZTestUnit
{
    /*!
     Initializes the unit with the name \a $name, if the name is \c false then the class name is used.
    */
    function eZTestUnit( $name = false )
    {
        if ( !$name )
            $name = get_class( $this );
        $this->Name = $name;
        $this->TestList = array();
    }

    /*!
     \virtual
     This function is called before the test method is called.
     It can be overriden to have common initialization code for all tests.
    */
    function setup()
    {
    }

    /*!
     \virtual
     This function is called after the test method has finished its call.
     It can be overriden to remove any initialization done in setup().
    */
    function teardown()
    {
    }

    /*!
     \return the number of tests in the unit.
    */
    function count()
    {
        return count( $this->TestList );
    }

    /*!
     \return an array with tests.
    */
    function testList()
    {
        return $this->TestList;
    }

    /*!
     \return the name of the test unit.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
     \protected
     Adds a new test entry for the test list.
    */
    function addTestEntry( $entry )
    {
        $this->TestList[] = $entry;
    }

    /// \privatesection
    /// Name of test case
    var $Name;
    /// A list of tests to run
    var $TestList;
}

?>
