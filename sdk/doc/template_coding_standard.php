<?php
//
// Created on: <04-Jun-2002 09:09:16 bf>
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

include_once( "lib/ezutils/classes/eztexttool.php" );

$DocResult = array();
$DocResult["title"] = "Template Coding Standard";
?>

<p>
The templates is a mix of XHTML, or other output formats, and some eZ template
blocks and variables. This document defines the structure and syntax of the
eZ template related parts. The XHTML standard defines how you should format
XHTML/HTML.
</p>

<h2>Template variables</h2>
<p>
Template variables should be named in lower case. Each word in the variable should
be separated by _. Attributes should be lowercase and named in the same manner as
template variables. Template variables which works as lists should be named as such
since this makes them more visible, ie. <i>workflow_list</i> not <i>workflows</i>,
spotting <i>workflow</i> from <i>workflows</i> can be hard.
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
