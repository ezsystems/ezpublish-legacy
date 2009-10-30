#!/usr/bin/env php
<?php
//
// Created on: <15-Nov-2009 bd>
//
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.2
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "Fixes eZImageAliasHandler bug (http://issues.ez.no/15155)" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[n]",
                                "",
								array( 'n' => 'Do not wait the 10 safety seconds before starting' ) );
$script->initialize();

$output = new ezcConsoleOutput();
$output->formats->error->style = array( 'bold' );
$output->formats->error->color = 'red';

if ( !isset( $options['n'] ) )
{
    $output->outputLine( 'This script will delete images that look orphan' );
    $output->outputLine( 'Press ctrl-c in the next 10 seconds to prevent the script from starting...' );
    sleep( 10 );
}

try
{
	$output->outputLine( 'Looking for obsolete image files...' );

	// Fetch all image files in ezimagefile table
	$aImageFiles = eZPersistentObject::fetchObjectList( eZImageFile::definition() );
	$nbImageFiles = count( $aImageFiles );

	if( $nbImageFiles > 0 )
	{
		// Progress bar initialization
		$progressBarOptions = array(
			'emptyChar'		=> ' ',
			'barChar'		=> '='
		);
		$progressBar = new ezcConsoleProgressbar( $output, $nbImageFiles, $progressBarOptions );

		// Loop the image files and check if it is still used by a content object attribute. If not, delete it.
		foreach( $aImageFiles as $image )
		{
			$filePath = $image->attribute( 'filepath' );
			$dirpath = dirname( $filePath );
			$contentObjectAttributeID = $image->attribute( 'contentobject_attribute_id' );
			$dbResult = eZImageFile::fetchImageAttributesByFilepath( $filePath, $contentObjectAttributeID );
			if( count( $dbResult ) == 0 )
			{
				$file = eZClusterFileHandler::instance( $filePath );
				if ( $file->exists() ) // Delete the file physically
				{
					$file->delete();
					eZImageFile::removeFilepath( $contentObjectAttributeID, $filePath );
					eZDir::cleanupEmptyDirectories( $dirpath );
				}

				// Delete the obsolete reference in the database
				$image->remove();
			}

			$progressBar->advance();
		}

		$progressBar->finish();
		$output->outputLine();
	}
	else
	{
		$output->outputText( 'No image file found !' );
		$output->outputLine();
	}

	$script->shutdown();
}
catch(Exception $e)
{
	$output->outputText( $e->getMessage(), 'error' );
	$output->outputLine();
	$script->shutdown( $e->getCode() );
}
?>