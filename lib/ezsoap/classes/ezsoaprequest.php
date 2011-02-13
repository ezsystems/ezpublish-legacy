<?php
//
// Definition of eZSOAPRequest class
//
// Created on: <19-Feb-2002 15:42:03 bf>
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

/*!
  \class eZSOAPRequest ezsoaprequest.php
  \ingroup eZSOAP
  \brief eZSOAPRequest handles SOAP request messages

*/

class eZSOAPRequest extends eZSOAPEnvelope
{
    /*!
     Constructs a new eZSOAPRequest object. You have to provide the request name
     and the target namespace for the request.

     \param name
     \param namespace
     \param parameters, assosiative array, example: array( 'param1' => 'value1, 'param2' => 'value2' )
    */
    function eZSOAPRequest( $name="", $namespace="", $parameters = array() )
    {
        $this->Name = $name;
        $this->Namespace = $namespace;

        // call the parents constructor
        $this->eZSOAPEnvelope();

        foreach( $parameters as $name => $value )
        {
            $this->addParameter( $name, $value );
        }
    }

    /*!
      Returns the request name.
    */
    function name()
    {
        return $this->Name;
    }

   /** Returns the request target namespace.
     *
     * @since 4.1.4 (renamed from namespace() for php 5.3 compatibility)
     * @return string
    */
    function ns()
    {
        return $this->Namespace;
    }

    /*!
     Adds a new attribute to the body element.

     \param attribute name
     \param attribute value
     \param prefix
    */
    function addBodyAttribute( $name, $value, $prefix = false )
    {
        $this->BodyAttributes[( $prefix ? $prefix . ':' : '' ) . $name] = $value;
    }

    /*!
      Adds a new parameter to the request. You have to provide a prameter name
      and value.
    */
    function addParameter( $name, $value )
    {
        $this->Parameters[] = new eZSOAPParameter( $name, $value );
    }

    /*!
      Returns the request payload
    */
    function payload()
    {
        $doc = new DOMDocument( "1.0" );
        $doc->name = 'eZSOAP message';

        $root = $doc->createElementNS( eZSOAPEnvelope::ENV, eZSOAPEnvelope::ENV_PREFIX . ':Envelope' );

        $root->setAttribute( 'xmlns:' . eZSOAPEnvelope::XSI_PREFIX, eZSOAPEnvelope::SCHEMA_INSTANCE );
        $root->setAttribute( 'xmlns:' . eZSOAPEnvelope::XSD_PREFIX, eZSOAPEnvelope::SCHEMA_DATA );
        $root->setAttribute( 'xmlns:' . eZSOAPEnvelope::ENC_PREFIX, eZSOAPEnvelope::ENC );

        // add the body
        $body = $doc->createElement( eZSOAPEnvelope::ENV_PREFIX . ':Body' );
        $body->setAttribute( 'xmlns:req', $this->Namespace );

        foreach( $this->BodyAttributes as $name => $value )
        {
            $body->setAttribute( $name, $value );
        }

        $root->appendChild( $body );

        // add the request
        $request = $doc->createElement( 'req:' . $this->Name );

        // add the request parameters
        foreach ( $this->Parameters as $parameter )
        {
            $param = eZSOAPCodec::encodeValue( $doc, $parameter->name(), $parameter->value() );

            if ( $param == false )
            {
                eZDebug::writeError( "Error encoding data for payload: " . $parameter->name(), "eZSOAPRequest::payload()" );
                continue;
            }
            $request->appendChild( $param );
        }

        $body->appendChild( $request );

        $doc->appendChild( $root );
        return $doc->saveXML();
    }

    /// The request name
    public $Name;

    /// The request target namespace
    public $Namespace;

    /// Additional body element attributes.
    public $BodyAttributes = array();

    /// Contains the request parameters
    public $Parameters = array();
}

?>
