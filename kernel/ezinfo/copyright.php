<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];

$text = <<<'COPYRIGHT'
<p>Copyright (C) 1999-2015 eZ Systems AS. All rights reserved.</p>

<p>This file may be distributed and/or modified under the terms of the
"GNU General Public License" version 2 as published by the Free
Software Foundation and appearing in the file LICENSE included in
the packaging of this file.</p>

<p>Licencees holding a valid "eZ Business Use License" version 2.1
may use this file in accordance with the "eZ Business Use License"
version 2.1 Agreement provided with the Software.</p>

<p>This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE.</p>

<p>The "eZ Business Use License" version 2.1 is available at
<a href="http://ez.no/Products/About-our-Software/Licenses/eZ-Business-Use-License-Agreement-eZ-BUL-Version-2.1">http://ez.no/Products/About-our-Software/Licenses/eZ-Business-Use-License-Agreement-eZ-BUL-Version-2.1</a> and in the file
PROFESSIONAL_LICENCE included in the packaging of this file.
For pricing of this licence please contact us via e-mail to <a href="mailto:licence@ez.no">licence@ez.no</a>.
Further contact information is available at <a href="http://ez.no/About-eZ/Contact-Us">http://ez.no/About-eZ/Contact-Us</a>.</p>

<p>The "GNU General Public License" (GPL) is available at
<a href="http://www.gnu.org/copyleft/gpl.html">http://www.gnu.org/copyleft/gpl.html</a>.</p>

<p>Contact eZ Systems if any conditions of this licencing isn't clear to you.</p>
COPYRIGHT;

$Result = array();
$Result['content'] = $text;
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/ezinfo', 'Info' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/ezinfo', 'Copyright' ) ) );

?>
