<?php
/**
 * File containing the eZBenchmark class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZBenchmark ezbenchmark.php
  \brief eZBenchmark provides a framework for doing benchmarks

*/

require_once( 'lib/ezutils/classes/ezdebug.php' );

class eZBenchmark extends eZBenchmarkUnit
{
    /*!
     Initializes the benchmark with the name \a $name.
    */
    function eZBenchmark( $name )
    {
        $this->eZBenchmarkUnit( $name );
    }

    function addMark( &$mark )
    {
        if ( is_subclass_of( $mark, 'ezbenchmarkunit' ) )
        {
            $markList = $mark->markList();
            foreach ( $markList as $entry )
            {
                $entry['name'] = $mark->name() . '::' . $entry['name'];
                $this->addEntry( $entry );
            }
        }
        else
        {
            eZDebug::writeWarning( "Tried to add mark unit for an object which is not subclassed from eZBenchmarkUnit", __METHOD__ );
        }
    }
}

?>
