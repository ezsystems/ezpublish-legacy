<?php
//
// Definition of eZTestCLIRunner class
//
// Created on: <30-Jan-2004 10:46:10 >
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
        $cli->output( $result['name'] . "\033[" . $col . "G", false );
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
