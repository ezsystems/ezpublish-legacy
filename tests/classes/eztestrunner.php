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
  \defgroup eZTest Classes for dealing with testing software
*/

/*!
  \class eZTestRunner eztestrunner.php
  \ingroup eZTest
  \brief eZTestRunner runs tests from test units and accumulates test results

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
    function run( &$unit, $display = false )
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
                if ( isset( $test['method'] ) and
                     isset( $test['object'] ) )
                {
                    $object =& $test['object'];
                    $method =& $test['method'];
                    if ( method_exists( $object, $method ) )
                    {
                        $this->setCurrentTestName( $unit->name() . '::' . $test['name'] );
                        $this->resetCurrentResult();

                        $object->setup();
                        $object->$method( $this );
                        $object->teardown();
                        if ( !$this->isCurrentResultSuccessful() )
                            $this->IsSuccessful = false;

                        $currentResult = $this->addCurrentResult();
                        $this->setCurrentTestName( false );

                        if ( $display )
                            $this->display( $currentResult );

                    }
                    else
                    {
                        $this->addFailure( $test['name'],
                                           "Method $method does not exist for test object(" . get_class( $object ) . ")" );
                    }
                }
                else if ( isset( $test['function'] ) )
                {
                    $function = $test['function'];
                    if ( function_exists( $function ) )
                    {
                        $this->setCurrentTestName( $unit->name() . '::' . $test['name'] );
                        $this->resetCurrentResult();

                        $function( $this );
                        if ( !$this->isCurrentResultSuccessful() )
                            $this->IsSuccessful = false;

                        $currentResult = $this->addCurrentResult();
                        $this->setCurrentTestName( false );

                        if ( $display )
                            $this->display( $currentResult );
                    }
                    else
                    {
                        $this->addFailure( $test['name'],
                                           "Function $function does not exist" );
                    }
                }
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
     Adds the current result to the result list and resets the current result data.
    */
    function addCurrentResult()
    {
        if ( is_array( $this->CurrentResult ) )
            $this->Results[] = $this->CurrentResult;
        return $this->CurrentResult;
    }

    /*!
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
     Sets the name of the currently running test to \a $name.
    */
    function setCurrentTestName( $name )
    {
        $this->CurrentTestName = $name;
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
