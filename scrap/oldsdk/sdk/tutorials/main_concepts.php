<?php
//
// Created on: <10-Dec-2002 15:20:51 bf>
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

$DocResult = array();
$DocResult["title"] = "Main concepts";

?>
<h1>Main concepts</h1>

<p>
eZ publish 3 is the next generation of the popular open source content management system eZ publish.
This version of eZ publish, though, is not only a totally re-engineered version with a lot of new,
exciting functionality. eZ publish 3 has evolved into a development platform with several general
PHP libraries for the rapid building of all sorts of PHP applications. Best of all: eZ publish
is available for free under the GPL license. The latest version of eZ publish can be downloaded
from ez.no at all times.
</p>

<p>
The eZ publish 3 development framework is a feature rich package. The libraries handles common
operations including database abstraction, internationalization, template
engine for content and design separation, XML parser and SOAP library. On top of the libraries
you get the ready to use publishing functionality available in the eZ publish kernel. You have
user defined content, integrated role based permission system, multilanguage features including
UNICODE support, versioning of content, workflows, integrated search engine and an a full feature
e-commerce engine to mention some features.
</p>

<p>
This article will show you how quickly you can set up eZ publish and how you can create custom
websites with dynamic content. We will have a look at one content class and see how this is
defined and how you can use it.
</p>

<h2>Installing</h2>

<p>
You have basically four ways of installing eZ publish 3. You can use one of the supplied installer
packages, which will install Apache, PHP, MySQL and set up everything needed to get eZ publish up
and running. Another option is to use the publish sources and follow the setup wizard which
will guide you through the steps of configuring your system for eZ publish. If you are a
litle more experienced you can disable the setup wizard and configure eZ publish from scratch
following the installation manual. If you don't want to install eZ publish yourself you can
always hire eZ systems to install the software for you or buy a ready hosted eZ publish solution.
We're not going to go into details about the installation here, you will find all the information
you need in the installation manual.
</p>

<h2>Setting up</h2>

<p>
Once you've gotten eZ publish up and running on a server you need to configure the system.
You can have several different sites running on the same eZ publish installation, to distinguish
between these sites you need to set up something called site access. The site access defines
how eZ publish will recognize which site you're accessing. eZ publish will then know which database
to use, which design to show etc. There are four ways of letting eZ publish recognize a site access,
by URI, host name, port or file name. The most common way is to use the host name.
</p>

<p>
In our example we will name our site www.mybookstore.com and we will use the admin.mybookstore.com
as the administration interface. To make eZ publish fetch site access from host names you need to
configure a DNS server and point the domains to your web server. When your DNS is up and running
and the names resolve to your web server and your eZ publish installation you need to make eZ publish
recognize the names and use the correct configuration. To do this you open the configuration file
found in settings/site.ini in the root of your eZ publish installation. In site.ini browse down to
the section [SiteAccessSettings] and alter the configuration like shown below. Only the settings
you need to change is shown below.
</p>

<pre class="example">
[SiteAccessSettings]
MatchOrder=host
HostMatchRegexp=^(.+)\.mybookstore\.com$
HostMatchSubtextPost=\.mybookstore\.com
</pre>

<p>
We've now told eZ publish to look for the first characters in our domain name. This means that
you will get the matches www for the website and admin for the administration site. Now that
eZ publish knows how to distinguish between the two domains we need to create a configuration
file for each site. This is done by creating two folders under settings/siteaccess/ which
corresponds to our matches ( www and admin ). In both these folder you need to create a file
called site.ini.append. This is the configuration file which will be used to override any of
the standard settings in eZ publish. We will keep our example simple and have just made a few
settings distinguish bewteen the two sites. You can see the two configuration files below.
</p>

<pre class="example">
Contents of configuration for admin site settings/siteaccess/admin/site.ini.append
[SiteSettings]
LoginPage=custom

[SiteAccessSettings]
RequireUserLogin=true
</pre>

<p>
The configuration LoginPage=custom means that eZ publish will use a separate template for
the login page of the administration site. RequireUserLogin=true tells eZ publish not to
let anyone inside eZ publish unless they're logged into the system.
</p>

<pre class="example">
Contents of configuration for website settings/siteaccess/www/site.ini.append

[DesignSettings]
SiteDesign=mybookstore

[SiteAccessSettings]
RequireUserLogin=false
</pre>

<p>
The settings above applies to the website. SiteDesign=mybookstore means that eZ publish
will prefer to use the design for the site found in design/mybookstore, RequireUserLogin=false
is set so that users do not have to log into eZ publish to browse the website.
</p>

<h2>Applying design</h2>

<p>
<b>Note: When developing templates you should disable the view cache. When this is
enabled, the template engine does not check the modification date of the templates,
thus you will not see any changes. Edit settings/site.ini and set ViewCaching=disabled
in [ContentSettings].</b>
</p>

