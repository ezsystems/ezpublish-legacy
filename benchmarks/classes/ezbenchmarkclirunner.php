<?php
//
// Definition of eZBenchmarkCLIRunner class
//
// Created on: <18-Feb-2004 11:57:35 >
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

/*! \file ezbenchmarkclirunner.php
*/

/*!
  \class eZBenchmarkCLIRunner ezbenchmarkclirunner.php
  \ingroup eZBenchmark
  \brief eZBenchmarkCLIRunner runs marks from benchmark cases and displays results on the console

  This class overrides the display() method to provide instant
  reporting of mark results. Using this class is the same
  as with the eZBenchmarkRunner class.

  If you want to display all test results call the printResults() after
  the marks have been run.

*/

include_once( 'benchmarks/classes/ezbenchmarkrunner.php' );

class eZBenchmarkCLIRunner extends eZBenchmarkRunner
{
    /*!
     Constructor
    */
    function eZBenchmarkCLIRunner()
    {
        $this->eZBenchmarkRunner();
        $this->MaxMap = false;
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
        $col = 60;
        $start = $result['start'];
        $end = $result['end'];
        $final = $result['result'];
        $prime = $result['prime'];
        $repeatCount = $result['repeat_count'];
        $normalized = $result['normalized'];
        $cli->output( $cli->stylize( 'symbol', $result['name'] ) . " " . $cli->stylize( 'mark', "($repeatCount times)" ) );
        if ( isset( $final['memory'] ) )
        {
            $text = $cli->stylize( 'bold', "Memory" ) . ": " . $cli->stylize( 'emphasize', $final['memory'] ) . " bytes";
            if ( isset( $prime['memory'] ) )
                $text .= " (Priming: " . $cli->stylize( 'mark', $prime['memory'] ) . " bytes)";
            if ( $this->MaxMap !== false )
            {
                if ( $this->MaxMap['memory'] > $final['memory'] )
                {
                    $factor = $this->MaxMap['memory'] / $final['memory'];
                    $text .= " " . number_format( $factor, 1 ) . "x less";
                }
                else
                {
                    $factor = $final['memory'] / $this->MaxMap['memory'];
                    $text .= " " . number_format( $factor, 1 ) . "x more";
                }
            }
            $cli->output( $text );
        }
        $text = "Time:   " . $cli->stylize( 'emphasize', number_format( $final['time'], 3 ) ) . " seconds (" . $cli->stylize( 'emphasize', number_format( $normalized['time'], 3 ) ) . ")";
        if ( $this->MaxMap !== false )
        {
            if ( $this->MaxMap['time'] > $normalized['time'] )
            {
                $factor = $this->MaxMap['time'] / $normalized['time'];
                $text .= " " . number_format( $factor, 1 ) . "x less";
            }
            else
            {
                $factor = $normalized['time'] / $this->MaxMap['time'];
                $text .= " " . number_format( $factor, 1 ) . "x more";
            }
        }
        $cli->output( $text );

        if ( $this->MaxMap == false )
        {
            $this->MaxMap = array();
            $this->MaxMap['time'] = $normalized['time'];
            $this->MaxMap['memory'] = $final['memory'];
        }
        else
        {
            if ( isset( $final['memory'] ) and
                 $this->MaxMap['memory'] < $final['memory'] )
                $this->MaxMap['memory'] = $final['memory'];
            if ( $this->MaxMap['time'] < $normalized['time'] )
                $this->MaxMap['time'] = $normalized['time'];
        }

//         $cli->output( $result['name'] . "\033[" . $col . "G", false );
//         if ( $result['status'] )
//         {
//             $cli->output( $cli->stylize( 'success', "[Success]" ) );
//         }
//         else
//         {
//             $cli->output( $cli->stylize( 'error', "[Failure]" ) );
//             foreach ( $result['messages'] as $message )
//             {
//                 $cli->output( "  * ", false );
//                 if ( $message['assert'] )
//                 {
//                     $cli->output( '#' . $message['assert_number'] . ' ', false );
//                     $cli->output( '' . $message['assert'] . ': ', false );
//                 }
//                 $cli->output( $message['text'], false );
//                 $cli->output();
//             }
//         }
    }

    var $MaxMap = array();
}

?>
