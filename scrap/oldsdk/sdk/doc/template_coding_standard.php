<?php
//
// Created on: <04-Jun-2002 09:09:16 bf>
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

include_once( "lib/ezutils/classes/eztexttool.php" );

$DocResult = array();
$DocResult["title"] = "Template Coding Standard";
?>

<p>
The templates are a mix of XHTML, or other output formats, and some eZ template
blocks and variables. This document defines the structure and syntax of the
eZ template related parts. The XHTML standard defines how you should format
XHTML/HTML.
</p>

<h2>Template variables</h2>
<p>
Template variables should be named in lower case. Each word in the variable should
be separated by _. Attributes should be lowercase and named in the same manner as
template variables. Template variables that work as lists should be named as such,
since this makes them more visible, i.e. <i>workflow_list</i> not <i>workflows</i>.
Spotting <i>workflow</i> from <i>workflows</i> can be hard.
</p>

<pre class="example"><? print( eZTextTool::highlightHTML(
'$my_template_variable
$object.attribute
$class'
) );?>
</pre>

<h2>Namespaces</h2>
<p>
Namespaces should be named with capital first letters.
</p>

<pre class="example"><? print( eZTextTool::highlightHTML(
'{switch match=$match1}
  {case match=1}Matched 1{/case}
  {case match=2}Matched 2{/case}
  {case}Matched default{/case}
{/switch}'
) );?>
</pre>

<p>

</p>

<pre class="example"><? print( eZTextTool::highlightHTML(
'{section name=Num loop=$numbers}
{$Num:index}:{$Num:number}:{$Num:key} Number: {$Num:item}
{/section}
'
) );?>
</pre>

<h1 id="security">Security in templates</h1>
<p>
All templates shipped with eZ publish are designed with security in mind, this means that have proper
output washing to avoid XSS exploits. However for those of you who create new templates it's important
that steps are taken to secure the templates.
</p>

<h2>Output washing</h2>
<p>
Before displaying stored data in an HTML page you must make sure that it's presentable, especially
to avoid cross-site scripting (XSS). This might mean
escaping the data or converting it to a different form, however this washing must not be done until
the data is just about to be shown to the user. This means that the code for escaping must not be
placed in the class or function which returns the input data but rather in the template code, this
because it's not known what the client code wants to do with the data.
</p>

<h3>Example using wash operator</h3>
<pre class="example"><? print( eZTextTool::highlightHTML(
'$obj = new eZObject( $id );

$tpl->setVariable( "obj", $obj );
$tpl->display( "view.tpl" );

// view.tpl
{$obj.title|wash}
{$obj.description|wash}
{$obj.price}
{$obj.email|wash(email)}
'
) );?>
</pre>

<p>
It is also important to make sure that all generated urls is washed properly, for instance it is possible
to input special characters in the url and have alter the generated HTML code in such a way that it will
run javascripts.
</p>
<p>In eZ publish escaping urls are done with the <i>ezurl</i> operator which will make sure the resulting url
is properly escaped as well as have correct form for non-virtual hosts.</p>

<h3>Example using ezurl operator</h3>
<pre class="example"><? print( eZTextTool::highlightHTML(
'$viewmode = $Params["ViewMode"];

// view.tpl
<a href={concat("module/view/",$viewmode)|ezurl}>
'
) );?>
</pre>
