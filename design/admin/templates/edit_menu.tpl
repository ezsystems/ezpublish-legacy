<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>Object info</h4>

</div></div></div></div></div></div>

<div class="box-ml"><div class="box-mr"><div class="box-content">

<p>
<label>{"Created"|i18n("design/standard/content/edit")}:</label>
{section show=$object.published}
{$object.published|l10n(date)}<br />
{$object.current.creator.name}
{section-else}
{"Not yet published"|i18n("design/standard/content/edit")}
{/section}
</p>
<p>
<label>{"Last Modified"|i18n("design/standard/content/edit")}:</label>
{section show=$object.modified}
{$object.modified|l10n(date)}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name}
{section-else}
{"Not yet published"|i18n("design/standard/content/edit")}
{/section}
</p>

</div></div></div>

</div>

<!-- Translation box start-->
<div class="translations">
{let name=Translation
     language_index=0
     default_translation=$content_version.translation
     other_translation_list=$content_version.translation_list
     translation_list=$Translation:other_translation_list|array_prepend($Translation:default_translation)}

{section show=$Translation:translation_list}
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">
<h4>{"Translations"|i18n("design/standard/content/edit")}</h4>
</div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-content">

{section loop=$Translation:translation_list}
  {section show=eq($edit_language,$Translation:item.language_code)}
    {set language_index=$Translation:index}
  {/section}
{/section}

{section loop=$Translation:translation_list}
<p>
<label>
{section show=$Translation:other_translation_list|gt(0)}
          <input type="radio" name="EditSelectedLanguage" value="{$Translation:item.language_code}" {section show=eq($Translation:index,$Translation:language_index)}checked="checked"{/section} />
{/section}
{section show=$Translation:item.locale.is_valid}
<img src={concat( '/share/icons/flags/', $Translation:item.language_code, '.gif' )|ezroot} alt="($Translation:item.language_code)" style="vertical-align: middle;" /> {$Translation:item.locale.intl_language_name}
{section-else}
{"%1 (No locale information available)"|i18n("design/standard/content/edit",,array($Translation:item.language_code))}
{/section}
</label>
</p>
{/section}
<div class="block">
	  <input class="button" type="submit" name="TranslateButton" value="{'Manage'|i18n('design/standard/content/edit')}" />
{section show=$Translation:other_translation_list|gt(0)}
          <input class="button" type="submit" name="EditLanguageButton" value="{'Edit'|i18n('design/standard/content/edit')}" />
{/section}
</div>
{/section}

{/let}
</div></div></div></div>
</div>

<!-- Translation box end-->

<div class="versions">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">
<h4>{"Versions"|i18n("design/standard/content/edit")}</h4>
</div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<div class="element">
<p>
<label>{"Editing"|i18n("design/standard/content/edit")}:</label>
{$edit_version}
</p>
</div>
<div class="element">
<p>
<label>{"Current"|i18n("design/standard/content/edit")}:</label>
{$object.current_version}
</p>
</div>
</div>
<div class="block">
<input class="button" type="submit" name="VersionsButton" value="{'Manage'|i18n('design/standard/content/edit')}" />
</div>
</div></div></div></div>
</div>

<div class="drafts">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">
<h4>{"Drafts"|i18n("design/standard/content/edit")}</h4>
</div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<p>
<label>{"Last draft"|i18n("design/standard/content/edit")}:</label>
{section show=$object.modified}
{$object.modified|l10n(date)}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name}
{section-else}
{"Not yet published"|i18n("design/standard/content/edit")}
{/section}
</p>

<div class="block">
<input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('design/standard/content/edit')}" />
</div>

<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'Store'|i18n('design/standard/content/edit')}" />
<input class="button" type="submit" name="Exit" value="{'#%@* it!'|i18n('design/standard/content/edit')}" />
</div>

</div></div></div></div></div></div>

</div>

{*
<!-- Dummy link tool START -->
<div class="linktool">
<h4>Internal link tool</h4>
<p>
<input class="button" type="submit" value="Create link code" />
<input class="linkbox" type="text" readonly="readonly" value="&lt;link id=123 /&gt;" />
</p>
</div>
<!-- Dummy link tool END -->
*}
