<?php
//
// Created on: <26-Aug-2003 15:15:32 kk>
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

include_once( 'lib/ezpdf/classes/class.ezpdf.php' );
include_once( 'lib/ezpdf/classes/class.pdf.php' );

include_once( 'lib/ezfile/classes/ezfilehandler.php' );

/*!
  \class eZPDF ezpdf.php
  \brief The class eZPDF does

*/

class eZPDF
{

    /*!
     Initializes the object with the name $name, default is "attribute".
    */
    function eZPDF( $name = "pdf" )
    {
        $this->Operators = array( $name );
    }

    /*!
     Returns the template operators.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'operation' => array( 'type' => 'string',
                                            'required' => true,
                                            'default' => '' ),
                      'text' => array( 'type' => 'string',
                                            'required' => false,
                                            'default' => '' ) );
    }

    /*!
     Display the variable.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $namedParameters['operation'] )
        {
            case 'table':
            {
            }

            case 'create':
            {
                $this->PDF = new Cezpdf();
                $this->PDF->selectFont( 'lib/ezpdf/classes/fonts/Helvetica.afm' );
                eZDebug::writeNotice( 'PDF file created' );
            } break;

            case 'newpage':
            {
                $this->PDF->ezNewPage();
            } break;

            case 'close':
            {
                include_once( 'lib/ezutils/classes/eztexttool.php' );
                $filename = 'tmp.pdf';
                eZFile::create( $filename, eZSys::storageDirectory().'/pdf', $this->PDF->ezOutput() );
                eZDebug::writeNotice( 'PDF file closed and saved to '.eZSys::storageDirectory().'/pdf/'.$filename  );
            } break;

            case 'text':
            {
                $text = $namedParameters['text'];
                $this->PDF->ezText( $text );
                eZDebug::writeNotice( '"'.$text.'" added to pdf file.' );
            } break;

            default:
            {
                $text =& $operatorValue;
                $this->PDF->ezText( $text );
                eZDebug::writeNotice( '"'.$text.'" added to pdf file.' );
                $operatorValue = null;
            }

        }

    }


    /// The array of operators, used for registering operators
    var $Operators;
    var $PDF;

}


?>
