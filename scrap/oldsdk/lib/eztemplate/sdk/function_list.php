<?php
//
// Created on: <24-Jan-2003 15:34:16 bf>
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

$Result = array( 'title' => 'Function list' );

?>

<p>
This is a complete list of all the functions you can use in eZ publish
templates. Some functions are native to the eZ template library (and can be used in other applications),
while others are specific for eZ publish.
</p>


<h2>eZ publish template library functions</h2>

<dl>
<dt>section (delimiter, section-exclude, section-include, section-else)</dt>
<dd>Used for looping over arrays and numeric ranges, and conditional control of block and sequences.
Several input parameters and sub functions are available. Requires an end tag.
<a href="/sdk/eztemplate/view/function_section/">More...</a></dd>

<dt>ldelim</dt>
<dd>Outputs the { sign (left curly bracket). Requires no end tag.
<a href="/sdk/eztemplate/view/function_delimit/">More...</a></dd>

<dt>rdelim</dt>
<dd>Outputs the } sign (right curly bracket). Requires no end tag.
<a href="/sdk/eztemplate/view/function_delimit/">More...</a></dd>

<dt>include</dt>
<dd>Includes another template file. Requires no end tag.
<a href="/sdk/eztemplate/view/function_include/">More...</a></dd>

<dt>switch (case)</dt>
<dd>Allows conditional control of output. Requires an end tag.
<a href="/sdk/eztemplate/view/function_switch/">More...</a></dd>

<dt>sequence</dt>
<dd>Creates a sequence that can be iterated over, wrapping around to the beginning when the end is
reached. Requires no end tag. <a href="/sdk/eztemplate/view/function_sequence/">More...</a></dd>

<dt>let, default</dt>
<dd>Assigns one or more variables within its tags. Requires an end tag. "default" assigns the variables
unless they are already assigned, while "let" always assigns them. The assigned variables are freed
(released) at the end tag.</dd>
<dd>E.g.<br/>
{let var1=42 var2='forty-two'}<br/>
Variables: {$var1} ({$var2})<br/>
{/let}<br/>
This will output "Variables: 42 (forty-two)".<br/>
<br/>
{let var1=42 var2='forty-two'}<br/>
{default var1=53}<br/>
Variables: {$var1} ({$var2})<br/>
{/default}<br/>
{/let}<br/>
This will output "Variables: 42 (forty-two)", because the first variable was set before the
default statement.<br/>
<br/>
By passing name as a parameter all variables will be created in the new namespace:<br/>
{let name=NewNameSpace ...}<br/>
<br/>
You can even use let to change the namespace by passing no assignments:<br/>
{let name=First}<br/>
&nbsp;{let name=Second}<br/>
&nbsp;{/let}<br/>
{/let}</dd>

<dt>set</dt>
<dd>Assigns a value to one or more variables. The value must have been created earlier using let
or default. Requires no end tag.</dd>
<dd>E.g.<br/>
{let var=4}<br/>
Before: {$var}<br/>
{set var=42}<br/>
After: {$var}<br/>
{/let}<br/>
This will output "Before: 4 After: 42".</dd>

<dt>set-block</dt>
<dd>Renders all it's children as text and sets it as a template variable. This is useful for allowing
one template to return multiple text portions, for instance an email template could set subject as a
block and return the rest as body. Requires an end tag.</dd>
<dd>The scope parameter can be global, root or relative:<br/>
global - Sets the variable in the defined subspace (if any) in the global namespace<br/>
root - Sets the variable in the defined subspace in the root namespace of the current file
(may be the same as global namespace in some cases)<br/>
relative - Sets the variable in the defined subspace from the current namespace.</dd>
<dd>E.g.<br/>
{set-block name=MyNameSpace scope=global variable=text}<br/>
{$item} - {$item2}<br/>
{/set-block}<br/>
This will render the output into the variable &quot;text&quot;.</dd>

