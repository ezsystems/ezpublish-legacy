<?php
//
// Created on: <01-Dec-2002 12:43:53 sp>
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

?>

<h1>Custom workflows</h1>

<p>
One of the most powerful features of eZ publish 3 is that you can create custom workflow.
Workflow is a mechanism that makes it possible to customize or add new features to operations in eZ Publish.
For example: add some additional actions to publish routing or some additional steps to view objects.
</p>

<p>
Workflows should be connected to defined eZ Publish operations ( there are only two defined operations in content module currently: "Read"  and "Publish" ) with triggers.
For each defined operation eZ Publish kernel tries to execute two triggers. One - before the actual operation body is executed and another after that. Trigger is just a row in database in table eztrigger.
Table definition:
<pre class="example">

CREATE TABLE eztrigger (
  id int(11) NOT NULL auto_increment,
  name varchar(255),
  module_name varchar(200) NOT NULL default '',
  function_name varchar(200) NOT NULL default '',
  workflow_id int(11) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY eztrigger_def_id (module_name,function_name,name)
) TYPE=MyISAM;

</pre>
Field "name" is the name of trigger, such as "pre_publish", "pre_read". Fields module_name and function_name are the names of module and function for which system will start executing workflow.
</p>

<h2> "Operations"   understanding </h2>
<p>
Operation is a sequence of functions and triggers which should be executed to make full action like read or publish. For example simple "read" operation (bellow) is just a sequence of "pre_read" trigger  and "fetch-object" method. 

<pre class="example">

$OperationList['read'] = array( 'name' => 'read',
                                'default_call_method' => array( 'include_file' => 'kernel/content/ezcontentoperationcollection.php',
                                                                'class' => 'eZContentOperationCollection' ),
                                'parameters' => array( array( 'name' => 'node_id',
                                                              'required' => true ),
                                                       array( 'name' => 'user_id',
                                                              'required' => true ),
                                                       array( 'name' => 'language_code',
                                                              'default' => '',
                                                              'required' => false ) ),
                                'keys' => array( 'node_id',
                                                 'user_id' ),

                                'body' => array( array( 'type' => 'trigger',
                                                        'name' => 'pre_read',
                                                        'keys' => array( 'node_id',
                                                                         'user_id'
                                                                         ) ),



                                                 array( 'type' => 'method',
                                                        'name' => 'fetch-object',
                                                        'frequency' => 'once',
                                                        'method' => 'readObject',
                                                        ) ) );

</pre>
</p>

<ul>
<li>name - the name of operation.</li>
<li>default_call_method - array which shows in which file and class methods used in operation are defined </li>
<li>parameters - parameters that are passed to each method and trigger in operation when they are executed</li>
<li>keys - part of parameters that are used to edentify stored interupted operation (operation memento)</li>
<li>body - array of arrays that describe methods and triggers of the operation</li>
</ul>
<br>
Types of body elements:
<br>
Trigger:
<br>
<ul>
<li>type - trigger, shows that current body element is trigger </li>
<li>name - name of trigger used to fetch trigger from database. To fetch trigger system will use next conditions - (module name, operation name, trigger name) </li>
<li>key - part of the parameters used to identify running workflow process  </li>
</ul>
Method:
<ul>
<li>type - method </li>
<li>name - fetch-object, name of method used in internals of operation </li>
<li>frequency - once, it means that method will be run only once</li>
<li>method - method of class described with "default_call_method" "class" parameter of operation. That function is the code which will be executed </li>
</ul>


<h2>Creating workflows</h2>

<p>
Workflow consists of workflow-events, executed one after another. It means that before you create workflow you need to create workflow event type or you can use
events that already exist.
</p>
<h3>Creating new workflow event</h3>
<p>
For example you want o create workflow event which will show "hello user" to users when workflow runs. The name of that event for example will be <i>hellouser</i>
To create new workflow event you need to do some steps:
<ol>
<li>modify site.ini settings to add the new worklow event type. You should add "event_hellouser" to the AvailableEventTypes parameter</li>
<li>create directory kernel/classes/workflowtypes/event/hellouser/</li>
<li>create file in that directory called hellousertype.php</li>
</ol>
</p>
File <i>hellousertype.php</i>
<pre class="example">
define( "EZ_WORKFLOW_TYPE_HELLO_USER_ID", "hellouser" );

