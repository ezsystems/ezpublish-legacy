<?php
//
// Definition of EZStepInstaller class
//
// Created on: <08-Aug-2003 14:46:44 kk>
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

/*! \file ezstep_class_definition.php
*/

/*!
  \class EZStepInstaller ezstep_class_definition.ph
  \brief The class EZStepInstaller provide a framework for eZStep installer classes

*/

class eZStepInstaller
{
    /*!
     Default constructor for eZ publish installer classes

    \param template
    \param http object
    \param ini settings object
    \param persistencelist, all previous posted data
    */
    function EZStepInstaller( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->Tpl =& $tpl;
        $this->Http =& $http;
        $this->Ini =& $ini;
        $this->PersistenceList =& $persistenceList;
    }

    /*!
     \virtual

     Processespost data from this class.
     \return  true if post data accepted, or false if post data is rejected.
    */
    function processPostData()
    {
    }

    /*!
     \virtual

    Performs test needed by this class.

    This class may access class variables to store data needed for viewing if output failed
    \return true if all tests passed and continue with next default step,
            number of next step if all tests passed and next step is "hard coded",
           false if tests failed
    */
    function init()
    {
    }

    /*!
    \virtual

    Display information and forms needed to pass this step.
    \return result to use in template
    */
    function &display()
    {
        return null;
    }

    var $Tpl;
    var $Http;
    var $Ini;
    var $PersistenceList;
}

?>
