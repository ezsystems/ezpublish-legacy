<?php
// 
// $Id: ezbenchmark.php,v 1.7.2.1 2002/04/05 08:10:04 bf Exp $
//
// Definition of eZTextTool class
//
// Created on: <23-Jan-2001 12:34:54 bf>
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
