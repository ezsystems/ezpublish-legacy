<?php
//
// Definition of eZUnpublishType class
//
// Created on: <14-ñÎ×-2003 17:48:11 sp>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezunpublishtype.php
*/

/*!
  \class eZUnpublishType ezunpublishtype.php
  \brief The class eZUnpublishType does

*/
include_once( 'kernel/classes/ezcontentobjectversion.php' );
define( "EZ_WORKFLOW_TYPE_UNPUBLISH_ID", "ezunpublish" );

class eZUnpublishType extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function eZUnpublishType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_UNPUBLISH_ID, "Unpublish" );
    }

    function execute( &$process, &$event )
    {
        $parameters = $process->attribute( 'parameter_list' );
        $object =& eZContentObject::fetch( $parameters['object_id'] );
        $version =& eZContentObjectVersion::fetchVersion( $parameters['version'], $parameters['object_id'] );
        $version->unpublish();
        return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
    }

}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_UNPUBLISH_ID, "ezunpublishtype" );

?>
