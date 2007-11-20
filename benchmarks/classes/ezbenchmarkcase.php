<?php
//
// Definition of eZBenchmarkCase class
//
// Created on: <18-Feb-2004 11:55:40 >
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezbenchmarkcase.php
*/

/*!
  \class eZBenchmarkCase ezbenchmarkcase.php
  \ingroup eZTest
  \brief eZBenchmarkCase provides a base class for doing automated benchmarks

  This class provides basic functionality and interface
  for creating benchmarks. It keeps a list of marks and
  a name which are accessible with markList() and name().

  To add new tests use addMark() or addFunctionMark()
  with the appropriate mark data.

  The methods prime() and cleanup() will be called before
  and after the mark itself is handled. This allows for priming
  certain values for the mark and cleaning up afterwards.

  To create a mark case inherit this class and create some mark methods
  that takes one parameter \a $tr which is the current test runner and
  a $parameter which is optional parameters added to the mark entry.
  The constructor must call the eZBenchmarkCase constructor with a useful
  name and setup some test methods with addMark() and addFunctionMark().

  For running the marks you must pass the case to an eZBenchmarkRunner instance.

  \code
//include_once( 'benchmarks/classes/ezbenchmarkcase.php' );
class MyTest extends eZBenchmarkCase
{
    function MyTest()
    {
        $this->eZBenchmarkCase( 'My test case' );
        $this->addmark( 'markFunctionA', 'Addition mark' );
        $this->addFunctionTest( 'MyFunctionMark', 'Addition mark 2' );
    }

    function markFunctionA( &$tr, $parameter )
    {
        $a = 1 + 2;
    }
}

function MyFunctionMark( &$tr, $parameter )
{
    $a = 1 + 2;
}

$case = new MyTest();
$runner = new eZBenchmarkCLIRunner();
$runner->run( $case );
  \endcode

*/

require_once( 'lib/ezutils/classes/ezdebug.php' );
//include_once( 'benchmarks/classes/ezbenchmarkunit.php' );

class eZBenchmarkCase extends eZBenchmarkUnit
{
    /*!
     Constructor
    */
    function eZBenchmarkCase( $name = false )
    {
        $this->eZBenchmarkUnit( $name );
    }

    function addMark( $method, $name, $parameter = false )
    {
        if ( !method_exists( $this, $method ) )
        {
            eZDebug::writeWarning( "Mark method $method in mark " . $this->Name . " does not exist, cannot add",
                                   'eZBenchmarkCase::addMark' );
        }
        if ( !$name )
            $name = $method;
        $this->addEntry( array( 'name' => $name,
                                'object' => &$this,
                                'method' => $method,
                                'parameter' => $parameter ) );
    }

    function addFunctionMark( $function, $name, $parameter = false )
    {
        if ( !function_exists( $function ) )
        {
            eZDebug::writeWarning( "Mark function $method does not exist, cannot add to mark " . $this->Name,
                                   'eZBenchmarkCase::addFunctionMark' );
        }
        if ( !$name )
            $name = $function;
        $this->addEntry( array( 'name' => $name,
                                'function' => $function,
                                'parameter' => $parameter ) );
    }
}

?>
