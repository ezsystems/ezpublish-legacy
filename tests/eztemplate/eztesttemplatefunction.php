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

/*! \file eztesttemplatefunction.php
*/

/*!
  \class eZTestTemplateFunction eztesttemplatefunction.php
  \brief The class eZTestTemplateFunction does

*/

class eZTestTemplateFunction extends eZTestCase
{
    /*!
     Constructor
    */
    function eZTestTemplateFunction( $name = false )
    {
        $this->eZTestCase( $name );

        foreach ( glob('tests/eztemplate/functions/*.tpl') as $template )
        {
            $this->addTemplateTest( $template );
        }
    }

    function addTemplateTest( $file )
    {
        $name = str_replace( 'tests/eztemplate/functions/', '', $file );
        $name = str_replace( '.tpl', '', $name );
        $name = ucwords( $name );
        $this->addTest( 'testTemplate', $name, $file );
    }

    function testTemplate( &$tr, $templateFile )
    {
        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();
        eZTemplateDesignResource::setDesignStartPath( "tests/eztemplate/design" );
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

        preg_match( "/^(.+).tpl/", $templateFile, $matches );
        $phpFile = $matches[1] . '.php';

        if ( file_exists( $phpFile ) )
        {
            include( $phpFile );
        }

        $actual = $tpl->fetch( $templateFile );

        $expectedFileName = str_replace( '.tpl', '.exp', $templateFile );
        if ( file_exists( $expectedFileName ) )
        {
            $expected = file_get_contents( $expectedFileName );
        }
        else
        {
            $tr->assert( false, 'Missing expected test file ' . $expectedFileName );
        }

        $actualFileName = str_replace( '.tpl', '.out', $templateFile );
        $fp = fopen( $actualFileName, 'w' );
        fwrite( $fp, $actual );
        fclose( $fp );

        $tr->assert( strcmp( $actual, $expected ) == 0, 'String compare of results' );
    }
}

?>
