<?php
//
// Definition of eZTestCase class
//
// Created on: <30-Jan-2004 08:44:55 >
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eztestcase.php
*/

/*!
  \class eZTestCase eztestcase.php
  \ingroup eZTest
  \brief eZTestCase provides a base class for doing automated test cases

  eZTestCase inherits from eZTestUnit and can be used in all
  test suites and test runners. It provides an easy way of registering
  test method using addTest().

  To create a test case inherit this class and create some test methods
  that takes one parameter \a $tr which is the current test runner.
  The constructor must call the eZTestCase constructor with a useful
  name and setup some test methods with addTest().

  For running the tests you must pass the case to an eZTestRunner instance
  or add it to a eZTestSuite object.

  \code
include_once( 'tests/classes/eztestcase.php' );
class MyTest extends eZTest
{
    function MyTest()
    {
        $this->eZTest( 'My test case' );
        $this->addTest( 'testFunctionA', 'Addition test' );
        $this->addFunctionTest( 'MyFunctionTest', 'Addition test 2' );
    }

    function testFunctionA( &$tr )
    {
        $tr->assertEquals( 2 + 2, 4, '2+2 equals 4' );
    }
}

function MyFunctionTest( &$tr )
{
    $tr->assertEquals( 2 + 2, 4, '2+2 equals 4' );
}

$case = new MyTest();
$runner = new eZCLITestRunner();
$runner->run( $case );
  \endcode

*/

include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'tests/classes/eztestunit.php' );

class eZTestCase extends eZTestUnit
{
    /*!
     Initializes the case with the name \a $name which passed to eZTestUnit::eZTestUnit.
    */
    function eZTestCase( $name = false )
    {
        $this->eZTestUnit( $name );
    }

    /*!
     Adds a new test method \a $method. If \a $name is empty the method name is used as name.
     \note If the method does not exist a warning is issued and the test will not be added the case.
    */
    function addTest( $method, $name = false, $parameter = false )
    {
        if ( !method_exists( $this, $method ) )
        {
            eZDebug::writeWarning( "Test method $method in test " . $this->Name . " does not exist, cannot add",
                                   'eZTestCase::addTest' );
        }
        if ( !$name )
            $name = $method;
        $this->addTestEntry( array( 'name' => $name,
                                    'object' => &$this,
                                    'method' => $method,
                                    'parameter' => $parameter) );
    }

    /*!
     Adds a new test function \a $function. If \a $name is empty the function name is used as name.
     \note If the function does not exist a warning is issued and the test will not be added the unit.
    */
    function addFunctionTest( $function, $name = false, $parameter = false )
    {
        if ( !function_exists( $function ) )
        {
            eZDebug::writeWarning( "Test function $functuion in test " . $this->Name . " does not exist, cannot add",
                                   'eZTestCase::addFunctionTest' );
        }
        if ( !$name )
            $name = $function;
        $this->addTestEntry( array( 'name' => $name,
                                    'function' => $function,
                                    'parameter' => $parameter ) );
    }
}

?>
