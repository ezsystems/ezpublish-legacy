<?php
//
// Definition of eZTestRunner class
//
// Created on: <30-Jan-2004 09:21:41 >
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

/*! \file eztestrunner.php
*/

/*!
  \defgroup eZTest Testing of PHP code
*/

/*!
  \class eZTestRunner eztestrunner.php
  \ingroup eZTest
  \brief eZTestRunner runs tests from test units and accumulates test results

  There are three uses of this class, as a client, as a test handler
  and as a base class for a new runner.

  <h3>Client usage</h3>
  To run test cases and test suites an instance of this class
  should be made and the method run() must be called with the
  test unit.

  Once the test is run you can check if all test were succesful with
  isSuccessful(). Detailed test information can be fetched with
  resultList().

  <h3>Test handler</h3>
  All tests that are run are sent an instance of an eZTestRunner
  class as a parameter. The test must use this instance to
  report failures.

  The tests must use the methods assert(), assertEquals(),
  assertNotEquals(), assertSimilar() and assertNotSimilar() for the runner
  to know about failed tests. It is also possible to add a custom failure
  with failure().

  Some examples:
  \code
$tr = new eZTestRunner();

function MyTests( &$tr )
{
    $tr->assert( $a > $b );
    $tr->assertEquals( $c, $d );
    $tr->assertNotEquals( $a, $b );
    $tr->assertSimilar( $e, $f );
    $tr->assertNotSimilar( $a, $b );
    if ( $a - $b > $d )
        $tr->failure( "\$a minus \$b is not larger than \$d" );
}


  \endcode

  <h3>Base class</h3>
  If you want to provide direct output of test results you must inherit
  this class and override the display() method.

  You can also add support for more test types by implementing the
  testEntryType() and runTestEntry() methods. Optionally prepareTestEntry()
  and finalizeTestEntry() can be implemented to add more specific
  preparation and finalizing code.

  To get output on console use eZTestCLIRunner instead.
*/

include_once( 'lib/ezutils/classes/ezdebug.php' );

class eZTestRunner
{
    /*!
     Initializes runner with no tests and successful set to \c false.
    */
    function eZTestRunner()
    {
        $this->Results = array();
        $this->CurrentResult = false;
        $this->IsSuccessful = false;
    }

    /*!
     Tries to run all tests defined in unit \a $unit and creates a result
     list and result value. Use resultList() and isSuccessful() to get the status.
    */
    function run( &$unit, $display = false, $testsToRun = true )
    {
        $this->Results = array();
        $this->CurrentResult = false;
        $this->IsSuccessful = false;
        if ( is_subclass_of( $unit, 'eztestunit' ) )
        {
            $this->IsSuccessful = true;
            $testList = $unit->testList();
            foreach ( $testList as $test )
            {
                if ( is_array( $testsToRun ) and
                     !in_array( strtolower( $test['name'] ), $testsToRun ) )
                    continue;
                $type = $this->testEntryType( $unit, $test );
                if ( $type )
                {
                    $test['type'] = $type;
                    $this->prepareTestEntry( $unit, $test );

                    $this->runTestEntry( $unit, $test );

                    $this->finalizeTestEntry( $unit, $test, $display );
                }
                else
                    $this->addToCurrentResult( false, false, $test['name'],
                                               "Unknown test type for test " . $unit->name() . '::' . $test['name'] );
            }
        }
        else
        {
            eZDebug::writeWarning( "Tried to run test on an object which is not subclassed from eZTestUnit",
                                   'eZTestRunner::run' );
        }
    }

    /*!
     \virtual
     \protected
     Figures out the test type and returns a string identifiying it. The type
     will be set in the test entry for other functions to use for checking.
     \return \c false if the type could not be figure out, in which case the test is skipped.
    */
    function testEntryType( $unit, $entry )
    {
        if ( isset( $entry['method'] ) and
             isset( $entry['object'] ) )
        {
            return 'method';
        }
        else if ( isset( $entry['function'] ) )
        {
            return 'function';
        }
        return false;
    }

    /*!
     \virtual
     \protected
     Prepares the test for running, this involves setting the current test name
     and resetting all current test restults.
    */
    function prepareTestEntry( &$unit, $entry )
    {
        $this->setCurrentTestName( $unit->name() . '::' . $entry['name'] );
        $this->resetCurrentResult();
    }

    /*!
     \virtual
     \protected
     Finalizes the test by applying the current test results to the main
     result list and resetting current test name.
     It will also call display() if \a $display is \c true.
    */
    function finalizeTestEntry( &$unit, $entry, $display )
    {
        if ( !$this->isCurrentResultSuccessful() )
            $this->IsSuccessful = false;

        $currentResult = $this->addCurrentResult();
        $this->setCurrentTestName( false );

        if ( $display )
            $this->display( $currentResult );
    }

    /*!
     \virtual
     \protected
     Runs the actual test entry based on the \c type value.

     \note eZTestRunner supports \c 'method' and \c 'function' calls,
           to get support for more test type this method must be overriden
           in a subclass.
    */
    function runTestEntry( &$unit, $entry )
    {
        switch ( $entry['type'] )
        {
            case 'method':
            {
                $object =& $entry['object'];
                $method =& $entry['method'];
                if ( method_exists( $object, $method ) )
                {
                    $object->setup();
                    $object->$method( $this, $entry['parameter'] );
                    $object->teardown();
                }
                else
                {
                    $this->addToCurrentResult( false, false, $entry['name'],
                                               "Method $method does not exist for test object(" . get_class( $object ) . ")" );
                }
            } break;

            case 'function':
            {
                $function = $entry['function'];
                if ( function_exists( $function ) )
                {
                    $function( $this, $entry['parameter'] );
                }
                else
                {
                    $this->addToCurrentResult( false, false, $entry['name'],
                                               "Function $function does not exist" );
                }
            } break;
        }
    }

