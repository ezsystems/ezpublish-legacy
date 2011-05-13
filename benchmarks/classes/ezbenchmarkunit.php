<?php
/**
 * File containing the eZBenchmarkUnit class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZBenchmarkUnit ezbenchmarkunit.php
  \ingroup eZTest
  \brief eZBenchmarkUnit provides a base class for doing automated benchmarks

  This class provides basic functionality and interface
  for creating benchmarks. It keeps a list of marks and
  a name which are accessible with markList() and name().

*/

class eZBenchmarkUnit
{
    /*!
     Constructor
    */
    function eZBenchmarkUnit( $name = false )
    {
        if ( !$name )
            $name = get_class( $this );
        $this->Name = $name;
        $this->MarkList = array();
    }

    /*!
     \virtual
     This function is called before the mark method run is done.
     It can be overriden to have initialization which should not be part of the mark measures.
    */
    function prime( &$tr )
    {
    }

    /*!
     \virtual
     This function is called after the mark method has finished its runs.
     It can be overriden to have cleanup any initialization done in prime().
    */
    function teardown()
    {
    }

    /*!
     \return the number of marks in the unit.
    */
    function count()
    {
        return count( $this->MarkList );
    }

    /*!
     \return an array with marks.
    */
    function markList()
    {
        return $this->MarkList;
    }

    /*!
     \return the name of the mark unit.
    */
    function name()
    {
        return $this->Name;
    }


    function addEntry( $entry )
    {
        if ( isset( $entry['parameter']['repeat_count'] ) )
            $entry['repeat_count'] = $entry['parameter']['repeat_count'];
        $this->MarkList[] = $entry;
    }

    /// \privatesection
    var $Name;
}

?>