<p>
The main template in eZ publish is called pagelayout.tpl and is located in the templates/
folder or the current design folder. In our example this is design/mybookstore/templates/pagelayout.tpl.
This template defines the layout of our website. Here you normally decide where your logo and menu goes.
We will make our pagelayout template simple with a heading, a menu with two links and the
content itself. Below you will see the complete code for our template.
</p>

<pre class="example">
{*?template charset=latin1?*}
&lt;!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"&gt;
&lt;html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"&gt;
&lt;head&gt;
    &lt;title&gt;Price Update System&lt;/title&gt;
    &lt;link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} /&gt;
&lt;/head&gt;
&lt;body&gt;
&lt;h1&gt;My Bookstore&lt;/h1&gt;
&lt;table width="100%"&gt;
&lt;tr&gt;
    &lt;td valign="top"&gt;
    &lt;a href={"content/view/full/16/"|ezurl}&gt;Books&lt;/a&gt;&lt;br /&gt;
    &lt;a href={"content/view/full/17/"|ezurl}&gt;News&lt;/a&gt;
    &lt;/td&gt;
    &lt;td valign="top"&gt;
    {$module_result.content}
    &lt;/td&gt;
&lt;/tr&gt;
&lt;/table&gt;
&lt;/body&gt;
&lt;/html&gt;
</pre>

<p>
This template consists mainly of XHTML but there are some special codes in this template, which
makes it dynamic. All these codes start with a { and ends with a }. These codes are parsed by
the template engine in eZ publish and makes the template dynamic. To start at the top we see
that we have a template definition {*?template charset=latin1?*}, this defines the charset we
use in this template. Latin1 is the most common character set used, if you need an other
character set like UTF-8 (UNICODE) you need to specify this here. This only specifies what
kind of character set used in the template.
</p>

<p>
When we include the stylesheet we use a template operator, ezdesign, {"stylesheets/core.css"|ezdesign}.
This operator tells eZ publish to look for the stylesheet stylesheets/core.css in the current design
folder. In our case this is design/mybookstore/stylesheets/core.css. This is a handy function to
use when you want templates to work with different designs and it also takes care of url translation
if you have eZ publish installed in a subfolder of your site or do not use a virtual host configuration.
E.g. www.mybookstore.com/ezpublishsite/index.php/content/view/full/42.
</p>

<p>
A similar template operator is used in our url's {"content/view/full/16/"|ezurl}. The ezurl operator
will convert relative links to real links and it will also handle non virtual host setups in the
same manner as the ezdesign operator. In our menu we have linked to two folders that we have created.
We will use these folders as containers for our products and news.
</p>

<p>
In our template we also have the template variable {$module_result.content}. This will put the
contents served by eZ publish in this position. Typically this is the information about a product or
an article you display, a list of articles or the search result page. In short any page you view will
use the pagelayout.tpl. You can also have different pagelayout.tpl files for different parts of your
site or dynamically change it based on e.g. the current user, but we're not going to go into detail
about this here.
</p>

<h2>Defining our custom content</h2>
<p>
There are a couple of concepts which are vital to understand when creating custom content. The
first is the usage of content classes. A content class is a definition of a structure. The class
is a collection of attributes. Each attribute is of a certain data type. Text line, text area,
image and integer value are typical examples of data types you can use for the class attributes.
For each class attribute we need to select the data type, if it's required and if it's searchable.
Every class attribute also have a name, e.g. Title. When we input the name of an attribute we will
automatically get something called an identifier. The identifier is a string generated from the
class attribute name but can only consist of the letters a-z and numbers 0-9 in lower case and
the underscore, _, character. We will later come back to what we use identifiers for.
</p>

<p>
A powerful feature of eZ publish is that you can define your custom content classes. As
default eZ publish comes with classes to publish folders, articles and images among others.
In our example we will create a new content class, book, which will define the structure of
information published about books. To be able to do this you need to log into the administration
interface of our site and click on the classes menu found under set up. There you select
an appropriate group, e.g. content, and click new class. Name the class "Book" and add the
needed attributes.
</p>

<p>
To publish information about a book we need to be able to define the title of the book,
name of the author and publisher, a brief and complete description of the book, a cover
image and of course the price.
</p>

<p>
On the class edit page, which is shown on the image below, we can
create attributes by selecting the data type in the lower left corner and click on the
New button. For our Book class we need to define the following attributes.
</p>

<img src="/doc/images/book_class_edit.jpg" alt="Book class edit" border="1" />


<ul>
  <li>Title: text line</li>
  <li>Author: text line</li>
  <li>Publisher: text line</li>
  <li>Brief: xml text field</li>
  <li>Description: xml text field</li>
  <li>Cover: image</li>
  <li>Price: price</li>
</ul>

<p>
Now that we've created our book class we can start publishing information about books.
To do this we go to the contents menu in the administration interface and click on a
folder, books in my example. There you can select the class you want to use in the
lower left corner and click new. That will bring you to the book edit window as shown
in the image.
</p>


