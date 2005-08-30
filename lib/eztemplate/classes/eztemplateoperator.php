<?php
//
// Definition of eZTemplateOperator class
//
// Created on: <01-Mar-2002 13:50:09 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZTemplateOperator eztemplateoperator.php
  \ingroup eZTemplateOperators
  \brief Base documentation class for operators

  \note This class is never used but only exists for documentation purposes.
*/

class eZTemplateOperator
{
    /*!
     Returns the template operators which are registered when using eZTemplate::registerOperators()
    */
    function &operatorList()
    {
        $operationList = array();
        return $operationList;
    }

    /*!
     Returns an array of named parameters, this allows for easier retrieval
     of operator parameters. This also requires the function modify() has an extra
     parameter called $named_params.

     The position of each element (starts at 0) represents the position of the original
     sequenced parameters. The key of the element is used as parameter name, while the
     contents define the type and requirements.
     The keys of each element content is:
     * type - defines the type of parameter allowed
     * required - boolean which says if the parameter is required or not, if missing
                  and required an error is displayed
     * default - the default value if the parameter is missing
    */
    function namedParameterList()
    {
        return array();
    }

    /*!
     Modifies the input variable $value and sets the output result in the same variable.

     \note Remember to use references on the function arguments.
    */
    function modify( /*! The operator element, can be used for doing advanced querying but should be avoided. */
                     &$element,
                     /*! The template object which called this class */
                     &$tpl,
                     /*! The name of this operator */
                     &$op_name,
                     /*! The parameters for this operator */
                     &$op_params,
                     /*! The namespace which this operator works in */
                     &$namespace,
                     /*! The current namespace for functions, this is usually used in functions
                         for setting new variables. */
                     /*! The input/output value */
                     &$value,
                     /*! The parameters as named lookups, only required if namedParameterList() is defined.
                         The values of each parameter is also fetched for you. */
                     &$named_params )
    {
    }

}

?>
