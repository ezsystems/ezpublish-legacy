<?php
//
// Created on: <25-Jun-2002 10:49:40 bf>
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

$DocResult = array( "title" => "Site access" );
?>

<h1>Understanding site access</h1>
<p>
Site access is a new and powerful way of handling access to your site. By using this
you can create different views to your site specially designed for a certain role or
user group. For example the admin page on the eZ publish 2.x series can be implemented
very easily with site access. Other uses for this is to create subsites.
</p>

<h2>Recognizing site accesses</h2>
<p>
There are four ways to recognize a site access, by <i>uri</i>, by <i>hostname</i>, by <i>port</i> or by <i>filename</i>.
URI, hostname and filename way has three matching types: <i>element</i>, <i>subtext</i> or <i>regexp</i>, with the exception of uri matching
which only supports element and regexp. All INI variables are placed in the group <b>SiteAccessSettings</b>.
The port matching is much simpler, it only maps the port number to an access name. Each port is listed under the
<b>PortAccessSettings</b> group (See below for example).
</p>
<p>
A fifth method is also possible by setting the <b>StaticMatch</b> in group <b>SiteAccessSettings</b>
to a value, this is useful when you package a site with a specific access style, for instance this
SDK uses StaticMatch=sdk.
<p>
Selecting which type is used is done by setting the <b>MatchOrder</b> variable. The order of matching
is also specified by this variable, this means that first type to make a match controls the access.
For instance to get only host and index matching we do.
</p>
<pre class="example">
MatchOrder=host;index
</pre>

<h3>Matching with element</h3>
<p>
Element matching is the simplest matching type to use but also the least flexible. The element
matcher will split up the matching text by a defined character and select one of the elements.
The element index is determined by these variables in site.ini.
</p>
<table class="listing">
<tr>
  <th>Match type</th><th>Separator</th><th>site.ini variable</th>
</tr>
<tr>
  <td>URI</td><td>/</td><td>URIMatchElement</td>
</tr>
<tr>
  <td>Host</td><td>.</td><td>HostMatchElement</td>
</tr>
<tr>
  <td>Index</td><td>_</td><td>IndexMatchElement</td>
</tr>
</table>

<p>
Examples of element matching.
</p>

<table class="example" cellspacing="0">
<tr>
  <th>Source text</th><th>Element index</th><th>Result</th>
</tr>
<tr>
  <td>/admin/view/something</td><td>1</td><td>admin</td>
</tr>
<tr>
  <td>admin.example.com</td><td>0</td><td>admin</td>
</tr>
<tr>
  <td>index_admin.php</td><td>1</td><td>admin</td>
</tr>
</table>


<h3>Matching with subtext</h3>
<p>
Matching by subtext is done by specifying the text wich appears infront and the text that
appears after the text you want extracted. For that reason matching subtexts in URI is not
possible because the text that appear after the wanted text will change all the time.
The pre and post text is determined by these variables in site.ini.
</p>

<table class="listing">
<tr>
  <th>Match type</th><th>Pre variable</th><th>Post variable</th>
</tr>
<tr>
  <td>Host</td><td>HostMatchSubtextPre</td><td>HostMatchSubtextPost</td>
</tr>
<tr>
  <td>Index</td><td>IndexMatchSubtextPre</td><td>IndexMatchSubtextPost</td>
</tr>
</table>

<p>
Examples of subtext matching.
</p>

<table class="example">
<tr>
  <th>Source text</th><th>Pre text</th><th>Post text</th><th>Result</th>
</tr>
<tr>
  <td>admin.example.com</td><td></td><td>.example.com</td><td>admin</td>
</tr>
<tr>
  <td>index_admin.php</td><td>index_</td><td>.php</td><td>admin</td>
</tr>
</table>

<h3>Matching with regexp</h3>
<p>
Regexp is the most advanced (and complex) method of matching, with this
you can match about anything. You specicy the regexp and which subexpression
should be extracted. For more information about regexp creation see the reference at the bottom.
The regexp and subexpression number is determined by these variables in site.ini.
</p>

<table class="listing">
<tr>
  <th>Match type</th><th>Regexp variable</th><th>Subexpression variable</th>
</tr>
<tr>
  <td>URI</td><td>URIMatchRegexp</td><td>URIMatchRegexpItem</td>
