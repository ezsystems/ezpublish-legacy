<?php
//
// Definition of eZTestUnit class
//
// Created on: <30-Jan-2004 08:57:09 >
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

/*! \file eztestunit.php
*/

/*!
  \class eZTestUnit eztestunit.php
  \ingroup eZTest
  \brief The class eZTestUnit does

*/

class eZTestUnit
{
    /*!
     Constructor
    */
    function eZTestUnit( $name = false )
    {
        if ( !$name )
            $name = get_class( $this );
        $this->Name = $name;
        $this->TestList = array();
    }

    /*!
     \virtual
     This function is called before the test method is called.
     It can be overriden to have common initialization code for all tests.
    */
    function setup()
    {
    }

    /*!
     \virtual
     This function is called after the test method has finished its call.
     It can be overriden to remove any initialization done in setup().
    */
    function teardown()
    {
    }

    /*!
     \return the number of tests in the unit.
    */
    function count()
    {
        return count( $this->TestList );
    }

    /*!
     \return an array with tests.
    */
    function testList()
    {
        return $this->TestList;
    }

    /*!
     \return the name of the test unit.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
     Adds a new test entry for the test list.
    */
    function addTestEntry( $entry )
    {
        $this->TestList[] = $entry;
    }

    /// \privatesection
    /// Name of test case
    var $Name;
    /// A list of tests to run
    var $TestList;
}

?>
