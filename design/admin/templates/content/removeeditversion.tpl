{let version=fetch( content, version, hash( object_id, $object_id, version_id, $object_version ) )}

<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/removeeditversion' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Object ID *}
<p>
<label>{'ID'|i18n( 'design/admin/content/removeeditversion' )}:</label>
{$version.contentobject.id}
</p>

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/removeeeditversion' )}:</label>
{section show=$version.contentobject.published}
{$version.contentobject.published|l10n( shortdatetime )}<br />
{$version.contentobject.current.creator.name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/removeeditversion' )}
{/section}
</p>

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/removeeditversion' )}:</label>
{section show=$version.contentobject.modified}
{$version.contentobject.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $version.contentobject.content_class.modifier_id ) ).name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/removeeditversion' )}
{/section}
</p>

{* Published version *}
<p>
<label>{'Published version'|i18n( 'design/admin/content/removeeditversion' )}:</label>
{section show=$version.contentobject.published}
{$version.contentobject.current.version}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/removeeditversion' )}
{/section}
</p>

</div></div></div></div></div></div>

</div>

</div>
</div>

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

<form name="EditVersionRemove" method="post" action={'content/removeeditversion'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Confirm draft discard'|i18n( 'design/admin/content/removeeditversion' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">
<h2>{'Are you sure you want to discard the draft?'|i18n( 'design/admin/content/removeeditversion' )}</h2>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
<input class="button" type="submit" name="ConfirmButton" value="{'Yes'|i18n( 'design/admin/content/removeeditversion' )}" />
<input class="button" type="submit" name="CancelButton" value="{'No'|i18n( 'design/admin/content/removeeditversion' )}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>

{/let}
