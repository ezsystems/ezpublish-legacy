<?php
//
// Created on: <19-Jun-2002 18:52:00 bf>
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

<p>

</p>
<h2>Worflows</h2>

<h4> Class diagram </h4>
<img src="/doc/design/uml/workflows.png" /><br/>
<h4> State diagram </h4>
<img src="/doc/design/uml/workflows_state.png" /><br/>

<h4> Database diagram </h4>
<img src="/doc/images/workflows_db.png" /><br/>


Workflows are triggerd by runTrigger function.
The code may look like: (like I imagine in 'content/edit.php')
<pre class="example">
if ( $Module->isCurrentAction( 'publish' ) )
{
    $workflowParameters = array( 'contentobject' => $object,
                                 'session' => $currentSession,
                                 'user' => $currentUser
                                 );
    if ( eZTrigger::runTrigger( 'content', 'publish', 'before' , $workflowParameters) == 'done' )
    {
        $object->publish();
        eZTrigger::runTrigger( 'content', 'publish', 'after', $workflowParameters);
    }
}
</pre>

Here is how the runTrigger function should work:<br/>

 1. try to look is there worflow connected to that function <br/>
 2. look if there is already  started workflow process for the same event.<br/>
 3. If so start the next event<br/>
 4. if not  create new workflow process and start it<br/>

 If to look to the publish process. Multiplesor workflow looks to parameters and decide what workflow to run next if need it. And shows user a page saying that object is sended for aproval or smth. like that.
<h4>Example of Publish workflow </h4>
<img src="/doc/images/publish.png" />

<h4>Example of Checkout workflow </h4>
<img src="/doc/images/checkout.png" />
