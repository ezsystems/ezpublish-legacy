<h1>Translation</h1>

<form action={concat("/content/translate/",$object.id,"/",$edit_version,"/")|ezurl} method="post">

<select name="TranslateToLanguage" >
  {section name=Translation loop=fetch('content','translation_list')}
<option value="{$Translation:item.locale_code}">{$Translation:item.intl_language_name}</option>
  {/section}
</select>
<input type="submit" name="SelectLanguageButton" value="{'Select'|i18n('content/object')}" />

<h2>Available translations</h2>
<table width="100%" cellspacing="0" cellpadding="0" border="1">
<tr>
  <th width="98%">Language</th>
  <th width="2%"><input type="submit" name="RemoveLanguageButton" value="{'Remove'|i18n('content/object')}" /></th>
</tr>
{section name=TranslationLocale loop=$content_version.translation_list}
<tr>
  <td>
  {section show=$TranslationLocale:item.locale.is_valid}
    {$TranslationLocale:item.locale.intl_language_name}
  {section-else}
    No locale information for {$TranslationLocale:item.locale.locale_code}
  {/section}
  </td>
  <td>
    <input type="checkbox" name="RemoveLanguageArray[]" value="{$TranslationLocale:item.language_code}" />
  </td>
</tr>
{/section}
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
  <th width="50%">Translation</th>
  <th width="50%">Original</th>
</tr>

{section name=ContentAttribute loop=$content_attributes sequence=array('bglight','bgdark')}
<tr>
  <td>
    <label>{$ContentAttribute:item.contentclass_attribute.name}:</label><div class="labelbreak"></div>
    <input type="hidden" name="ContentAttribute_id[]" value="{$ContentAttribute:item.id}" />
  </td>
  <td>
    <label>{$ContentAttribute:item.contentclass_attribute.name}:</label><div class="labelbreak"></div>
  </td>
</tr>
<tr>
  </td>
  <!-- Translated attributes start -->
  <td>
  {let translation=$content_attribute_map[$ContentAttribute:item.contentclassattribute_id]}
    {section show=$ContentAttribute:translation}
      {attribute_edit_gui attribute=$ContentAttribute:translation}<br />
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
<br />

<input type="submit" name="StoreButton" value="Store" />
<input type="submit" name="BackButton" value="Back" />
</form>
