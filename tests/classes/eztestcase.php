<?php
//
// Definition of eZTestCase class
//
// Created on: <30-Jan-2004 08:44:55 >
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

/*! \file eztestcase.php
*/

/*!
  \class eZTestCase eztestcase.php
  \ingroup eZTest
  \brief eZTestCase provides a base class for doing automated test cases

*/

include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'tests/classes/eztestunit.php' );

class eZTestCase extends eZTestUnit
{
    /*!
     Constructor
    */
    function eZTestCase( $name = false )
    {
        $this->eZTestUnit( $name );
    }

    /*!
     Adds a new test method \a $method. If \a $name is empty the method name is used as name.
    */
    function addTest( $method, $name = false )
    {
        if ( !method_exists( $this, $method ) )
        {
            eZDebug::writeWarning( "Test method $method in test " . $this->Name . " does not exist, cannot add",
                                   'eZTestCase::addTest' );
        }
        if ( !$name )
            $name = $method;
        $this->addTestEntry( array( 'name' => $name,
                                    'object' => &$this,
                                    'method' => $method ) );
    }
}

?>
