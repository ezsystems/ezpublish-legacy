<h1>{"New translation for content"|i18n("design/standard/content")}</h1>

<form action={concat("content/translations")|ezurl} method="post" >

<p>{"Pick one of the translations from the list to add or enter a new custom one in the input fields."|i18n("design/standard/content")}</p>

<table cellspacing="0" cellpadding="0">
<tr>
  <td valign="top">

<div class="textblock">
<label>{"Translations"|i18n("design/standard/content")}</label><div class="break"/>
<select name="LocaleID">
  <option value="-1">{"Custom"|i18n("design/standard/content")}</option>
  {section loop=fetch("content","locale_list")}
  <option value="{$:item.locale_full_code}">
  {$:item.language_name}{section show=$:item.country_variation} ({$:item.language_comment}){/section}<br/>
  </option>
  {/section}
</select>
</div>

  </td>

  <td>

<div class="textblock">
<label>{"Name of translation"|i18n("design/standard/content")}</label><div class="break"/>
<input type="edit" name="TranslationName" value=""  size="20" /><br/>
</div>

<div class="textblock">
<label>{"Locale"|i18n("design/standard/content")}</label><div class="break"/>
<input type="text" name="TranslationLocale" value="" size="8" /><br/>
</div>

  </td>
</tr>
</table>

<div class="buttonblock">
<input class="defaultbutton" type="submit" name="StoreButton" value={"Create"|i18n("design/standard/content")} />
<input class="button" type="submit" name="CancelButton" value={"Cancel"|i18n("design/standard/content")} />
</div>

</form>
