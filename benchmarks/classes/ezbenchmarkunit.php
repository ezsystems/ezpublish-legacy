<?php
//
// Definition of eZBenchmarkUnit class
//
// Created on: <18-Feb-2004 11:55:40 >
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
