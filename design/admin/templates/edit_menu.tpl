<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/edit' )}</h4>

</div></div></div></div></div></div>

<div class="box-ml"><div class="box-mr"><div class="box-content">
<p>
<label>{'ID'|i18n( 'design/admin/content/edit' )}:</label>
{$object.id}
</p>

<p>
<label>{'Created'|i18n( 'design/admin/content/edit' )}:</label>
{section show=$object.published}
{$object.published|l10n( shortdatetime )}<br />
{$object.current.creator.name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/edit' )}
{/section}
</p>
<p>
<label>{'Last Modified'|i18n( 'design/admin/content/edit' )}:</label>
{section show=$object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/edit' )}
{/section}
</p>

<p>
<label>{'Published version'|i18n( 'design/admin/content/edit' )}:</label>
{section show=$object.published}
{$object.current_version}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/edit' )}
{/section}
</p>
<p>
<label>{'Editing version'|i18n( 'design/admin/content/edit' )}:</label>
{$edit_version}
</p>

<div class="block">
<input class="button" type="submit" name="VersionsButton" value="{'Manage versions'|i18n( 'design/admin/content/edit' )}" />
</div>

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
<h4>{'Translations [%translations_count]'|i18n( 'design/admin/content/edit',, hash( '%translations_count', $Translation:translation_list|count ) )}</h4>
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
<img src={$Translation:item.language_code|flag_icon} alt="($Translation:item.language_code)" style="vertical-align: middle;" /> {$Translation:item.locale.intl_language_name|shorten(16)}
{section-else}
{'%1 (No locale information available)'|i18n( 'design/admin/content/edit',, array($Translation:item.language_code))}
{/section}
</label>
</p>
{/section}
<div class="block">
<input class="button" type="submit" name="EditLanguageButton" value="{'Edit selected'|i18n( 'design/admin/content/edit' )}" {section show=$Translation:other_translation_list|not}disabled="disabled"{/section} />
<input class="button" type="submit" name="TranslateButton" value="{'Manage translations'|i18n('design/admin/content/edit' )}" />
</div>
{/section}

{/let}
</div></div></div></div>
</div>

<!-- Translation box end-->


<div class="drafts">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">
<h4>{'Current draft'|i18n( 'design/admin/content/edit' )}</h4>
</div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
<p>
<label>{'Created'|i18n( 'design/admin/content/edit' )}:</label>
{$content_version.created|l10n( shortdatetime )}<br />
{$content_version.creator.name}
</p>
<p>
<label>{'Last modified'|i18n( 'design/admin/content/edit' )}:</label>
{$content_version.modified|l10n( shortdatetime )}<br />
{$content_version.creator.name}
</p>




<div class="block">
<input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('design/admin/content/edit')}" />
</div>
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'Store'|i18n('design/standard/content/edit')}" />
<input class="button" type="submit" name="StoreExitButton" value="{'Store and exit'|i18n('design/standard/content/edit')}" />
</div>

</div></div></div></div></div></div>

</div>

