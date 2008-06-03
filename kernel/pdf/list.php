<?php
//
// eZSetup - init part initialization
//
// Created on: <29-Oct-2003 14:49:54 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

$Module = $Params['Module'];

require_once( 'kernel/common/template.php' );
//include_once( 'kernel/classes/ezpdfexport.php' );

// Create new PDF Export
if ( $Module->isCurrentAction( 'NewExport' ) )
{
    return $Module->redirect( 'pdf', 'edit' );
}
//Remove existing PDF Export(s)
else if ( $Module->isCurrentAction( 'RemoveExport' ) && $Module->hasActionParameter( 'DeleteIDArray' ) )
{
    $deleteArray = $Module->actionParameter( 'DeleteIDArray' );
    foreach ( $deleteArray as $deleteID )
    {
        // remove draft if it exists:
        $pdfExport = eZPDFExport::fetch( $deleteID, true, eZPDFExport::VERSION_DRAFT );
        if ( $pdfExport )
        {
            $pdfExport->remove();
        }
        // remove default version:
        $pdfExport = eZPDFExport::fetch( $deleteID );
        if ( $pdfExport )
        {
            $pdfExport->remove();
        }
    }
}

$exportArray = eZPDFExport::fetchList();
$exportList = array();
foreach( $exportArray as $export )
{
    $exportList[$export->attribute( 'id' )] = $export;
}

$tpl = templateInit();

$tpl->setVariable( 'pdfexport_list', $exportList );

$Result = array();
$Result['content'] = $tpl->fetch( "design:pdf/list.tpl" );
$Result['path'] = array( array( 'url' => 'kernel/pdf',
                                'text' => ezi18n( 'kernel/pdf', 'PDF Export' ) ) );

?>
