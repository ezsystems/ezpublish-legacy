<?php
//
// Definition of eZTestTemplateOutput class
//
// Created on: <30-Jan-2004 11:59:49 >
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
//        $this->addTest( 'testImage', 'Testing creation of Images' );
    }

    function testImage( &$tr )
    {
        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();

        $fp = fopen( 'tests/eztemplate/image.exp', 'rb' );
        $expected = fread( $fp, filesize( 'tests/eztemplate/image.exp' ) );
        fclose( $fp );

        $tpl->reset();

        eZTemplateCompiler::setSettings( array( 'compile' => true,
                                                'comments' => false,
                                                'accumulators' => false,
                                                'timingpoints' => false,
                                                'fallbackresource' => false,
                                                'nodeplacement' => false,
                                                'execution' => true,
                                                'generate' => true,
                                                'compilation-directory' => 'tests/eztemplate/compilation' ) );
        $actual = $tpl->fetch( 'tests/eztemplate/image.tpl' );
        $fp = fopen( 'tests/eztemplate/image.out', 'w' );
        fwrite( $fp, $actual );
        fclose( $fp );

        $tr->assert( $actual == $expected, 'String compare of results' );
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
