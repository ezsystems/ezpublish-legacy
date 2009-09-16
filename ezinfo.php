<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore
// SOFTWARE RELEASE: 1.x
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
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

class ezjscoreInfo
{
    static function info()
    {
        $eZCopyrightString = 'Copyright (C) 1999-' . date('Y') . ' eZ Systems AS';

        return array( 'Name'      => 'ezjscore',
                      'Version'   => '1.0.0rc1',
                      'Copyright' => $eZCopyrightString,
                      'License'   => 'GNU General Public License v2.0',
                      'Includes the following third-party software' => array( 'Name' => 'YUI',
                                                                              'Version' => '3.0Beta1 and 2.7.0',
                                                                              'Copyright' => 'Copyright (c) 2009, Yahoo! Inc. All rights reserved.',
                                                                              'License' => 'Licensed under the BSD License',),
                      'Includes the following library'              => array( 'Name' => 'JSMin (jsmin-php)',
                                                                              'Version' => '1.1.1',
                                                                              'Copyright' => '2002 Douglas Crockford <douglas@crockford.com> (jsmin.c), 2008 Ryan Grove <ryan@wonko.com> (PHP port)',
                                                                              'License' => 'Licensed under the MIT License',),
                    );
    }
}

?>