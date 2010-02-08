<div class="objectinfo">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h4>{'Object information'|i18n( 'design/admin/content/edit' )}</h4>

{* DESIGN: Header END *}</div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-br"><div class="box-bl"><div class="box-content">

{* Object ID *}
<p>
<h6>{'ID'|i18n( 'design/admin/content/edit' )}:</h6>
{$object.id}
</p>

{* Created *}
<p>
<h6>{'Created'|i18n( 'design/admin/content/edit' )}:</h6>
{if $object.published}
{$object.published|l10n( shortdatetime )}<br />
{$object.owner.name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/edit' )}
{/if}
</p>

{* Modified *}
<p>
<h6>{'Modified'|i18n( 'design/admin/content/edit' )}:</h6>
{if $object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{$object.current.creator.name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/edit' )}
{/if}
</p>

{* Published version *}
<p>
<h6>{'Published version'|i18n( 'design/admin/content/edit' )}:</h6>
{if $object.published}
{$object.current.version}
{else}
{'Not yet published'|i18n( 'design/admin/content/edit' )}
{/if}
</p>

{* Manage versions *}
<div class="block">
{if $object.versions|count|gt( 1 )}
<input class="button" type="submit" name="VersionsButton" value="{'Manage versions'|i18n( 'design/admin/content/edit' )}" title="{'View and manage (copy, delete, etc.) the versions of this object.'|i18n( 'design/admin/content/edit' )}" />
{else}
<input class="button-disabled" type="submit" name="VersionsButton" value="{'Manage versions'|i18n( 'design/admin/content/edit' )}" disabled="disabled" title="{'You cannot manage the versions of this object because there is only one version available (the one that is being edited).'|i18n( 'design/admin/content/edit' )}" />
{/if}
</div>

</div></div></div></div></div></div>

</div>

<div class="drafts">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h4>{'Current draft'|i18n( 'design/admin/content/edit' )}</h4>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{* Created *}
<p>
<h6>{'Created'|i18n( 'design/admin/content/edit' )}:</h6>
{$content_version.created|l10n( shortdatetime )}<br />
{$content_version.creator.name|wash}
</p>

{* Modified *}
<p>
<h6>{'Modified'|i18n( 'design/admin/content/edit' )}:</h6>
{$content_version.modified|l10n( shortdatetime )}<br />
{$content_version.creator.name|wash}
</p>

{* Version *}
<p>
<h6>{'Version'|i18n( 'design/admin/content/edit' )}:</h6>
{$edit_version}
</p>

<div class="block">
<input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n( 'design/admin/content/edit' )}" title="{'View the draft that is being edited.'|i18n( 'design/admin/content/edit' )}" />
</div>

{* DESIGN: Content END *}</div></div></div>
</div>

<!-- Translation box start-->
<div class="translations">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h4>{'Translate from'|i18n( 'design/admin/content/edit' )}</h4>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

<label>
<input type="radio" name="FromLanguage" value=""{if $from_language|not} checked="checked"{/if}{if $object.status|eq(0)} disabled="disabled"{/if} /> {'No translation'|i18n( 'design/admin/content/edit' )}
</label>

{if $object.status}
{foreach $object.languages as $language}
<label>
<input type="radio" name="FromLanguage" value="{$language.locale}"{if $language.locale|eq($from_language)} checked="checked"{/if} />
<img src="{$language.locale|flag_icon}" alt="{$language.locale}" style="vertical-align: middle;" />
{$language.name|wash}
</label>
{/foreach}
{/if}

<div class="block">
<input {if $object.status|eq(0)}class="button-disabled" disabled="disabled"{else} class="button"{/if} type="submit" name="FromLanguageButton" value="{'Translate'|i18n( 'design/admin/content/edit' )}" title="{'Edit the current object showing the selected language as a reference.'|i18n( 'design/admin/content/edit' )}" />
</div>

{* DESIGN: Content END *}</div></div></div>
</div>

<!-- Translation box end-->

{* Edit section *}
<div class="sections">
{include uri='design:content/edit_sections.tpl'}
</div>


{* Edit states *}
<div class="states">
{include uri='design:content/edit_states.tpl'}
</div>

