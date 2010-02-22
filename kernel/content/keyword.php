<?php
//
// Definition of List class
//
// Created on: <>
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

/*! \file
*/


$Module = $Params['Module'];
$Alphabet = rawurldecode( $Params['Alphabet'] );

$Offset = $Params['Offset'];
$ClassID = $Params['ClassID'];
$viewParameters = array( 'offset' => $Offset, 'classid' => $ClassID );

$tpl = eZTemplate::factory();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'alphabet', $Alphabet );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/keyword.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Keywords' ),
                                'url' => false ) );

?>
