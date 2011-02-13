<?php
//
// Definition of eZBenchmarkCLIRunner class
//
// Created on: <18-Feb-2004 11:57:35 >
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

//include_once( 'benchmarks/classes/ezbenchmarkrunner.php' );

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
        //include_once( 'lib/ezutils/classes/ezcli.php' );
        $cli = eZCLI::instance();
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
