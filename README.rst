eZ Online Editor 5.x extension README


What is eZ Online Editor 5.x?
============================

eZ Online Editor 5.x (ezoe) is a eZ Publish extension that customizes
eZ XML datatype (the "Rich text" datatype) with a wysiwyg editor (TinyMCE based).

ezoe provides a user friendly interface for editing eZ XML, it does not change the
fact that eZ XML is a xml format (XHTML 2.0 inspired) that by default enforces*
strict separation between content and design. Thus all features of TinyMCE not supported
by eZ XML is disabled and not supported (supported plugins/buttons are listed in ezoe.ini).

\* This enables enterprise features like pr tag template view, multi channel publishing, clean content export and places design control in the hands of designers and not editors.


Install
=======

See: ./INSTALL  (the file named INSTALL in the same directory you found this file)


Debug
=====

ezoe consists of three parts: TinyMCE, ezoe TinyMCE customizations and a eZXML input handler.

TinyMCE
-------
TinyMCE in ezoe is a out of the box setup of the official version, no hacks/changes applied at all.
We use Ephox LGPL version, which is the original Moxiecode version with additional bug fixes.
So if it turns out that issue comes from this part, then we'll report the issue for you to Ephox
if your running a Enterprise version of eZ Publish, and if not you'll have to report it to Moxiecode.

ezoe TinyMCE customizations
---------------------------
These consist of a custom theme (ez), plugins (eztable & ezemotions) and ezoe settings for TinyMCE
in design/standard/templates/datatype/edit/ezxmltext_ezoe.tpl (partly based on ezoe.ini settings)
All customizations exists to add eZXML features and hide TinyMCE features not supported by eZXML.


eZXML input handler
-------------------
Consist of input handler that takes over handling of edit interface of eZXML and translates eZXML to and
from a xhtml format TinyMCE (with ezoe TinyMCE customizations) understands.
Input is handled by eZOEInputParser and output to TinyMCE is handled directly on input handler.


Example of debugging ezoe #1
----------------------------
Example issue: #017838: Anchors disappears after publishing
( example shows how issue was identified, checkout ezoe before fix in f06d39f600800d2d0ebf9f7d331cd865bd4dabf9 if you want to try it yourself)
In this case there are four possible places to look: parser, TinyMCE ez theme,
ezoe settings for TinyMCE and TinyMCE itself, run-down:

1. Enable ezoe parser debug so we can identify when anchor disappears, debug.ini:
    [GeneralCondition]
    kernel-datatype-ezxmltext-ezoe=enabled

2. Edit some content object with ezoe(ezxml attribute) and add a anchor.

3. Right click on anchor and click 'Inspect this', you'll see that it has the
   following source: <a style="" id="Anchor" class="mceItemAnchor"></a>

4. Click Store draft, it will now disappear from the document, and in your debug output
   you'll see that it was received by parser as: <a id="Anchor"></a>

   This (todo: add doc on internal format) is not how the parser expects it to be.
   From eZOEInputParser->tagNameLink we can see that it expects either 'name' to be set
   or class to be 'mceItemAnchor' as it had in the source when added to TinyMCE.

3. Double checking in ezxmltext_ezoe.tpl it looks like <a> does allow class attribute, ref:
   a[href|name|target|view|title|class|id|customattributes]

   We have now identified that issue is in TinyMCE / ezoe TinyMCE customizations.
   And when verifying on a stock TinyMCE it became clear that TinyMCE uses 'name'
   attribute now that it is allowed in html5 but was deprecated in xhtml so solution was
   to change ezoe to use 'name' internally.

