<?php
//
// Created on: <27-Jan-2003 15:28:14 gl>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

?>

<h1>Common template issues</h1>

<p>
Here you will find examples of some features you are likely to need when developing your own templates.
Consult the <a href="/sdk/eztemplate/">eZ template documentation</a> for the complete documentation of
the template engine.
</p>

<p>
In this example we will create an override template for the 'full' view mode of the Folder class. To test
it, create a file called 'full_class_1.tpl' in the directory 'design/admin/override/templates/node/view'.
Login to the admin interface, create a test folder, and fill it with some articles.
</p>

<p>
<b>Note: When developing templates you should disable the view cache. When this is
enabled, the template engine does not check the modification date of the templates,
thus you will not see any changes. Edit settings/site.ini and set ViewCaching=disabled
in [ContentSettings].</b>
</p>

<br>
<h1>Just a list</h1>

<p>
This example simply lists out the names of the articles who are children of the folder we are viewing.
The fetch statement fetches a list of children whose parent node id equals our node id, and assigns it to
the template variable $children. The name of our node is printed as a header, and then we use a loop to
print the name of each child. Looping is done with the
<a href="/sdk/eztemplate/view/function_section/">section function</a>. Note how we assign a namespace for
the loop, called 'Child' in this example.
</p>

<pre class="example">
{* set children variable *}
{let children=fetch('content',list,hash(parent_node_id,$node.node_id))}

&lt;h1&gt;{$node.name}&lt;/h1&gt;

{* loop children and print name *}
{section name=Child loop=$children}
{$Child:item.name}&lt;br&gt;
{/section}

{/let}
</pre>

<p>
This will output something like this:
</p>

<p><img src="/doc/images/templateissues01.jpg"></p>



<br>
<h1>A list with links to children</h1>

<p>
In the second example we will output links to the child objects, not just their names. We use the function
'node_view_gui' to print the child objects, and select the 'line' view mode.
</p>

<pre class="example">
{* set children variable *}
{let children=fetch('content',list,hash(parent_node_id,$node.node_id))}

&lt;h1&gt;{$node.name}&lt;/h1&gt;

{* loop children and show line view *}
{section name=Child loop=$children}
{node_view_gui view=line content_node=$Child:item}&lt;br&gt;
{/section}

{/let}
</pre>

<p>
This will output something like this:
</p>

<p><img src="/doc/images/templateissues02.jpg"></p>



<br>
<h1>A more complex list</h1>

<p>
Since this is a news page example, we will now try some functionality that might be used on a news site.
We show the two most recent news titles on the top, with the ingress, and a link to the full article.
For the remaing articles we simply show the name, linked to the full article, as in the previous example.
To achieve this we use the 'max' and 'offset' input parameters. In the first loop we set 'max=2', meaning
that up to two items will be show in this loop. In the second loop we set 'offset=2', meaning that this
loop will start two items from the beginning of the list, in other words with the third item.
</p>

<p>
We also use the 'delimiter' function to show the remaing articles in a two-column list. The delimiter
function is used when we want to do something between each item in a loop. In this case we output the
closing and starting &lt;tr&gt;-tags. We set 'modulo=2' for the delimiter, meaning that the delimiter will
only be used for every second list item. Thus, this part of the table will contain two cells per row.
</p>

<pre class="example">
{* set children variable *}
{let children=fetch('content',list,hash(parent_node_id,$node.node_id))}

&lt;h1&gt;{$node.name}&lt;/h1&gt;

&lt;table border='0'&gt;
{section name=Child loop=$children max=2}
  &lt;tr&gt;
    &lt;td colspan="2"&gt;
      &lt;h2&gt;{$Child:item.name}&lt;/h2&gt;
      &lt;p&gt;{$Child:item.data_map.intro.data_text}&lt;/p&gt;
      &lt;p&gt;&lt;a href={concat("/content/view/full/",$Child:item.node_id)|ezurl}&gt;Read more...&lt;/a&gt;&lt;/p&gt;
    &lt;/td&gt;
  &lt;/tr&gt;
{/section}

  &lt;tr&gt;
    &lt;td colspan="2"&gt;
      &lt;h2&gt;More news:&lt;/h2&gt;
    &lt;/td&gt;
  &lt;/tr&gt;
  &lt;tr&gt;
{section name=Child loop=$children offset=2}
    &lt;td&gt;
    {node_view_gui view=line content_node=$Child:item}
    &lt;/td&gt;
  {delimiter modulo=2}
  &lt;/tr&gt;
  &lt;tr&gt;
  {/delimiter}
{/section}
  &lt;/tr&gt;
&lt;/table&gt;

{/let}
</pre>

<p>
This will output something like this:
</p>

<p><img src="/doc/images/templateissues03.jpg"></p>



<br>
<h1>A even more complex list - with colours</h1>

<p>
In the final example we set alternating colours for the lines in the list, using the 'sequence' function.
Sequence is used two loop trough a list, and wrap around when the end is reached. This is very useful for
alternating colours. We set two parameters, the namespace and the array of items to loop. (In this case the
array contains four items, allthough we only use two colours. This is because the section loops over table
cells, and there are two cells per line.) We get the current sequence item using the namespace and 'item',
and we advance to the next item by calling sequence again with the same namespace.
</p>

<pre class="example">
{* set children variable *}
{let children=fetch('content',list,hash(parent_node_id,$node.node_id))}

&lt;h1&gt;{$node.name}&lt;/h1&gt;

&lt;table border='0'&gt;
{section name=Child loop=$children max=2}
  &lt;tr&gt;
    &lt;td colspan="2"&gt;
      &lt;h2&gt;{$Child:item.name}&lt;/h2&gt;
      &lt;p&gt;{$Child:item.data_map.intro.data_text}&lt;/p&gt;
      &lt;p&gt;&lt;a href={concat("/content/view/full/",$Child:item.node_id)|ezurl}&gt;Read more...&lt;/a&gt;&lt;/p&gt;
    &lt;/td&gt;
  &lt;/tr&gt;
{/section}

  &lt;tr&gt;
    &lt;td colspan="2"&gt;
      &lt;h2&gt;More news:&lt;/h2&gt;
    &lt;/td&gt;
  &lt;/tr&gt;
  &lt;tr&gt;
{sequence name=Seq loop=array("#A0FFA0","#A0FFA0","#D0FFD0","#D0FFD0")}
{section name=Child loop=$children offset=2}
    &lt;td bgcolor="{$Seq:item}"&gt;
    {node_view_gui view=line content_node=$Child:item}
    &lt;/td&gt;
{* Get next item in the sequence *}
{sequence name=Seq}
  {delimiter modulo=2}
  &lt;/tr&gt;
  &lt;tr&gt;
  {/delimiter}
{/section}
  &lt;/tr&gt;
&lt;/table&gt;

{/let}
</pre>

<p>
This will output something like this:
</p>

<p><img src="/doc/images/templateissues04.jpg"></p>
