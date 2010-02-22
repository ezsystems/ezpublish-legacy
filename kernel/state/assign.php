<?php
//
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

$Module = $Params['Module'];
$Result = array();
$Result['content'] = '';

// Identify whether the input data was submitted through URL parameters or through POST
if ( $Module->isCurrentAction( 'Assign' )                 and
     $Module->hasActionParameter( 'SelectedStateIDList' ) and
     $Module->hasActionParameter( 'ObjectID' ) )
{
    $selectedStateIDList = $Module->actionParameter( 'SelectedStateIDList' );
    $objectID = $Module->actionParameter( 'ObjectID' );
}
else
{
    $objectID = isset( $Params['ObjectID'] ) ? $Params['ObjectID'] : false;
    $selectedStateIDList = isset( $Params['SelectedStateID'] ) ? array( $Params['SelectedStateID'] ) : false ;
}

// Change object's state
if ( $objectID and $selectedStateIDList )
{
    if ( eZOperationHandler::operationIsAvailable( 'content_updateobjectstate' ) )
    {
        $operationResult = eZOperationHandler::execute( 'content', 'updateobjectstate',
                                                        array( 'object_id'     => $objectID,
                                                               'state_id_list' => $selectedStateIDList ) );
    }
    else
    {
        eZContentOperationCollection::updateObjectState( $objectID, $selectedStateIDList );
    }

    // Redirect to the provided URI, or to the root if not provided.
    // @TODO : in case this view is called through Ajax, make sure the module ends another way.
    $Module->hasActionParameter( 'RedirectRelativeURI' ) ? $Module->redirectTo( $Module->actionParameter( 'RedirectRelativeURI' ) ) : $Module->redirectTo( '/' );
}
elseif ( $objectID )
{
    // Propose an interface. The end-user probably accessed this view through a simple URL like
    // '/state/assign/<object_id>'
    if ( ( $object = eZContentObject::fetch( $objectID ) ) !== null  )
    {
        $tpl = eZTemplate::factory();
        $tpl->setVariable( 'node', $object->attribute( 'main_node' ) );
        $Result['content'] = $tpl->fetch( 'design:state/assign.tpl' );
    }
}
else
    $Module->hasActionParameter( 'RedirectRelativeURI' ) ? $Module->redirectTo( $Module->actionParameter( 'RedirectRelativeURI' ) ) : $Module->redirectTo( '/' );

$Result['path'] = array(
                    array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'State' ) ),
                    array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'Assign' ) )
                   );
?>