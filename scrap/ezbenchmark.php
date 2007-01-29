<?php
// 
// $Id: ezbenchmark.php,v 1.7.2.1 2002/04/05 08:10:04 bf Exp $
//
// Definition of eZTextTool class
//
// Created on: <23-Jan-2001 12:34:54 bf>
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

//!! eZCommon
//! Provied utility functions for http.
/*!

  \code
  include_once( "classes/ezbenchmark.php" );

  $bench = new eZBenchmark();
  $bench->start();
  
  // do something here

  $bench->stop();
  $bench->printResults();
  \endcode  
*/

class eZBenchmark
{
    /*!
      Creates a new eZBenchmark object.
    */
    function eZBenchmark( )
    {

    }

    /*!
      Starts a new benchmark.
    */
    function start()
    {
        $this->StartTime = microtime();
    }

    /*!
      Stops the benchmark interval.
    */
    function stop()
    {
        $this->StopTime = microtime();
    }

    /*!
      Returns the elapsed time.
    */
    function elapsed()
    {
        $time_1 = explode( " ", $this->StartTime );
        $time_2 = explode( " ", $this->StopTime );

        ereg( "0\.([0-9]+)", "" . $time_1[0], $t1 );
        ereg( "0\.([0-9]+)", "" . $time_2[0], $t2 );

        $Start = $time_1[1] . "." . $t1[1];
        $Stop = $time_2[1] . "." . $t2[1];    
        
        $elapsed = $Stop - $Start;
        $elapsed = number_format( ( $elapsed ), 2 );
        return $elapsed;
    }
    
    /*!
      Prints the benchmark results.
    */
    function printResults( $return=false )
    {
        $elapsed = $this->elapsed();
        if ( $return == false )
        {
            print( "Time elapsed: " .  $elapsed . " seconds.<br>" );
        }
        else
        {
            return "Time elapsed: " . $elapsed . " seconds.<br>";
        }
        
    }

    
    var $StartTime;
    var $StopTime;
}

?>
