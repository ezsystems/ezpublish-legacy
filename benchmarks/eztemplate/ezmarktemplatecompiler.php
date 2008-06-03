<?php
//
// Definition of eZMarkTemplateCompiler class
//
// Created on: <18-Feb-2004 11:54:17 >
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezmarktemplatecompiler.php
*/

/*!
  \class eZMarkTemplateCompiler ezmarktemplatecompiler.php
  \brief The class eZMarkTemplateCompiler does

*/

class eZMarkTemplateCompiler extends eZBenchmarkCase
{
    /*!
     Constructor
    */
    function eZMarkTemplateCompiler( $name )
    {
        $this->eZBenchmarkCase( $name );
        $this->addMark( 'markProcess', 'Processed template mark' );
        $this->addMark( 'markCompilation', 'Compiled template mark' );
    }

    function prime( &$tr )
    {
        require_once( 'kernel/common/template.php' );
        $tpl = templateInit();
        $tpl->setIsCachingAllowed( true );
        eZTemplateCompiler::setSettings( array( 'compile' => true,
                                                'comments' => false,
                                                'accumulators' => false,
                                                'timingpoints' => false,
                                                'fallbackresource' => false,
                                                'nodeplacement' => false,
                                                'execution' => true,
                                                'generate' => true,
                                                'compilation-directory' => 'benchmarks/eztemplate/compilation' ) );
        $expected = $tpl->fetch( 'benchmarks/eztemplate/mark.tpl' );
        eZTemplateCompiler::setSettings( array( 'compile' => true,
                                                'comments' => false,
                                                'accumulators' => false,
                                                'timingpoints' => false,
                                                'fallbackresource' => false,
                                                'nodeplacement' => false,
                                                'execution' => true,
                                                'generate' => false,
                                                'compilation-directory' => 'benchmarks/eztemplate/compilation' ) );
        $tpl->reset();
        $this->TPL = $tpl;
    }

    function markProcess( &$tr )
    {
        $tpl =& $this->TPL;

        $tpl->setIsCachingAllowed( false );
        $expected = $tpl->fetch( 'benchmarks/eztemplate/mark.tpl' );
    }

    function markCompilation( &$tr )
    {
        $tpl =& $this->TPL;

        $tpl->setIsCachingAllowed( true );
        $actual = $tpl->fetch( 'benchmarks/eztemplate/mark.tpl' );
    }

    var $TPL;
}

?>
