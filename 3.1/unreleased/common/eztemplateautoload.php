<?php
//
// Created on: <05-Oct-2002 21:27:11 amos>
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

/*! \file eztemplateautoload.php
*/

// Operator autoloading

$eZTemplateOperatorArray = array();
$eZTemplateFunctionArray = array();
$eZTemplateFunctionArray[] = array( 'function' => 'eZObjectNotificationForwardInit',
                                    'function_names' => array( 'notification_edit_gui' ) );

if ( !function_exists( 'eZObjectNotificationForwardInit' ) )
{
    function &eZObjectNotificationForwardInit()
        {
            include_once( 'kernel/common/ezobjectforwarder.php' );
            $forward_rules = array(
                'notification_edit_gui' => array( 'template_root' => 'notification/rules',
                                                  'input_name' => 'notification_type',
                                                  'output_name' => 'notification_type',
                                                  'namespace' => 'Notification',
                                                  'attribute_access' => array( array( 'information',
                                                                                      'string' ) ),
                                                  'use_views' => false ) );
            return new eZObjectForwarder( $forward_rules );
        }
}

?>
