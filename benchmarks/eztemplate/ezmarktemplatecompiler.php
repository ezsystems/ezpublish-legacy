<?php
//
// Definition of eZMarkTemplateCompiler class
//
// Created on: <18-Feb-2004 11:54:17 >
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
        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();
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
