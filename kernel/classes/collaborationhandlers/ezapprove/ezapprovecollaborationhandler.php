<?php
//
// Definition of eZApproveCollaborationHandler class
//
// Created on: <23-Jan-2003 11:57:11 amos>
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

/*! \file ezapprovecollaborationhandler.php
*/

/*!
  \class eZApproveCollaborationHandler ezapprovecollaborationhandler.php
  \brief The class eZApproveCollaborationHandler does

*/

class eZApproveCollaborationHandler extends eZCollaborationItemHandler
{
    /*!
     Constructor
    */
    function eZApproveCollaborationHandler()
    {
        $this->eZCollaborationItemHandler( 'ezapprove', 'Approval' );
    }

    function title( &$collaborationItem )
    {
        return "Approval";
    }

    function content( &$collaborationItem )
    {
        return array( "content_object_id" => $collaborationItem->attribute( "data_int1" ),
                      "content_object_version" => $collaborationItem->attribute( "data_int2" ) );
    }

    function handleCustomAction( &$collaborationItem )
    {
    }

}

?>