    /*!
     \virtual
     \protected
     Called whenever a test is run, can be overriden to print out the test result immediately.
    */
    function display( $result )
    {
    }

    /*!
     \return \c true if the last test was successful.
     \note Will return \c false if no tests are run yet.
    */
    function isSuccessful()
    {
        return $this->IsSuccessful;
    }

    /*!
     \return an array with all the results from the last run.
    */
    function resultList()
    {
        return $this->Results;
    }

    /*!
     \protected
      Adds a failure for test \a $testName with optional message \a $message.
    */
    function addFailure( $testName, $message = false )
    {
        $messages = array();
        if ( $message )
            $messages[] = $message;
        $this->Results[] = array( 'status' => false,
                                  'assert' => false,
                                  'name' => $testName,
                                  'messages' => $messages );
    }

    /*!
     \protected
      Adds a result for test \a $testName with optional message \a $message.
    */
    function addToCurrentResult( $status, $assertName, $testName, $message = false )
    {
        if ( !is_array( $this->CurrentResult ) )
        {
             $this->CurrentResult = array( 'status' => $status,
                                           'name' => $testName,
                                           'messages' => array() );
        }
        else
        {
            $this->CurrentResult['status'] = $status;
        }
        if ( $message or $assertName )
            $this->CurrentResult['messages'][] = array( 'assert' => $assertName,
                                                        'assert_number' => $this->CurrentAssertNumber,
                                                        'text' => $message );
    }

    /*!
     \protected
     Adds the current result to the result list and resets the current result data.
    */
    function addCurrentResult()
    {
        if ( is_array( $this->CurrentResult ) )
            $this->Results[] = $this->CurrentResult;
        return $this->CurrentResult;
    }

    /*!
     \protected
     Resets the current result data.
    */
    function resetCurrentResult()
    {
        $this->CurrentResult = array( 'status' => true,
                                      'name' => $this->currentTestName(),
                                      'messages' => array() );
        $this->CurrentAssertNumber = 0;
    }

    /*!
     \protected
     \return \c true if the result of the currently run test is successful.
    */
    function isCurrentResultSuccessful()
    {
        if ( !is_array( $this->CurrentResult ) )
            return false;
        return $this->CurrentResult['status'];
    }

    /*!
     \return the name of the currently running test or \c false if no test.
    */
    function currentTestName()
    {
        return $this->CurrentTestName;
    }

    /*!
     \protected
     Sets the name of the currently running test to \a $name.
    */
    function setCurrentTestName( $name )
    {
        $this->CurrentTestName = $name;
    }

    /*!
     Adds a custom failure with message \a $message.
    */
    function failure( $message )
    {
        $this->addToCurrentResult( false, false, $this->currentTestName(), $message );
    }

    /*!
     Throws an error if \a $assertion is \c false, optionally \a $message may be attached to the failure.
    */
    function assert( $assertion, $message = false )
    {
        $this->CurrentAssertNumber++;
        if ( !$assertion )
            $this->addToCurrentResult( false, 'assert', $this->currentTestName(), $message );
    }

    /*!
     Throws an error if \a $actual is not equal to \a $expected, optionally \a $message may be attached to the failure.
     It will use === for equality checking.
    */
    function assertEquals( $actual, $expected, $message = false )
    {
        $this->CurrentAssertNumber++;
        if ( $actual !== $expected )
            $this->addToCurrentResult( false, 'assertEquals', $this->currentTestName(), $message );
    }

    /*!
     Throws an error if \a $actual is equal to \a $expected, optionally \a $message may be attached to the failure.
     It will use === for unequality checking.
    */
    function assertNotEquals( $actual, $expected, $message = false )
    {
        $this->CurrentAssertNumber++;
        if ( $actual === $expected )
            $this->addToCurrentResult( false, 'assertNotEquals', $this->currentTestName(), $message );
    }

    /*!
     Throws an error if \a $actual is not simi;ar to \a $expected, optionally \a $message may be attached to the failure.
     It will use == for similarity checking.
    */
    function assertSimilar( $actual, $expected, $message = false )
    {
        $this->CurrentAssertNumber++;
        if ( $actual != $expected )
            $this->addToCurrentResult( false, 'assertSimilar', $this->currentTestName(), $message );
    }

    /*!
     Throws an error if \a $actual is similar to \a $expected, optionally \a $message may be attached to the failure.
     It will use == for similarity checking.
    */
    function assertNotSimilar( $actual, $expected, $message = false )
    {
        $this->CurrentAssertNumber++;
        if ( $actual == $expected )
            $this->addToCurrentResult( false, 'assertNotSimilar', $this->currentTestName(), $message );
    }

    /// \privatesection
    /// An array with test results.
    var $Results;
    /// The current result
    var $CurrentResult;
    /// The name of the currently running test or \c false
    var $CurrentTestName;
    /// Contains the number of the currently run assertion in a test, \c 0 means no assertions are run yet.
    var $CurrentAssertNumber;
}

?>
