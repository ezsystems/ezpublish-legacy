<form action={concat("/content/translate/",$object.id,"/",$edit_version)|ezurl} method="post"  enctype="multipart/form-data" >

<div class="maincontentheader">
<h1>{"Translating '%1'"|i18n("design/standard/content/translate",,array($object.name|wash))}</h1>
</div>

{section show=$validation.processed}
    {section name=UnvalidatedAttribute loop=$validation.attributes show=$validation.attributes}

    <div class="warning">
    <h2>{"Input did not validate"|i18n("design/standard/content/edit")}</h2>
    <ul>
    	<li>{$:item.name|wash}: {$:item.description}</li>
    </ul>
    </div>

    {section-else}

    <div class="feedback">
    <h2>{"%1 input was stored successfully"|i18n("design/standard/content/translate",,array($validation.locale.intl_language_name))}</h2>
    </div>

    {/section}
{/section}

{section show=$is_remove_active}
<!-- Translation removal start -->

<p>
{"Remove the following translations from '%1'"|i18n("design/standard/content/translate",,array($object.name|wash))}?
</p>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th width="10%">{"Locale"|i18n("design/standard/content/translate")}</th>
    <th width="90%" colspan="2">{"Language"|i18n("design/standard/content/translate")}</th>
</tr>

{section name=Language loop=$remove_language_list sequence=array("bglight","bgdark")}
<tr>
    <td class="{$Language:sequence}">
    {$Language:item.language_code}
    </td>
    <td class="{$Language:sequence}">
    {section show=$Language:item.locale.is_valid}
    {$Language:item.locale.intl_language_name}
    {section-else}
    {"(No locale information available)"|i18n("design/standard/content/translate")}
    {/section}
    </td>
    <td class="{$Language:sequence}">
    <input type="hidden" name="RemoveLanguageArray[]" value="{$Language:item.language_code}" />
    </td>
</tr>
{/section}

</table>

<input type="hidden" name="TranslationLanguageEdit" value="{$translation_language}" />

<div class="buttonblock">
<input class="button" type="submit" name="RemoveLanguageConfirmationButton" value="{'Yes'|i18n('design/standard/content/translate')}" />
<input class="button" type="submit" name="RemoveLanguageCancelButton" value="{'No'|i18n('design/standard/content/translate')}" />
</div>

<!-- Translation removal end -->

{section-else}

{let name=Translation
     translation_list=fetch('content','non_translation_list',hash('object_id',$object.id,'version',$edit_version))}
{section show=$Translation:translation_list}

<div class="block">
<label>{"Translate into"|i18n("design/standard/content/translate")}</label><div class="labelbreak"></div>
<select name="SelectedLanguage" >
  {section loop=$Translation:translation_list}
<option value="{$Translation:item.locale_code}">{$Translation:item.intl_language_name}</option>
  {/section}
</select><input class="button" type="submit" name="AddLanguageButton" value="{'Add'|i18n('design/standard/content/translate')}" />
</div>

{/section}
{/let}

{let name=Translation
     language_index=0
     translation_list=$content_version.translation_list}
{section show=$Translation:translation_list}

<h2>{"Translations"|i18n("design/standard/content/translate")}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th width="10%">{"Locale"|i18n("design/standard/content/translate")}</th>
    <th width="86%">{"Language"|i18n("design/standard/content/translate")}</th>
    <th></th>
    <th></th>
</tr>

{section loop=$Translation:translation_list}
    {section show=eq($translation_language,$Translation:item.language_code)}
        {set language_index=$Translation:index}
    {/section}
{/section}

{section loop=$Translation:translation_list sequence=array("bglight","bgdark")}
<tr>
    <td class="{$Translation:sequence}">
    {$Translation:item.language_code}
    </td>
    <td class="{$Translation:sequence}">
    {section show=$Translation:item.locale.is_valid}
        {$Translation:item.locale.intl_language_name}
    {section-else}
        {"(No locale information available)"|i18n("design/standard/content/translate")}
    {/section}
    </td>
    <td class="{$Translation:sequence}">
    <input type="radio" name="EditSelectedLanguage" value="{$Translation:item.language_code}" {section show=eq($Translation:index,$Translation:language_index)}checked="checked"{/section} />
    </td>
    <td class="{$Translation:sequence}">
    <input type="checkbox" name="RemoveLanguageArray[]" value="{$Translation:item.language_code}" />
    </td>
</tr>
{/section}
<tr>
    <td></td>
    <td></td>
    <td width="2%"><input type="submit" name="EditLanguageButton" value="{'Translate'|i18n('design/standard/content/translate')}" /></td>
    <td width="2%">{include uri="design:gui/delete.tpl"}</td>
</tr>
</table>

{/section}

{/let}

{section show=$translation_language}

<input type="hidden" name="TranslationLanguageEdit" value="{$translation_language}" />

<table class="layout" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="50%"><h3>{$translation_locale.intl_language_name} ({$translation_locale.locale_code})</h3></td>
    <td width="50%"><h3>{$original_locale.intl_language_name} ({$original_locale.locale_code})</h3></td>
</tr>

{section name=ContentAttribute loop=$content_attributes}
{section-exclude match=$content_attribute_map[$ContentAttribute:item.contentclassattribute_id].contentclass_attribute.data_type.properties.translation_allowed|not}

<tr>
    <td>
    <label>{$ContentAttribute:item.contentclass_attribute.name|wash}:</label><div class="labelbreak"></div>
    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentAttribute:item.id}" />
    </td>
    <td>
    <label>{$ContentAttribute:item.contentclass_attribute.name|wash}:</label><div class="labelbreak"></div>
    </td>
</tr>
<tr>
    </td>
  <!-- Translated attributes start -->
    <td>

    {let translation=$content_attribute_map[$ContentAttribute:item.contentclassattribute_id]}
        {section show=$ContentAttribute:translation}
            {* Only show edit GUI if the attribute is translateable *}
            {section show=and(eq($ContentAttribute:translation.contentclass_attribute.can_translate,0),
                              ne($object.default_language,$ContentAttribute:translation.language_code) ) }
               {attribute_view_gui attribute=$ContentAttribute:translation}
            {section-else}
               {attribute_edit_gui attribute=$ContentAttribute:translation}
            {/section}
        {/section}
    {/let}

  </td>
  <!-- Translated attributes end -->

  <!-- Original attributes start -->
  <td valign="top">
    {attribute_view_gui attribute=$ContentAttribute:item}<br />
  </td>
  <!-- Original attributes end -->
</tr>

{/section}

</table>

<div class="buttonblock">
<input class="button" type="submit" name="StoreButton" value="{'Store'|i18n('design/standard/content/translate')}" />
<input class="button" type="submit" name="EditObjectButton" value="{'Edit'|i18n('design/standard/content/translate')}" />
</div>

{section-else}

<div class="buttonblock">
<input class="button" type="submit" name="EditObjectButton" value="{'Edit'|i18n('design/standard/content/translate')}" />
</div>

{/section}

{/section}

</form>
