<?php
//
// Created on: <29-May-2002 09:41:04 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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


?>
<p>
The eZ debug&trade; library has some settings which can be edited in the file <b>settings/site.ini</b>.
</p>

<p>
You can enable debug information for one specific host, which is useful for debugging live systems.
You can choose to show the debug information inline in the browser or in a popup winodow. There is also a
setting to enable automatic or manual redirecting when jumping from a process page to a result page.
</p>

<p>Sample settings for site.ini</p>
<pre class="example">
[DebugSettings]
# IP addresses which will get debug information
# Valid are: enabled, disabled or list of IP's separated by ;
DebugIP=10.0.2.3
# Use either disabled, inline or popup
Debug=inline
# Use either enabled or a an array of uris to match
DebugRedirection=disabled
</pre>
