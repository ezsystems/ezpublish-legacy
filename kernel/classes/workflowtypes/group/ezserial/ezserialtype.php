<?php
//
// Definition of eZSerialType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZSerialType ezserialtype.php
  \brief Serialized event grouping

*/

include_once( "kernel/classes/ezworkflowtype.php" );

define( "EZ_WORKFLOW_TYPE_SERIAL_ID", "ezserial" );

class eZSerialType extends eZWorkflowGroupType
{
    function eZSerialType()
    {
        $this->eZWorkflowGroupType( EZ_WORKFLOW_TYPE_SERIAL_ID, ezi18n( 'kernel/workflow/group', "Serial" ) );
    }

}

eZWorkflowGroupType::registerType( EZ_WORKFLOW_TYPE_SERIAL_ID, "ezserialtype" );

?>