<img src="/doc/images/book_edit.jpg" alt="Book edit" border="1" />
<p>
When you've published the book, you get an object. An object is the specific book,
that is the information you filled in after the rules defined in the book class.
When you publish an object it is automatically indexed in the search engine so it's
searchable from the moment it's published.
</p>

<h2>The book design</h2>

<p>
We are now able to publish information about our books, it's time to apply the design.
The book class gets a number (ID), which we need to use when we want to create a
special design for this class. You will find this number in the class list under ID.
In my case this was 6, but you need to check the number of your class.
</p>

<p>
In eZ publish you can view objects using different view modes. The two most common
that you use on most setups is line, which is the view typically used in article
lists and similar. To create a template for this view mode we need to create an
override template in the folder design/mybookstore/override/templates/node/view/line
called line_class_6.tpl. This is an template for line view mode overriden for class 6,
hence the name line_class_6.tpl.
</p>

<pre class="example">

File: design/mybookstore/override/templates/node/view/line_class_6.tpl

&lt;h2&gt;{attribute_view_gui attribute=$node.object.data_map.title}&lt;/h2&gt;
&lt;table align="right"&gt;
&lt;tr&gt;
   &lt;td&gt;
    {attribute_view_gui attribute=$node.object.data_map.cover image_class=small}
   &lt;/td&gt;
&lt;/tr&gt;
&lt;/table&gt;
{attribute_view_gui attribute=$node.object.data_map.brief}
{attribute_view_gui attribute=$node.object.data_map.price}
&lt;a href={concat("/content/view/full/",$node.node_id,"/")|ezurl}&gt;View book&lt;/a&gt;
</pre>

<img src="/doc/images/book_line.jpg" alt="Book line view" border="1" />

<p>
On the picture you see how the line_class_6.tpl template
displays a summary of the book. This template is normally used in lists and this
is a typical example of how you would create the template for a product in a web shop.
</p>

<p>
In the line_class_6.tpl we have some template functions which make them dynamic.
The first function we use is the attribute_view_gui function. This function takes
at least one parameter attribute (  in some cases you can supply more ). The
attribute parameter specifies which of the attributes in our object should be
shown. In the template we have a variable, $node. The $node variable is an object
which contains the specific placement of the object on our site, e.g. it will
know that the object is placed in / books / in our site. The $node object also
contains the content object. You can access the content object by writing $node.object.
Now that we have object we would like to get access to a specific attribute to supply
for the attribute_view_gui function. This is done using the data_map, $node.object.data_map,
followed by the identifier of the desired attribute. To fetch the title of our book
we just need to write attribute_view_gui attribute=$node.object.data_map.title, the
attribute_view_gui function will then handle the display of that attribute.
In the case of the attribute being an image we can supply a parameter image_class,
which is optional, to define the size of the image. You can set the image_class to small,
medium or large.
</p>

<p>
The second function we use in our template is concat(), this function will concatenate
the values supplied, to a string. In our example we use the function to create a url.
We then use the returned value from the concat() function with the operator ezurl,
in the same manner we did with the pagelayout.tpl.
</p>

<p>
We also want to create an override template for the full view mode, which is used
when viewing a specific book, see image below. To do this
we need to create a file in the same folder but with the name full_class_6.tpl.
</p>

<img src="/doc/images/book_full.jpg" alt="Book full view" border="1" />


<pre class="example">
File: design/mybookstore/override/templates/node/view/full_class_6.tpl

&lt;h1&gt;{attribute_view_gui attribute=$node.object.data_map.title}&lt;/h1&gt;
Author: {attribute_view_gui attribute=$node.object.data_map.author}&lt;br /&gt;
Publisher: {attribute_view_gui attribute=$node.object.data_map.publisher}&lt;br /&gt;
&lt;table align="right"&gt;
&lt;tr&gt;
   &lt;td&gt;
    {attribute_view_gui attribute=$node.object.data_map.cover}
   &lt;/td&gt;
&lt;/tr&gt;
&lt;/table&gt;
{attribute_view_gui attribute=$node.object.data_map.brief}
{attribute_view_gui attribute=$node.object.data_map.description}
&lt;b&gt;{attribute_view_gui attribute=$node.object.data_map.price}&lt;/b&gt;
</pre>

<p>
Now we've created a small site on eZ publish where we can publish information about
books. We would now have to work a bit more on design before we have a ready site.
From these few steps the true power of eZ publish 3 is shown. You can in a simple way
create highly customized websites with functionality such as search, permission checking
and versioning already implemented and ready to use.
</p>

<p>
eZ publish is used worldwide by thousands of websites. People use eZ publish for
their company sites, e-commerce sites, portals, and intranets. These functions
are only some of the possibilities that will be available with eZ publish 3. You
will find more information about eZ publish 3 and the many possibilities on
<a href="http://ez.no/developer">http://ez.no/developer</a>.
</p>
