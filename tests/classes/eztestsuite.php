<?php
//
// Definition of eZTestSuite class
//
// Created on: <30-Jan-2004 11:09:08 >
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

/*! \file eztestsuite.php
*/

/*!
  \class eZTestSuite eztestsuite.php
  \ingroup eZTest
  \brief eZTestSuite allows multiple test units to be run as one test

  This class makes it easier to run multiple test cases or units
  without resorting to multiple calls to run() on an eZTestRunner.
  The suite will accumulate all tests into one test list.

  Using this class is simply to create an instance with a name and
  then add test units with addUnit(). The suite itself is also a
  test unit so it is possible to add one suite to another as a
  unit.

  \code
$suite = new eZTestSuite( 'Test of my system' );
$mainTest = new MyMainTest();
$subsystemTest = new MySubsystemTest();

$suite->addUnit( $mainTest );
$suite->addUnit( $subsystemTest );

$runner = new eZTestCLIRunner();
$runner->run( $suite );
  \endcode

*/

include_once( 'tests/classes/eztestunit.php' );

class eZTestSuite extends eZTestUnit
{
    /*!
     Initializes the test suite with the name \a $name.
    */
    function eZTestSuite( $name = false )
    {
        $this->eZTestUnit( $name );
    }

    /*!
     Adds all tests from the test unit \a $unit to this test suite.
     \note If \a $unit is not a subclass of eZTestUnit a warning is issued and the tests are not added.
    */
    function addUnit( &$unit )
    {
        if ( is_subclass_of( $unit, 'eztestunit' ) )
        {
            $testList = $unit->testList();
            foreach ( $testList as $entry )
            {
                $entry['name'] = $unit->name() . '::' . $entry['name'];
                $this->addTestEntry( $entry );
            }
        }
        else
        {
            eZDebug::writeWarning( "Tried to add test unit for an object which is not subclassed from eZTestUnit",
                                   'eZTestSuite::addUnit' );
        }
    }
}

?>
