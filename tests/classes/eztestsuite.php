<?php
//
// Definition of eZTestSuite class
//
// Created on: <30-Jan-2004 11:09:08 >
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
