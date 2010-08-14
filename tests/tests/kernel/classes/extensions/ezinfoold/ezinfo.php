<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Find
// SOFTWARE RELEASE: 1.0.x
// COPYRIGHT NOTICE: Copyright (C) 2007 eZ Systems AS
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

/*! \file ezinfo.php
*/

/*!
  \class eZFindInfo ezinfo.php
  \brief The class eZFindInfo does

*/

class ezinfooldInfo
{
    static function info()
    {
        return array(
            'Name' => "Old eZ Info",
            'Version' => '1.0',
            'Copyright' => "Copyright © 2010 eZ Systems AS.",
            'Info_url' => "http://ez.no",
            'License' => "GNU General Public License v2.0",
            'Includes the following third-party software' => array(
                'name' => 'Software 1',
                'Version' => '1.1',
                'copyright' => 'Some company.',
                'license' => 'Apache License, Version 2.0',
                'info_url' => 'http://company.com',
             ),
            'Includes the following third-party software (2)' => array(
                'name' => 'Software 2',
                'Version' => '2.0',
                'copyright' => 'Some other company.',
                'license' => 'GNU Public license V2.0',
            ),
        );
    }
}

?>