class helloUserType extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function helloUserType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_HELLO_USER_ID, "Hello User" );
    }

    function execute( &$process, &$event )
    {
        return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_HELLO_USER_ID, "hellousertype" );

</pre>

<p>
That is the simplest workflow event type. To create workflow event type we need to create a class that extends eZWorkflowEventType and  register it in system with eZWorkflowEventType::registerType(). That classs should have at least one method except constructor. Method <i>execute</i> is called when workflow event is executed. Behaviour of running workflow depends of return value of execute function.
<br/>

<table border="1">
<CAPTION align=left> Possible values</CAPTION>
<th>Value</th><th>Description</th>
<tr>
<td>
EZ_WORKFLOW_TYPE_STATUS_ACCEPTED 
</td>
<td>
Workflow accepts that event, and run next event in workflow
</td>
</tr>

<tr>
<td>
EZ_WORKFLOW_TYPE_STATUS_REJECTED<br/>
EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_CANCELLED
</td>
<td>
Both of them means the same for now. All workflow is canceled.
</td>
</tr>


<tr>
<td>
EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON<br/>
EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT
</td>
<td>
That status means that executing of workflow is defered to cron daemon. Diference between the first and the second is that next time workflow is executed it start from next event in the first case and from the same event in the second case.
</td>
</tr>

<tr>
<td>
EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE<br/>
EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT
</td>
<td>
Workflow engine should interupt executing workflow and show page to the user. In case of that status workflow event should set up some internal variables. (Will be explaned bellow). Deferences between them same as in previous case

</td>
</tr>

<tr>
<td>
EZ_WORKFLOW_TYPE_STATUS_REDIRECT<br/>
EZ_WORKFLOW_TYPE_STATUS_REDIRECT_REPEAT

</td>
<td>
Workflow engine should interupt executing workflow and redirect user to the specified page. In case of that status workflow event should set up some internal variables. (Will be explaned bellow). Deferences between them same as in previous cases
</td>
</tr>

<tr>
<td>
EZ_WORKFLOW_TYPE_STATUS_NONE
</td>
<td>
Undefined status. For now the same as EZ_WORKFLOW_TYPE_STATUS_REJECTED
</td>
</tr>
</table>
</p>

<p>
So to show a page to the user you need to use status EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE and set up some internal variables.
<pre class="example">

    function execute( &$process, &$event )
    {

        $user =& eZUser::currentUser();
        $userName =& $user->attribute( 'login' );
        $localhostAddr = eZSys::hostname();
        $requestUri = eZSys::serverVariable( 'REQUEST_URI' );


        $process->Template = array( 'templateName' => 'design:workflow/eventtype/result/' . 'event_hellouser' . '.tpl',
                                     'templateVars' => array( 'return_uri' => $requestUri,
                                                              'user_name' => $userName ) );
        return EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE;
    }

</pre>

And to create result template

<pre class="example">

<?php  print( htmlspecialchars ( '<form action={$return_uri|ezurl} method="post" >

<div class="maincontentheader">
<h1>{"Hello"|i18n(\'workflow/eventtype/result/event_ezcheckout\')} {$user_name}</h1>
</div>
<div class="buttonblock">
<input type="submit" name="Next" value="next" />
</div>

</form>
' ) ); ?>

</pre>
</p>

<p>
Now workflow event is ready and we should create workflow. We should go to the workflows, go into prefered group (or create new one) <br/>

<img src="/doc/images/workflow_grouplist.png" alt="Class edit" />
<br/>
<br/>
and click there button "New workflow" button.
<br/>
<img src="/doc/images/workflowlist.png" alt="Class edit" />
<br/>
<br/>
You need to specify workflow name. After that you should add "Event/Hello User" event to the workflow by selcting it from dropdown and clicking "New Event" button.
<br/>
<img src="/doc/images/workflowedit.png" alt="Class edit" />
<br/>
<br/>

After that you should push apply and store buttons

</p>
<p>
Now workflow is ready and you need to create a trigger. For now you should do it manualy.
Connect to the database from command line tool and insert next record to the eztrigger table.
<pre class="example">
insert into eztrigger( module_name,function_name,name,workflow_id ) values ( 'content', 'read', 'pre_read', <i>new_workflow_id</i> );
</pre>
Where workflow id is internal id of  newly created workflow. You can know from edit workflow url.
</p>
<br/>

So now workflow is ready and connected to the operation.
