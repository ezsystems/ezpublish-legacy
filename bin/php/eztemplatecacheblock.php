#!/usr/bin/env php
<?php
/**
 * File containing the eztemplaetcachebloc.php script.
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Template Cache Blocks Handler\n" .
                                                        "Allows for easy clearing of Cahe Blocks\n" .
                                                        "\n" .
                                                        "Clearing all cache-blocks of a single template file\n" .
                                                        "./bin/eztemplatecacheblock.php --clear-cache=design/standard/template/pagelayout.tpl\n" .
                                                        "Clearing a specific (template+line number) cache-block\n" .
                                                        "./bin/eztemplatecacheblock.php --clear-cache=design/standard/template/pagelayout.tpl--5" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[clear-cache:]",
                                "",
                                array( 'clear-cache' => ( "Clears a specific cache-block related to a given template file (and optionnnaly line number),\n" .
                                                         "syntax : <filepath/filename>[--<linenumber>}].\n" .
                                                         "Separate multiple files with a comma" ) ) );
$sys = eZSys::instance();
$ini = eZINI::instance();
$siteAccessList = $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' );

$script->initialize();

if ( $options['clear-cache'] )
{
    $templateList = explode( ',', $options['clear-cache'] );
    foreach ( $templateList as $item )
    {
        list( $templateFile, $lineNumber ) = explode( '--', $item );
        $templateFilePath = eZTemplateCacheBlock::templateBlockPath( $templateFile, $lineNumber ).'/*.cache';
        $cacheBaseDir = eZTemplateCacheBlock::templateBlockCacheDir();
        $cmd = "find $cacheBaseDir -path \"*/$templateFilePath\"";
        $fileList = array();
        exec( $cmd, $fileList );
        $fileHandler = eZClusterFileHandler::instance();
        foreach ( $fileList as $fileItem )
        {
            $fileHandler->fileDelete( $fileItem );
        }
    }

    $script->shutdown( 0 );
}

$cli->output( "You will need to specify what to clear with option --clear-cache" );

$script->shutdown( 1 );