<dt>append-block</dt>
<dd>Similar to set-block but will create an array out of all appends instead. Requires an end tag.</dd>
<dd>E.g.<br/>
{append-block scope=global variable=extra_header_data}<br/>
&nbsp; {* Add some code, for instance a java script *}<br/>
{/append-block}<br/>
<br/>
Then, in a template, you could to this:<br/>
&lt;head&gt;<br/>
{section loop=$#extra_header_data show=is_set($#extra_header_data)}<br/>
{$:item}<br/>
{/section}<br/>
&lt;/head&gt;<br/>
</dd>

<dt>run-once</dt>
<dd>Assures that the content of the block is run only once in a page view, it uses the current filename
and placement to figure this out. This can be useful if you want a text to appear once or a calculation
to be run once for included templates or loops. Requires an end tag.</dd>

<dt>cache-block</dt>
<dd>Caches the content of the block. It takes one or both of these two input parameters: keys, which
determines the cache key, and expiry, which sets the expiry time in seconds.
The cache key can be a simple value like a string or an integer, or an array of simple values.
Requires an end tag.</dd>
<dd>E.g.<br/>
{cache-block keys=$node_id expiry=60}<br/>
{* Do something that needs caching *}<br/>
{/cache-block}</dd>
</dl>


<h2>eZ publish kernel functions</h2>

<dl>
<dt>attribute_edit_gui</dt>
<dd>Edit a content attribute. You must supply the attribute parameter. Any other parameters are passed
on as template variables.<br/>
E.g. {attribute_edit_gui attribute=$myAttribute}</dd>

<dt>attribute_view_gui</dt>
<dd>Show a content attribute. You must supply the attribute parameter. Any other parameters are passed
on as template variables.<br/>
E.g. {attribute_view_gui attribute=$myAttribute}</dd>

<dt>class_attribute_edit_gui</dt>
<dd>Edit an class attribute, this is used when editing classes. You must supply the class_attribute
parameter. Any other parameters are passed on as template variables.<br/>
E.g. {class_attribute_edit_gui class_attribute=$myAttribute}</dd>
<dd></dd>

<dt>content_view_gui</dt>
<dd>Show a content object. You must supply the content_object parameter. The view parameter, specifying
which view mode to use, is optional. Any other parameters are passed on as template variables.<br/>
E.g. {content_view_gui view=text_linked content_object=$myObject}</dd>

<dt>content_version_view_gui</dt>
<dd>Show a content object version. You must supply the content_version parameter. The view parameter,
specifying which view mode to use, is optional. Any other parameters are passed on as template
variables.<br/>
E.g. {content_version_view_gui view=full content_version=$myContentVersion}</dd>

<dt>collaboration_view_gui</dt>
<dd>Show a collaboration item. You must supply the item_class and collaboration_item parameters.
The view parameter, specifying which view mode to use, is optional. Any other parameters are passed
on as template variables.<br/>
E.g. {collaboration_view_gui view=line item_class=$myCollaborationItemClass
collaboration_item=$myCollaborationItem}</dd>

<dt>collaboration_icon</dt>
<dd>Show the icon for a collaboration item. You must supply the collaboration_item parameter.
The view parameter, specifying which view mode to use, is optional. Any other parameters are passed
on as template variables.<br/>
E.g. {collaboration_icon view=small collaboration_item=$myCollaborationItem}</dd>

<dt>collaboration_simple_message_view</dt>
<dd>Show a collaboration message. You must supply the sequence, is_read, item_link and
collaboration_message parameters. The view parameter, specifying which view mode to use, is optional.
Any other parameters are passed on as template variables.<br/>
E.g. {collaboration_simple_message_view
view=element
sequence=$:sequence
is_read=$current_participant.last_read|gt($:item.modified)
item_link=$:item
collaboration_message=$:item.simple_message}</dd>

<dt>collaboration_participation_view</dt>
<dd>Show a collaboration participation. You must supply the collaboration_participant parameter.
The view parameter, specifying which view mode to use, is optional. Any other parameters are passed
on as template variables.<br/>
E.g. {collaboration_participation_view view=text_linked collaboration_participant=$:item}</dd>

<dt>event_edit_gui</dt>
<dd>Edit an event, this is used in the workflow edit form. You must supply the event parameter. Any
other parameters are passed on as template variables.<br/>
E.g. {event_edit_gui event=$myEvent}</dd>

<dt>node_view_gui</dt>
<dd>Show a node. You must supply the content_node parameter. The view parameter, specifying which view
mode to use, is optional. Any other parameters are passed on as template variables.<br/>
E.g. {node_view_gui view=full content_node=$myNode}</dd>

<?php
/*
Notification is not public yet.
<dt>notification_edit_gui</dt>
<dd>Edit a notification, this is used in the notification edit form, for custom notification types. You
must supply the notification_type parameter. Any other parameters are passed on as template variables.<br/>
E.g. {notification_edit_gui notification_type=$myNotificationType}</dd>
*/
?>

<dt>shop_account_view_gui</dt>
<dd>Show a shop account. You must supply the order parameter. The view parameter, specifying
which view mode to use, is optional. Any other parameters are passed on as template variables.<br/>
E.g. {shop_account_view_gui view=html order=$myOrder}</dd>
</dl>
