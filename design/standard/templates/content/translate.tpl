<h1>Object translation</h1>

<form action={concat("/content/translate/",$object.id,"/",$edit_version,"/")|ezurl} method="post">

<select name="TranslateToLanguage" >
<option value="no_NO">nor-NO</option>
</select>
<input type="submit"  name="SelectLanguageButton" value="{"Select"|i18n}" />

<br />
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	{section name=ContentAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
<tr>
	<td class="{$ContentAttribute:sequence}">
	<h2>{$ContentAttribute:item.contentclass_attribute.name}</h2>
	<input type="hidden" name="ContentAttribute_id[]" value="{$ContentAttribute:item.id}" />
	<b>Original:</b><br />
	{attribute_view_gui attribute=$ContentAttribute:item}<br />
	<b>Translation:</b><br />
	{section name=ContentAttributeLanguage loop=$content_attributes_language sequence=array(bglight,bgdark)}
	{switch name=SW match=$ContentAttribute:item.contentclassattribute_id}
	{case  match=$ContentAttribute:ContentAttributeLanguage:item.contentclassattribute_id}	
	{attribute_edit_gui attribute=$ContentAttribute:ContentAttributeLanguage:item}<br />
	{/case}
	{case}
	{/case}
	{/switch}
	{/section}
	</td>
</tr>
	{/section}
</table>
<br />

<input type="submit" name="StoreButton" value="Store" />
<input type="submit" name="BackButton" value="Back" />
</form>
