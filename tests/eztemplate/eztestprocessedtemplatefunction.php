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

/*! \file eztesttemplatefunction.php
*/

/*!
  \class eZTestTemplateFunction eztesttemplatefunction.php
  \brief The class eZTestTemplateFunction does

*/

class eZTestProcessedTemplateFunction extends eZTestCase
{
    /*!
     Constructor
    */
    function eZTestProcessedTemplateFunction( $name = false )
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
        $expectedFileName = str_replace( '.tpl', '.exp', $templateFile );
        if ( file_exists( $expectedFileName ) )
        {
            $expected = file_get_contents( $expectedFileName );
        }
        else
        {
            $tr->assert( false, 'Missing expected test file ' . $expectedFileName );
        }

        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();
        eZTemplateDesignResource::setDesignStartPath( "tests/eztemplate/design" );
        $tpl->reset();

        $tpl->setIsDebugEnabled( false );
        $tpl->setIsCachingAllowed( false );

        eZTemplateCompiler::setSettings( array( 'compile' => false,
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

        $tr->assert( !$tpl->hasErrors(), 'Template errors, details will be in debug output' );
        $tr->assert( !$tpl->hasWarnings(), 'Template warnings, details will be in debug output' );

        $actualFileName = str_replace( '.tpl', '.pout', $templateFile );
        $fp = fopen( $actualFileName, 'w' );
        fwrite( $fp, $actual );
        fclose( $fp );

        $tr->assert( strcmp( $actual, $expected ) == 0, 'String compare of results' );
    }
}

?>
