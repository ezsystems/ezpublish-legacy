<?php
//
// Definition of eZBenchmarkUnit class
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

/*! \file ezbenchmarkunit.php
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
