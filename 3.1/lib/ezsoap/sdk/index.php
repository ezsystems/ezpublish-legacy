<?php

$infoArray = array();
$infoArray["name"] = "eZ soap";
$infoArray["description"] = "
<h1>eZ soap&trade;</h1>
<p>
eZ soap&trade; is a library for SOAP communication. It handles both client and server side
functionality. eZ xml&trade; is used to handle XML manipulations in eZ soap&trade;.
</p>

<p>Definition of SOAP, from the W3C spec:</p>

<blockquote>SOAP is a lightweight protocol for exchange of information in a decentralized,
  distributed environment. It is an XML based protocol that consists of three
  parts: an envelope that defines a framework for describing what is in a message
  and how to process it, a set of encoding rules for expressing instances of
  application-defined data types, and a convention for representing remote
  procedure calls and responses.
</blockquote>

<p>
eZ soap&trade; follows the <a href='http://www.w3.org/TR/2001/WD-soap12-part0-20011217/'>
SOAP Version 1.2</a> standard. Read the <a href='http://www.w3.org/TR/2001/WD-soap12-part1-20011217/'>
Messaging framework specification</a> for details about messaging with SOAP.
</p>

<h2>SOAP usage</h2>
<p>
eZ soap&trade; can be used for distributed computing. It's platform and language independent.
The basis of SOAP is to exchange XML encoded information, using XML namespaces and schemas.
SOAP can be used in two different ways, either sending a custom XML document or as a RPC
(Remote Procedure Call) protocol. eZ soap&trade; focuses on the latter.
</p>

<h2>SOAP messages</h2>
<p>
Every SOAP message contains the following elements, SOAP Envelope, Body and an optional Header. The SOAP
message below shows a simple \"Hello World!\" request. eZ soap&trade; provides a simple, yet powerful,
interface to SOAP communication.
</p>

<pre class=\"example\">
&lt;?xml version=\"1.0\"?&gt;
&lt;SOAP-ENV:Envelope
         xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
         xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
         xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
         xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\"&gt;
  &lt;SOAP-ENV:Body xmlns:req=\"http://soapinterop.org/\"&gt;
    &lt;req:helloWorld /&gt;
  &lt;/SOAP-ENV:Body&gt;
&lt;/SOAP-ENV:Envelope&gt;
</pre>

<h2>Useful links</h2>
<ul>
<li><a href='http://www.w3.org/'>W3C</a></li>
<li><a href='http://www.w3.org/TR/wsdl'>WSDL spec</></li>
<li><a href='http://xml.apache.org/axis/'>SOAPBuilders Interoperability Lab</a></li>
<li><a href='http://www.w3.org/TR/2001/WD-xmlp-am-20010709/'>XML Protocol Abstract Model</a></li>
<li><a href='http://xml.apache.org/axis/'>Apache AXIS SOAP library (Java)</a></li>
<li><a href='http://easysoap.sourceforge.net/'>EasySoap++ SOAP library (C++)</a></li>
</ul>
";

$dependArray = array();
$dependArray[] = array( "uri" => "ezxml",
                        "name" => "eZ xml" );
$dependArray[] = array( "uri" => "ezutils",
                        "name" => "eZ utils" );

$infoArray["dependencies"] =& $dependArray;

$featureArray = array();

$featureArray[] = array( "level" => 0,
                         "name" => "Introduction" );

$featureArray[] = array( "uri" => "helloworld",
                         "level" => 1,
                         "name" => "Hello World!" );
$featureArray[] = array( "uri" => "calculator",
                         "level" => 1,
                         "name" => "Simple calculator" );

$featureArray[] = array( "level" => 0,
                         "name" => "UML Diagrams" );

$featureArray[] = array( "uri" => "class",
                         "level" => 1,
                         "name" => "Class diagram" );


// $featureArray[] = array( "level" => 0,
//                          "name" => "Datatype examples" );

// $featureArray[] = array( "uri" => "echostring",
//                          "level" => 1,
//                          "name" => "EchoString" );
// $featureArray[] = array( "uri" => "echostringarray",
//                          "level" => 1,
//                          "name" => "EchoStringArray" );
// $featureArray[] = array( "uri" => "echostruct",
//                          "level" => 1,
//                          "name" => "EchoStruct" );
// $featureArray[] = array( "uri" => "echointeger",
//                          "level" => 1,
//                          "name" => "EchoInteger" );
// $featureArray[] = array( "uri" => "echofloat",
//                          "level" => 1,
//                          "name" => "EchoFloat" );

$infoArray["features"] =& $featureArray;

$docArray = array();
$docArray[] = array( "uri" => "eZSOAPClient",
                     "name" => "SOAP client" );
$docArray[] = array( "uri" => "eZSOAPServer",
                     "name" => "SOAP server" );
$docArray[] = array( "uri" => "eZSOAPHeader",
                     "name" => "SOAP header" );
$docArray[] = array( "uri" => "eZSOAPEnvelope",
                     "name" => "SOAP envelope" );
$docArray[] = array( "uri" => "eZSOAPParameter",
                     "name" => "SOAP parameter" );
$docArray[] = array( "uri" => "eZSOAPRequest",
                     "name" => "SOAP request" );
$docArray[] = array( "uri" => "eZSOAPResponse",
                     "name" => "SOAP response" );
$docArray[] = array( "uri" => "eZSOAPFault",
                     "name" => "SOAP fault" );

$infoArray["doc"] =& $docArray;

?>