</tr>
<tr>
  <td>Host</td><td>HostMatchRegexp</td><td>HostMatchRegexpItem</td>
</tr>
<tr>
  <td>Index</td><td>IndexMatchRegexp</td><td>IndexMatchRegexpItem</td>
</tr>
</table>

<p>
Examples of regexp matching.
</p>

<table class="example">
<tr>
  <th>Source text</th><th>Regexp</th><th>Subexpression value</th><th>Result</th>
</tr>
<tr>
  <td>/admin/view/something</td><td>^/([^/]+)/</td><td>1</td><td>admin</td>
</tr>
<tr>
  <td>admin.example.com</td><td>^(.+)\.example\.com$</td><td>1</td><td>admin</td>
</tr>
<tr>
  <td>index_admin.php</td><td>^index_(.+)\.php$</td><td>1</td><td>admin</td>
</tr>
</table>

<h3>Example configuration</h3>
<p>
Example of site.ini which matches first hosts with a regexp, then uris with elements
and finally index with subtext (pre and post).
</p>
<pre class="example">
[SiteAccessSettings]
MatchOrder=port;host;uri;index

URIMatchType=element
URIMatchElement=1

HostMatchType=regexp
HostMatchRegexp=^(.+)\.example\.com$

IndexMatchType=text
IndexMatchSubtextPre=index_
IndexMatchSubtextPost=.php

[PortAccessSettings]
1337=sdk
</pre>

<h2>Controlling site access</h2>
<p>
Once a match from either URI, index or host a siteaccess setup is checked for existance.
The setup is located in <i>settings/siteaccess</i> and will have a separate directory for each
access name. For instance accessing <i>admin.example.com</i> would require <i>settings/siteaccess/admin</i>
to be existant.
The setup directory will then become the new override directory meaning that you can override
<i>site.ini</i> or any other INI files present in the original directory.
<p>

<p>
Once the site access is in place it will try to reload the <i>site.ini</i> file, this means that
if a <i>site.ini</i> or <i>site.ini.append</i> is present in the site access setup it's values will
be used. This means that you can change everything from site title, database host to which modules
is active.
</p>

<h3>Changing access rules</h3>
<p>
The default rule of the kernel is to allow all views for everyone (assuming the permission allows it).
To tighten in what is allowed on a site you add a group entry in <i>site.ini</i> called <b>SiteAccessRules</b>
It contains an order list of access items which is processed for a module/function access.
It behaves similar to iptables or ipchains meaning that it's just a list of items which either
enable or disables items. The rule matcher has two enabled/disable states, one which is returned after the
list is done, called the current state, and the other which is temporary.
The temporary state is copied to the current state whenever a match is done.
</p>

<h3>Recognized access items</h3>
<ul>
  <li>Access - Changes the temporary state to enabled if it contains <b>enable</b> or disabled otherwise</li>
  <li>Module - Copies the temporary state to the current state if the current module matches it</li>
  <li>ModuleAll - Copies the temporary state if it contains <b>true</b></li>
</ul>

<h3>Access example</h3>
<p>
Shows how the module <b>sdk</b> is matched against the rules and how the states change.
The result of this rule is that it only allows the <b>sdk</b> module.
</p>
<table class="example">
  <tr>
    <th>Item</th><th>Value</th><th>Current state</th><th>Temporary state</th>
  </tr>
  <tr><td>Initial</td><td></td><td>enabled</td><td>enabled</td></tr>
  <tr><td>Access</td><td>disable</td><td>enabled</td><td>disabled</td></tr>
  <tr><td>ModuleAll</td><td>true</td><td>disabled</td><td>disabled</td></tr>
  <tr><td>Access</td><td>enable</td><td>disabled</td><td>enabled</td></tr>
  <tr><td>Module</td><td>sdk</td><td>enabled</td><td>enabled</td></tr>
</table>

<h3>site.ini example</h3>
<pre class="example">
[SiteAccessRules]
Access=disable
ModuleAll=true
Access=enable
Module=sdk
</pre>

<h1>Further reading</h2>
<p>
Here you will find some links to related articles for more information on specific topics.
</p>

<ul>
	<li><a href="http://zez.org/article/articleview/11/">http://zez.org/article/articleview/11/ - Regular expressions explained</a></li>
</ul>
