<h1>{"Translations list"|i18n("design/standard/content")}</h1>

<form action={$module.functions.translations.uri|ezurl} method="post" >


<h4>{"Translations editing"|i18n("design/standard/content")}</h4>


<table cellspacing="0">
<tr>
	<th align="left">{"Translation Name"|i18n("design/standard/content")}</th>
	<th align="left">{"Locale"|i18n("design/standard/content")}</th>
        <th width="1%" align="right">{"Remove:"|i18n("design/standard/content")}</th>

</tr>


{section name=Translation loop=$existing_translations sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Translation:sequence}">{$Translation:item.name}</td>
	<td class="{$Translation:sequence}">{$Translation:item.locale}</td>
	<td class="{$Translation:sequence}" align="right" >
           <input type="checkbox" name="DeleteIDArray[]" value="{$Translation:item.id}" />
        </td>  


</tr>
{/section}

</table>
<input type="submit" name="RemoveButton" value="{'Remove'|i18n('design/standard/content')}" />
<input type="submit" name="NewButton" value="{'New'|i18n('design/standard/content')}" />



</form>
