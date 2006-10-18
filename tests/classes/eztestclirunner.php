<?php
//
// Definition of eZTestCLIRunner class
//
// Created on: <30-Jan-2004 10:46:10 >
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

/*! \file eztestclirunner.php
*/

/*!
  \class eZTestCLIRunner eztestclirunner.php
  \ingroup eZTest
  \brief eZTestRunner runs tests from test units and displays results on the console

  This class overrides the display() method to provide instant
  reporting of test results. Using this class is the same
  as with the eZTestRunner class.

  If you want to display all test results call the printResults() after
  the tests have been run.

*/

include_once( 'tests/classes/eztestrunner.php' );

class eZTestCLIRunner extends eZTestRunner
{
    /*!
     Inititalizes the test runner.
    */
    function eZTestCLIRunner()
    {
        $this->eZTestRunner();
    }

    /*!
     Prints all results to the console.
    */
    function printResults()
    {
        $results = $this->resultList();
        foreach ( $results as $result )
        {
            $this->display( $result );
        }
    }

    /*!
     Displays the result text on the console.
    */
    function display( $result )
    {
        include_once( 'lib/ezutils/classes/ezcli.php' );
        $cli =& eZCLI::instance();
        $col = 70;
        $cli->output( $result['name'] . $cli->gotoColumn( $col ), false );
        if ( $result['status'] )
        {
            $cli->output( $cli->stylize( 'success', "[Success]" ) );
        }
        else
        {
            $cli->output( $cli->stylize( 'error', "[Failure]" ) );
            foreach ( $result['messages'] as $message )
            {
                $cli->output( "  * ", false );
                if ( $message['assert'] )
                {
                    $cli->output( '#' . $message['assert_number'] . ' ', false );
                    $cli->output( '' . $message['assert'] . ': ', false );
                }
                $cli->output( $message['text'], false );
                $cli->output();
            }
        }
    }
}

?>
