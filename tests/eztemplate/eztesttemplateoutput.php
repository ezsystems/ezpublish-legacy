<?php
//
// Definition of eZTestTemplateOutput class
//
// Created on: <30-Jan-2004 11:59:49 >
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

/*! \file eztesttemplateoutput.php
*/

/*!
  \class eZTestTemplateOutput eztesttemplateoutput.php
  \brief The class eZTestTemplateOutput does

*/

class eZTestTemplateOutput extends eZTestCase
{
    /*!
     Constructor
    */
    function eZTestTemplateOutput( $name = false )
    {
        $this->eZTestCase( $name );
        $this->addTest( 'testOutput', 'Compiled template output' );
        $this->addTest( 'testZero', 'Handling of zero elements' );
    }

    function testOutput( &$tr )
    {
        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();

        $tpl->setIsCachingAllowed( false );
        $tpl->setIsDebugEnabled( false );
        $expected = $tpl->fetch( 'tests/eztemplate/output.tpl' );
        $fp = fopen( 'tests/eztemplate/output.exp', 'w' );
        fwrite( $fp, $expected );
        fclose( $fp );

        $tpl->setIsCachingAllowed( true );
        $tpl->reset();

        $tpl->setIsDebugEnabled( false );
        eZTemplateCompiler::setSettings( array( 'compile' => true,
                                                'comments' => false,
                                                'accumulators' => false,
                                                'timingpoints' => false,
                                                'fallbackresource' => false,
                                                'nodeplacement' => false,
                                                'execution' => true,
                                                'generate' => true,
                                                'compilation-directory' => 'tests/eztemplate/compilation' ) );
        $actual = $tpl->fetch( 'tests/eztemplate/output.tpl' );
        $fp = fopen( 'tests/eztemplate/output.out', 'w' );
        fwrite( $fp, $actual );
        fclose( $fp );

        $tr->assert( $actual == $expected );
    }

    function testZero( &$tr )
    {
        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();

        $tpl->setIsCachingAllowed( false );
        $expected = $tpl->fetch( 'tests/eztemplate/empty.tpl' );

        $actual = '0';

        $tr->assert( strcmp( $actual, $expected ) == 0, 'String compare of results' );
    }
}

?>
