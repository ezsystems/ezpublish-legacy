<?php
//
// Definition of eZURLOperator class
//
// Created on: <18-Apr-2002 12:15:07 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

//!! eZKernel
//! The class eZURLOperator does
/*!

*/

class eZURLOperator
{
    /*!
     Initializes the image operator with the operator name $name.
    */
    function eZURLOperator( $url_name = 'ezurl',
                            $urlroot_name = 'ezroot',
                            $ezsys_name = 'ezsys',
                            $design_name = 'ezdesign',
                            $image_name = 'ezimage',
                            $ext_name = 'exturl' )
    {
        $this->Operators = array( $url_name, $urlroot_name, $ezsys_name, $design_name, $image_name, $ext_name );
        $this->URLName = $url_name;
        $this->URLRootName = $urlroot_name;
        $this->SysName = $ezsys_name;
        $this->DesignName = $design_name;
        $this->ImageName = $image_name;
        $this->ExtName = $ext_name;
        $this->Sys =& eZSys::instance();
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    function namedParameterList()
    {
        return array( 'quote_val' => array( 'type' => 'string',
                                            'required' => false,
                                            'default' => 'double' ) );
    }

    /*!
     */
    function modify( &$element, &$tpl, &$op_name, &$op_params, &$current_nspace, &$namespace, &$value, &$named_params )
    {
        switch ( $op_name )
        {
            case $this->URLName:
            {
                if ( strlen( $value ) > 0 and
                     $value[0] != '/' )
                    $value = '/' . $value;
                $value = $this->Sys->indexDir() . $value;
            } break;

            case $this->URLRootName:
            {
                if ( strlen( $value ) > 0 and
                     $value[0] != '/' )
                    $value = '/' . $value;
                $value = $this->Sys->wwwDir() . $value;
            } break;

            case $this->SysName:
            {
                if ( count( $op_params ) == 0 )
                    $tpl->warning( 'eZURLOperator' . $op_name, 'Requires attributename' );
                else
                {
                    $sysAttribute = $tpl->elementValue( $op_params[0], $namespace );
                    if ( !$this->Sys->hasAttribute( $sysAttribute ) )
                        $tpl->warning( 'eZURLOperator' . $op_name, "No such attribute '$sysAttribute' for eZSys" );
                    else
                        $value = $this->Sys->attribute( $sysAttribute );
                }
            } break;

            case $this->ImageName:
            {
                $ini =& eZINI::instance();
                $std_base = eZTemplateDesignResource::designSetting( 'standard' );
                $site_base = eZTemplateDesignResource::designSetting( 'site' );
                $std_file = "design/$std_base/images/$value";
                $site_file = "design/$site_base/images/$value";
                if ( file_exists( $site_file ) )
                    $value = $this->Sys->wwwDir() . "/$site_file";
                else if ( file_exists( $std_file ) )
                    $value = $this->Sys->wwwDir() . "/$std_file";
                else
                    $tpl->warning( 'eZURLOperator', "Image $value does not exist in any design" );
            } break;

            case $this->ExtName:
            {
                // TODO: Do something with external URLs.
                $value = "/$value";
            } break;

            case $this->DesignName:
            {
                $ini =& eZINI::instance();
                $std_base = eZTemplateDesignResource::designSetting( 'standard' );
                $site_base = eZTemplateDesignResource::designSetting( 'site' );
                $std_file = "design/$std_base/$value";
                $site_file = "design/$site_base/$value";
                if ( file_exists( $site_file ) )
                    $value = $this->Sys->wwwDir() . "/$site_file";
                else if ( file_exists( $std_file ) )
                    $value = $this->Sys->wwwDir() . "/$std_file";
                else
                    $tpl->warning( 'eZURLOperator', "Design element $value does not exist in any design" );
//                 $value = $this->Sys->wwwDir() . '/design/' . $tpl->variableValue( '$site.design', $namespace ) . "/$value";
            } break;
        }
        $quote = "\"";
        $val = $named_params['quote_val'];
        if ( $val == 'single' )
            $quote = "'";
        else if ( $val == 'no' )
            $quote = false;
        if ( $quote !== false )
            $value = $quote . $value . $quote;
    }

    var $Operators;
    var $URLName, $URLRootName, $DesignName, $ImageName;
    var $Sys;
};

?>
