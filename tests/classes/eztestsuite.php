<?php
//
// Definition of eZTestSuite class
//
// Created on: <30-Jan-2004 11:09:08 >
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

/*! \file eztestsuite.php
*/

/*!
  \class eZTestSuite eztestsuite.php
  \brief eZTestSuite allows multiple test units to be run as one test

*/

include_once( 'tests/classes/eztestunit.php' );

class eZTestSuite extends eZTestUnit
{
    /*!
     Initializes the test unit with the name \a $name.
    */
    function eZTestSuite( $name = false )
    {
        $this->eZTestUnit( $name = false );
    }

    /*!
     Adds all tests from the test unit \a $unit to this test suite.
    */
    function addUnit( &$unit )
    {
        if ( is_subclass_of( $unit, 'eztestunit' ) )
        {
            $testList = $unit->testList();
            foreach ( $testList as $entry )
            {
                $this->addTestEntry( $entry );
            }
        }
        else
        {
            eZDebug::writeWarning( "Tried to add test unit for an object which is not subclassed from eZTestUnit",
                                   'eZTestSuit::addUnit' );
        }
    }
}

?>
