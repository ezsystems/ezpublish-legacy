<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor
// SOFTWARE RELEASE: 5.x
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

class ezoeInfo
{
    static function info()
    {
        return array( 'Name'      => '<a href="http://projects.ez.no/ezoe">eZ Online Editor</a> extension',
                      'Version'   => '//autogentag//',
                      'Copyright' => 'Copyright (C) 1999-2010 eZ Systems AS',
                      'License'   => '//EZP_LICENSE//',
                      'Includes the following third-party software' => array( 'Name' => 'Ephox Enterprise TinyMCE Javascript HTML WYSIWYG editor',
                                                                              'Version' => '3.3.9.3-328',
                                                                              'Copyright' => 'Copyright (C) 2010, Ephox Corporation, All rights reserved.',
                                                                              'License' => 'GNU Lesser General Public License v2.1',),
                      'Includes the following third-party icons'    => array( 'Name' => 'Tango Icon theme',
                                                                              'Version' => '0.8.90',
                                                                              'Copyright' => 'Copyright (C) 1999-2010 http://tango.freedesktop.org/Tango_Icon_Library',
                                                                              'License' => 'Creative Commons Attribution-ShareAlike 2.5',)
                    );
    }
}

?>
