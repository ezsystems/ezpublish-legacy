<?php
//
// Definition of Wrappingworkflow class
//
// Created on: <12-Dec-2002 20:00:29 sp>
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
// The "GNU General Public Licence" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licensing isn't clear to
// you.
//

/*! \file wrappingworkflow.php
*/
?>
<h1>Wrapping workflow</h1>

<p>
Allows you to ask the user, before he confirms the order, if he wants to pack his goods into Christmas paper.
</p>
To create this workflow you need to do the following steps
<ul>
<li>Create new event_type</li>
<li>Create workflow with that event</li>
<li>Connect that workflow to the shop/confirm operation</li>
</ul>
<p>
To know how to create event type read the tutorial <a href="/sdk/tutorials/view/workflows">Custom workflows</a>,
go to the part called "Creating workflows". Create a skeleton of a workflow event.
</p>

<br>
File <i>ezwrappingtype.php</i>
<pre class="example">
define( "EZ_WORKFLOW_TYPE_WRAPPING_ID", "ezwrapping" );

class eZWrappingType extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function eZWrappingType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_WRAPPING_ID, "Wrapping" );
    }
    function execute( &$process, &$event )
    {
        return EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT;
    }

}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_WRAPPING_ID, "ezwrappingtype" );
</pre>

<br>
<p>
We will modify the "execute" function to improve the functionality of the workflow.
</p>
<pre class="example">
    function execute( &$process, &$event )
    {
1
2        $http =& eZHTTPTool::instance();
3
4        if ( $http->hasPostVariable( "Next" ) )
5        {
6            if ( $http->hasPostVariable( "answer" ) )
7            {
8                $answer = $http->postVariable( "answer" );
9                eZDebug::writeDebug( 'got answer' );
10               if( $answer == 'yes' )
11               {
12                   $parameters = $process->attribute( 'parameter_list' );
13                   eZDebug::writeDebug( 'got yes' );
14                   $orderID = $parameters['order_id'];
15
16                   $orderItem = new eZOrderItem( array( 'order_id' => $orderID,
17                                                        'description' => 'Wrapping',
18                                                        'price' => '100',
19                                                        'vat_is_included' => true,
20                                                        'vat_type_id' => 1 )
21                                                 );
22                   $orderItem->store();
23
24               }else
25               {
26                   eZDebug::writeDebug( 'got no' );
27
28               }
29               return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
30           }
31       }
32       $requestUri = eZSys::requestUri();
33       $process->Template = array( 'templateName' => 'design:workflow/eventtype/result/' . 'event_ezwrapping' . '.tpl',
34                                   'templateVars' => array( 'request_uri' => $requestUri )
35                                    );
36       return EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT;
     }
</pre>

<h2>Comments</h2>
<p>
line 2 - get eZHTTPTool instance to fetch postvariable from.<br/>
Conditional operators (4-6) which check if the needed http variables exist.
This is needed to check whether this is the answer from the user.<br/>
If we get an answer from user and if answer is yes we need to create an order item with wrapping.<br/>
OrderID is a workflow parameter (defined by operation) so you can fetch it from parameters list (12,14)<br/>
If the answer is "no" we "accept" event.<br/>
When the event runs first we need to say to the system that we need to halt an operation
and show an additional page to the user (32-36)<br/>
</p>


<h2>Create result template</h2>
<pre class="example">

<?php  print( htmlspecialchars ( '<form action={$return_uri|ezurl} method="post" >

<div class="maincontentheader">
<h1>{"Hello"|i18n(\'workflow/eventtype/result/event_hellouser\')} {$user_name}</h1>
</div>
<div class="buttonblock">
<input type="submit" name="Next" value="next" />
</div>

</form>
' ) ); ?>

</pre>

<br>
<p>
The page will look like this:
</p>
<img src="/doc/images/preconfirmorder.png" alt="Confirm" />

<h2>Creating and connecting the workflow </h2>
<p>
To know how to connect the workflow to the operation look through the <a href="/sdk/tutorials/view/publishworkflow">Approval workflow</a>
tutorial.
When connecting to the operation (triggers) select the name of the created workflow in the front of "shop-confirm-before".
</p>
<img src="/doc/images/triggers1.png" alt="Triggers" />

<p>
So now you can use the workflow.
</p>
