<?php
//
// Created on: <12-Dec-2002 22:15:53 gl>
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
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//
?>

<h1>Creating forms</h1>
<p>
In this tutorial we will create a feedback form where users can tell what they like and don't like
about your site.
</p>

<h2>Creating a new class</h2>
<p>
The first thing thing we need to do is create a new class for our feedback form.
Afterwards, we'll use this class to create new forms.
</p>

<p>
Login to your admin site and choose 'Classes' under the 'Set up' section. Enter one of the class groups.
These groups are used to categorize your classes. After entering one of the class groups press the 'New'
button to create a new class. You should be presented with the page shown below.
</p>

<img src="/doc/images/form_new_class.png" alt="New class" border="1" />

<p>
Now we need to add the different attributes we want our class to contain. For this example we will add
four attributes: Name, Email receiver, Subject and a Feedback field. We will fill in the first two fields
when we create a new form. The last two fields are meant for the user to fill in. Add the attributes as
shown in the picture below.
</p>

<img src="/doc/images/form_new_class_attributes.png" alt="Attributes" border="1" />

<p>
As you can see on the above picture attribute 3 and 4 have 'Information Collector' checked.
This enables the data in the attribute to be stored or sent.
</p>

<p>
If you only want one form on your site, or you want all the input from different forms mailed
to the same address you do not need the 'email receiver' attribute. It is possible to set the email receiver
in settings/site.ini, but we want the ability to set different addresses for different forms.
</p>

<p>
When you are done adding the attributes click on 'Store'.
</p>

<h2>Creating a new form</h2>
<p>
It is now time to use our class to create a form. Go to a location on the site you want your
form, select 'Submission form' from the drop down menu and click 'New'. Fill in the Name and
Email receiver field. The two last fields are meant for the user to fill in so leave those blank.
If the email address you set in the Email receiver field is invalid, the default address
from settings/site.ini will be used. When you are done click on 'Send for publishing'.
</p>

<p>
Before we proceed with customizing the form check that it works. Click on the form you created, fill in some
test data, click on 'send' and confirm that the data was sent to the email address you specified.
</p>

<h2>Creating a template</h2>
<p>
We want to customize the look and layout of our form. To accomplish this we need to create our own template
which will be used whenever an instance (a form created with the class we made in the beginning of the
tutorial) of our form class is viewed. The first information we need is the Class ID for our form class.
Go to 'Classes' under 'Set up' and choose the class group you created your form class in. You should now be
viewing a list of classes. The numbers in the first column are the class IDs. Get the ID for the form class
we created.
</p>

<p>
With the ID of the class we are going to create a new file called 'full_class_ID.tpl'. Replace 'ID' with the
ID of the form class. This file will be the full view for the class with the given ID. Place the file in
'design/standard/override/templates/node/view'. Shown below is an example of a template.
</p>

<pre class="example">
{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

&lt;h1&gt;{$node.data_map.name.content}&lt;/h1&gt;
&lt;p&gt;
We have just started this page and would like you to send your comments, ideas, 
improvement suggestions and critique to help us further 
develop this page. This page is made for you, 
the users, so here is your chance to contribute and make it the way you want it.
&lt;/p&gt;

&lt;form method="post" action={"content/action"|ezurl}&gt;

&lt;table width="100%" cellspacing="10" cellpadding="0" border="0"&gt;
&lt;tr&gt;
    &lt;td&gt;
    &lt;b&gt;Subject&lt;/b&gt;
    &lt;/td&gt;
&lt;/tr&gt;
&lt;tr&gt;
    &lt;td&gt;
    {attribute_view_gui attribute=$node.data_map.subject}
    &lt;/td&gt;
&lt;/tr&gt;
&lt;/table&gt;

&lt;table width="100%" cellspacing="10" cellpadding="0" border="0"&gt;
&lt;tr&gt;
    &lt;td&gt;
    &lt;b&gt;Feedback&lt;/b&gt;
    &lt;/td&gt;
&lt;/tr&gt;

&lt;tr&gt;
    &lt;td&gt;
    {attribute_view_gui attribute=$node.data_map.feedback}
    &lt;/td&gt;
&lt;/tr&gt;
&lt;/table&gt;


{section name=ContentAction loop=$content_object.content_action_list show=$content_object.content_action_list}
    &lt;div class="block"&gt;
        &lt;input type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name}" /&gt;
    &lt;/div&gt;
{/section}

&lt;input type="hidden" name="ContentNodeID" value="{$node.node_id}" /&gt;
&lt;input type="hidden" name="ContentObjectID" value="{$content_object.id}" /&gt;
&lt;input type="hidden" name="ViewMode" value="full" /&gt;

&lt;/form&gt;

{/default}
</pre>

For more information on templates read the <a href="/sdk/eztemplate">template tutorial</a>.

<p>
The final template:
</p>
<img src="/doc/images/form_template.png" alt="Template" border="1" />

<p>
The final thing that needs to be done is change the collectedinfomail.tpl located in
'design/standard/template/content'.
The content of this template is what will be sent by mail.
</p>

<pre class="example">
{set-block scope=root variable=subject}{"Collected information from:"|i18n("design/standard/content/edit")}
{$collection.object.name} {/set-block}
{set-block scope=root variable=email_receiver}{$object.data_map.email_receiver.content}{/set-block}

{"The following information was collected:"|i18n("design/standard/content/edit")}

{section name=Attribute loop=$collection.attributes}
{$Attribute:item.contentclass_attribute_name}:
{$Attribute:item.data_text}
{/section}
</pre>

<p>
What we did here was to make the email_receiver variable correspond to whatever email address we enter
in the email_receiver attribute.
</p>
