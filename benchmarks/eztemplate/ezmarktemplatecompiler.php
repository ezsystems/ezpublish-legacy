<?php
/**
 * File containing the eZMarkTemplateCompiler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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
        $tpl = eZTemplate::factory();
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
