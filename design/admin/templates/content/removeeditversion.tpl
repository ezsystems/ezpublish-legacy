{let version=fetch( content, version, hash( object_id, $object_id,
                                            version_id, $object_version ) )}
<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/edit_draft' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Object ID *}
<p>
<label>{'ID'|i18n( 'design/admin/content/edit' )}:</label>
{*
{$object.id}
*}
</p>

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/edit' )}:</label>
{*
{section show=$object.published}
{$object.published|l10n( shortdatetime )}<br />
{$object.current.creator.name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/edit' )}
{/section}
*}
</p>

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/edit' )}:</label>
{*
{section show=$object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/edit' )}
{/section}
*}
</p>

{* Published version *}
<p>
<label>{'Published version'|i18n( 'design/admin/content/edit' )}:</label>
{*
{section show=$object.published}
{$object.current.version}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/edit' )}
{/section}
*}
</p>

</div></div></div></div></div></div>

</div>

</div>
</div>

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

<form action={"content/removeeditversion"|ezurl} method="post" name="EditVersionRemove">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Discard draft?'|i18n( 'design/standard/content/edit' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">
<h2>{"Are you sure you want to discard the draft %versionname?"
     |i18n( 'design/standard/content/edit',,
            hash( '%versionname', concat( '&lt;', $version.version_name, '&gt;' ) ) )}</h2>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
    {include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/content/edit")}
    {include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/content/edit")}
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>

{/let}

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
