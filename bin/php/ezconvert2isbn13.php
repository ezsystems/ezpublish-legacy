#!/usr/bin/env php
<?php
//
// Created on: <24-Apr-2007 09:53:50 bjorn>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*!
  \file
  \brief Converts ISBN-10 numbers to ISBN-13.

  The script should be run by command line with example:

  php bin/php/ezconvert2isbn13.php --all-classes

  Depending on the parameter, the script will search through contentobjects and convert
  ezisbn values in content attributes from ISBN-10 to ISBN-13. The script will also set the hyphen on the correct
  place as well. You should set the class attribute to ISBN-13 in the contentclass before running
  this script or add the flag --force as a parameter when you're running the script.

  When --force is used, the is ISBN-13 will also be updated to ISBN-13 at the
  contentclass level.

  Example:
  --class-id=2 Will Go through all ezisbn attributes in the class with id 2 and convert everyone which is a
               ISBN-10 value.
  --attribute-id=12 will check if this is an ISBN datatype and convert all ISBN-13 values in the
                    attribute with id 12.
  --all-classes Does not have any argument, and converts all contentobject attributes that is set to ISBN-13.

  --force or -f will work in addition to all the options above and set the class attribute to ISBN-13, even if it was
                ISBN-10 before.
*/

set_time_limit( 0 );

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish ISBN-10 to ISBN-13 converter\n\n" .
                                                        "Converts an ISBN-10 number to ISBN-13\n" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[class-id:][attribute-id:][all-classes][f|force]",
                                "",
                                array( 'class-id' => 'The class id for the ISBN attribute.',
                                       'attribute-id' => 'The attribute id for the ISBN attribute which should be converted.',
                                       'all-classes' => 'Will convert all ISBN attributes in all content classes.',
                                       'f' => 'Short alias for force.',
                                       'force' => 'Will convert all attributes even if the class is set to ISBN.' ) );
$script->initialize();

$classID = $options['class-id'];
$attributeID = $options['attribute-id'];
$allClasses = $options['all-classes'];
$force = $options['force'];

$params = array( 'force' => $force );
$converter = new eZISBN10To13Converter( $script, $cli, $params );

$found = false;
if ( $allClasses === true )
{
    $allClassesStatus = $converter->addAllClasses();
    $found = true;
}
else
{
    if ( is_numeric( $classID ) )
    {
        $classStatus = $converter->addClass( $classID );
        $found = true;
    }

    if ( is_numeric( $attributeID ) )
    {
        $attributeStatus = $converter->addAttribute( $attributeID );
        $found = true;
    }
}

if ( $found == true )
{
    if ( $converter->attributeCount() > 0 )
    {
        $converter->execute();
    }
    else
    {
        $cli->output( 'Did not find any ISBN attributes.' );
    }
}
else
{
    $script->showHelp();
}

$script->shutdown();

?>
