<?php
//
// eZSetup - init part initialization
//
// Created on: <08-Nov-2002 11:00:54 kd>
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

$stepTable = array( 1 => array( 'name' => 'welcome',
                                'description' => 'Welcome' ),
                    2 => array( 'name' => 'system_check',
                                'description' => 'System check' ),
                    3 => array( 'name' => 'details',
                                'description' => 'Details' ),
                    4 => array( 'name' => 'database_choice',
                                'description' => 'Choose database' ),
                    5 => array( 'name' => 'site_language_options',
                                'description' => 'Language options' ),
                    6 => array( 'name' => 'regional_options',
                                'description' => 'Regional options' ),
                    7 => array( 'name' => 'summary',
                                'description' => 'Summary' ),
                    8 => array( 'name' => 'database_init',
                                'description' => 'Initialize Database' ),
                    9 => array( 'name' => 'email_settings',
                                'description' => 'Email Settings' ),
                    10 => array( 'name' => 'site_details',
                                 'description' => 'Site Details' ),
                    11 => array( 'name' => 'security',
                                 'description' => 'Securing Site' ),
                    12 => array( 'name' => 'registration',
                                 'description' => 'Registration' ),
                    13 => array( 'name' => 'send_registration',
                                 'description' => 'Send Registration' ),
                    14 => array( 'name' => 'finished',
                                 'description' => 'Finished' ) );

$mainStepTable = array( array( 'name_id' => 1,
                               'id_list' => array( 1 ) ),
                        array( 'name_id' => 2,
                               'id_list' => array( 2, 3 ) ),
                        array( 'name' => 'Collecting Information',
                               'name_id' => false,
                               'id_list' => array( 4, 5, 6, 7 ) ),
                        array( 'name' => 'Database Setup',
                               'name_id' => false,
                               'id_list' => array( 8 ) ),
                        array( 'name' => 'Post Configuration',
                               'name_id' => false,
                               'id_list' => array( 9, 10, 11, 12, 13 ) ),
                        array( 'name_id' => 14,
                               'id_list' => array( 14 ) ) );

?>
