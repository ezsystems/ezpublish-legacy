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

$DocResult = array( "title" => "Directory Layout" );
?>

<h2>Root</h2>
<p>
The eZ publish root directory is divided into multiple subdirectories, each which their own role.
</p>

<h3>bin</h3>
<p>
Contains various executables and shell scripts for different operation systems.
</p>

<h3>design</h3>
<p>
Contains all design related files, such as templates, images, style sheets and
template preparation scripts. These are categorized in site designs and can
contain override files.
The default site design is <b>standard</b>. It is also the default fallback design.
</p>

<h3>doc</h3>
<p>
Contains all documentation ranging from requirement documents to design documents and change logs.
This is the place too look if you want information on how eZ publish was designed and what has
changed. The dynamic documentation can be found in the <b>sdk</b> directory.
</p>

<h3>kernel</h3>
<p>
Contains all classes, views, data types and related kernel files. This is the core of the system.
You should not normally need to change anything here.
<!-- and this is where you should look if you want to change eZ publish specific functionality.
A lot of the kernel uses the <b>eZ publish libraries</b> so some features are probably moved
to libraries when they are general. -->
</p>

<h3>lib</h3>
<p>
Contains the general eZ publish libraries. These libraries are collections of classes which perform
various tasks. The libraries are highly reusable and are in no way dependent on the kernel.
People looking for general PHP libraries should take a look here.
</p>

<h3>sdk<h3>
<p>
Contains dynamic PHP scripts for rendering the various documentation bits, such as change logs,
API reference and kernel examples. The code found here is somewhat dependent on the <b>kernel</b>.
</p>

<h3>settings</h3>
<p>
Contains configuration files for eZ publish, these files are meant to be changed by sites,
and are unique for one site and should not be shared.
It also has site access configuration.
The size of this directory is generally small and never grows, see <b>var</b> for cache files.
</p>

<h3>share</h3>
<p>
Contains static configuration files such as code pages, locale information and translation files.
Dynamic configuration can be found in <b>settings</b>.
This directory can easily be shared among eZ publish installations.
</p>

<h3>var</h3>
<p>
Contains various cache files, logs and stored images and files.
This directory will grow in size as the site is used more and more,
it is therefore a good idea to put this on a large partition, and monitor it's growth.
Use the logs to solve configuration problems or other issues.
</p>
