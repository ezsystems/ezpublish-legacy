<?php
//
// Created on: <28-Oct-2002 12:43:53 bf>
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

<h1>Custom Content</h1>

<p>
One of the most powerful features of eZ publish 3 is that you can define your own content classes.
A content class defines the structure of the content you want to publish on your site. Examples of
different content classes are; article, folder, image, product and link.
</p>

<h2>Creating classes</h2>

<p>
When you start to build your site you need to find the suitable structure for your content. eZ publish
comes with a set of standard content classes which you can use, but you will most likely need to create
your own custom classes for your specific needs.
</p>

<p>
Lets say we need to publish information about cars. We then need to create a class called car.
The class needs the attributes; Name, Manufacturer, Model, Year, Description and Image.
</p>

<p>
You will find the administration interface for classes under "Set up" in the eZ publish administration
interface. Then click on the appropriate group, e.g. content, where you want to place your class. The group
is just a method of categorizing your content classes for simpler administration of larger sites. Then you
click on the "New class" button. You will then be presented to the class edit window as shown below:
</p>
<img src="/doc/images/class_edit.png" alt="Class edit" border="1" />

<p>
A class has three built-in attributes, in addition to the attributes you can add, edit and delete to
customize your class. The built-in attributes are object name, identifier and name. The name is the human
readable name for the class. The identifier is a string representation of the name, consisting only of the
characters 0-9 and a-z. It is used in templates, imports/exports etc. as an identifier for the class. The
object name defines how an object of this class will be named. All objects get a one-line name which can be
built up from several attributes, and contain arbitrary text. In this example it will be the same as the
first attribute - name. (For a User class, for instance, it would be natural to let the object name consist
of the attributes First Name and Last Name.)
</p>

<h2>Attributes</h2>

<p>
We then need to create the attributes for the car class. To add a new attribute you select the datatype
for the attribute in the dropdown in the lower right corner of the class edit page. Every attribute has a
name and an identifier. You only need to fill in the name, the identifier will automatically be generated.
Attributes are by default searchable, this means that the data entered here will be indexed in the search
engine. You can also select if the attribute is required or not. Additionally some datatypes have additional
settings, like default value and max string length for text line.
</p>

<p>
For our car class we will create datatypes of "Text line", "XML text" and image.
</p>

<ul>
<li>Name: Text line</li>
<li>Manufacturer: Text line</li>
<li>Model: Text line</li>
<li>Year: Text line</li>
<li>Description: XML text</li>
<li>Image: Image</li>
</ul>

<p>
Now that we've created all our attributes we can just click on the store button and our class is ready to use.
</p>

<p>
<b>Note: To make objects of the new class available to anonymous users you must edit the Anonymous role,
as this role has specified access limitation by class by default.</b>
</p>

<h2>Creating content</h2>

<p>
To take our newly created class for a spin we click on the content menu in the administration interface. You then
browse to a suitable placement and select "New car". This will bring us to the object edit window shown below. Here
you fill in all the values you want for you car and click "Send for publishing".
</p>

<img src="/doc/images/object_edit.png" alt="Object edit" border="1" />

<p>
Now we've already published our own custom content without changing any code just clicking in the web interface.
When we view our newly created it will display with the standard templates as shown below:
</p>

<img src="/doc/images/object_view_plain.jpg" alt="Object view standard" border="1" />

<h2>Custom template</h2>

<p>
<b>Note: When developing templates you should disable the view cache. When this is
enabled, the template engine does not check the modification date of the templates,
thus you will not see any changes. Edit settings/site.ini and set ViewCaching=disabled
in [ContentSettings].</b>
</p>

<p>
The standard template is not how we want our car to be displayed at our webpage. Now we need to get our hands dirty, and create
a display template. To create a specific we need to figure out the ID of our class. The ID is a unique number which
represents our class. This can be found in the class list under "Set up -> classes". In my case this was 26.
</p>

<p>
To make eZ publish understand that you want to have a custom template for your newly created class you must
create an override template for this. You can do this by creating a file in the eZ publish root. The eZ publish
root is the main folder of your eZ publish installation. There you will see the sub folders, lib, kernel, design etc.
The file should be created under the design folder:
</p>
<pre class="example">
Unix:
design/standard/override/templates/node/view/full_class_26.tpl
Windows:
design\standard\override\templates\node\view\full_class_26.tpl
</pre>

<p>
<i>Note:</i> If you don't have the subfolders override/templates/node/view, these must be created under your design folder
( which is design/standard/ as default ).
</p>

<p>
This file is a plain text file which you can edit with your favourite text editor, e.g. vi, emacs and notepad.
It must be a plain text editor with no formatting, so you cannot use word processors like word or open office.
</p>

<p>
Replace standard with your current design folder and 26 with the ID of your class.
</p>

<p>
This .tpl file is a eZ publish 3 template file and is a combination of XHTML and
eZ template syntax. The example below shows a simple customisation where we place the
attributes in a table and have a large image under the page title.
</p>

<pre class="example">
&lt;h1&gt;{$node.name}&lt;/h1&gt;

{attribute_view_gui attribute=$node.object.data_map.image}

&lt;table&gt;
&lt;tr&gt;
    &lt;td&gt;
    Manufacturer:
    &lt;/td&gt;
    &lt;td&gt;
    {attribute_view_gui attribute=$node.object.data_map.manufacturer}
    &lt;/td&gt;
&lt;/tr&gt;
&lt;tr&gt;
    &lt;td&gt;
    Model:
    &lt;/td&gt;
    &lt;td&gt;
    {attribute_view_gui attribute=$node.object.data_map.model}
    &lt;/td&gt;
&lt;/tr&gt;
&lt;tr&gt;
    &lt;td&gt;
    Year:
    &lt;/td&gt;
    &lt;td&gt;
    {attribute_view_gui attribute=$node.object.data_map.year}
    &lt;/td&gt;
&lt;/tr&gt;
&lt;/table&gt;

{attribute_view_gui attribute=$node.object.data_map.description}
</pre>

<p>
In the example above you see that we use the template variable {$node} whenever we want
to fetch some data from our object. The node represents the specific placement of our object.
E.g. if it's published under /frontpage/news/ that will be a node. We can fetch the
object name using {$node.name}. This is the automatically generated name for the object which
we configured to be equal to the attribute: name. The function {attribute_view_gui } is a template
function used for fetching the content of a specific attribute. You use the attribute identifier
to specify the attribute.
</p>

<p>
The image below shows our car using the custom template.
</p>

<img src="/doc/images/object_view_custom.jpg" alt="Object view custom" border="1" />
