<?php
//
// Created on: <18-Apr-2002 10:04:48 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

$module = $Params['Module'];
$referenceType = $Params['ReferenceType'];

$Result = array();
// $Result["pagelayout"] = false;
// $Result["external_css"] = true;

switch ( $referenceType )
{
    case 'ez':
    {
        $referenceResult = eZReferenceDocument( $module, '/reference/view/ez', $referenceType, array_slice( $Params['Parameters'], 1 ) );
    } break;
    default:
    {
        return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
    } break;
}

$tpl = templateInit();

$tpl->setVariable( 'reference_result', $referenceResult );
$tpl->setVariable( 'reference_type', $referenceType );

$Result['content'] = $tpl->fetch( "design:reference/view/$referenceType/full.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/reference', 'Reference documentation' ) ) );


?>
