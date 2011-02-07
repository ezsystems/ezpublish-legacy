
{include uri="design:content/parts/object_information.tpl" object=$object manage_version_button=true()}

<div class="drafts">

{* DESIGN: Header START *}<div class="box-header">

<h4>{'Current draft'|i18n( 'design/admin/content/edit' )}</h4>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/edit' )}:</label>
{$content_version.created|l10n( shortdatetime )}<br />
{$content_version.creator.name|wash}
</p>

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/edit' )}:</label>
{$content_version.modified|l10n( shortdatetime )}<br />
{$content_version.creator.name|wash}
</p>

{* Version *}
<p>
<label>{'Version'|i18n( 'design/admin/content/edit' )}:</label>
{$edit_version}
</p>

<div class="block">
<input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n( 'design/admin/content/edit' )}" title="{'Preview the draft that is being edited.'|i18n( 'design/admin/content/edit' )}" />
</div>

{* DESIGN: Content END *}</div>
</div>

<!-- Translation box start-->
<div class="translations">

{* DESIGN: Header START *}<div class="box-header">

<h4>{'Existing translations'|i18n( 'design/admin/content/edit' )}</h4>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">
<p>{'Base translation on'|i18n( 'design/admin/content/edit' )}:</p>
<label>
<input type="radio" name="FromLanguage" value=""{if $from_language|not} checked="checked"{/if}{if $object.status|eq(0)} disabled="disabled"{/if} /> {'None'|i18n( 'design/admin/content/edit' )}
</label>

{if $object.status}
{foreach $object.languages as $language}
	{if not( eq($language.locale, $object.current_language_object.locale) )} {* Only providing other languages than current *}
	<label>
	<input type="radio" name="FromLanguage" value="{$language.locale}"{if $language.locale|eq($from_language)} checked="checked"{/if} />
	<img src="{$language.locale|flag_icon}" width="18" height="12" alt="{$language.locale}" style="vertical-align: middle;" />
	{$language.locale}
	</label>
	{/if}
{/foreach}
{/if}

<div class="block">
<input {if $object.status|eq(0)}class="button-disabled" disabled="disabled"{else} class="button"{/if} type="submit" name="FromLanguageButton" value="{'View'|i18n( 'design/admin/content/edit' )}" title="{'Edit the current object showing the selected language as a reference.'|i18n( 'design/admin/content/edit' )}" />
</div>

{* DESIGN: Content END *}</div>
</div>

<!-- Translation box end-->

{* Edit section *}
<div class="sections">
{include uri='design:content/parts/edit_sections.tpl'}
</div>


{* Edit states *}
<div class="states">
{include uri='design:content/parts/edit_states.tpl'}
</div>

