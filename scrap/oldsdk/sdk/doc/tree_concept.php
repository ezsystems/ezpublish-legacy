<?php
//
// Created on: <08-Jul-2002 16:27:16 bf>
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

<p>
eZ publish has a tree based structure. This makes it very simple to create
intuitive site structures. This structure also has some limitations, here
we will discuss how the tree structure works in eZ publish and how it's meant
to be used.
</p>

<h1>An example tree</h1>
<p>
The diagram below shows an example tree structure. It consists of four
folders and two articles. The top node is the main node in the tree, this
must always be present. We've created two direct sub nodes of the "Top node", the
"Front page news" and "Music". Under the news node we have created a folder called
"New releases". The colours indicates which permissions and which workflows that
are used.
</p>
<img src="/doc/images/tree_overview.png" alt="Tree" />

<h2>Workflows</h2>
<p>
The workflow defines what should happen to an object when an action is performed.
On the frontpage we have a strict workflow which requires several editors to approve
before any content is published. The "New releases" folder has an open workflow where
every user can publish a story about new releases, this workflow only sends a notice
to the editor about new content beeing published.
</p>

<p>
When you create a content object which should be published into both the "Front page news"
and the "New releases" two workflows are created. In this case the content published
under "New releases" will be published at once, whilst the content for the "Front page news"
needs to be approved by an editor. This means that the content object will be published
two places at different times. The same would happen if you update the content object, i.e.
two different versions of the content object could exist at the same site.
</p>

<h2>URL's</h2>
<p>
Since the same object can exist in different versions in different part of the
tree we need to know which node we're looking at. To know this we we need to define
where a content object belongs - main node id. In URL's you can view objects with
URL's like this:
</p>

<pre class="example">
site.com/content/view/42/
</pre>

<p>
42 beeing the conent object ID. In this example the "Sting" article is found
two places in the tree, but it does belong in the "New releases" folder. Therefore
when entering the URL as above you will view the object with the path
<b>/ Top Node / Music / New releases / </b>.
</p>

<p>
In some cases you would like to display the <b>/ Top node / Front page news /</b>.
I.e. if you clicked on the article from that node/frontpage. This is done by
providing an extra parameter to the URL defining the <b>NodeID</b>. The <b>NodeID</b>
defines the absolute place in the tree. In this case the Sting article is placed
twice in the tree and can be identified by either NodeID  6 or 7. The URL below would
show the path  <b>/ Top node / Front page news /</b>.
</p>

<pre class="example">
site.com/content/view/42/7
</pre>

<h2>Searching</h2>
<p>
Since there can be published two different versions of an object two different places
in the tree the search engine needs to be able to handle this. If only one version of
an article is published it will only show up once in the search like you would expect.
The URL/NodeID would be main node id for that content object.
</p>

<p>
If you have two versions of a content object published they can contain different
content and that's why both versions should be handled as different searchable object.
E.g. in the same search you could find two versions of an article published on
different places. However if an object is published several places with the same
version only the main node will be indexed and searchable.
</p>
