<?php
//
// Definition of eZBenchmark class
//
// Created on: <18-Feb-2004 11:45:27 >
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezbenchmark.php
*/

/*!
  \class eZBenchmark ezbenchmark.php
  \brief eZBenchmark provides a framework for doing benchmarks

*/

include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'benchmarks/classes/ezbenchmarkunit.php' );

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
            eZDebug::writeWarning( "Tried to add mark unit for an object which is not subclassed from eZBenchmarkUnit",
                                   'eZBenchmark::addMark' );
        }
    }
}

?>
