<form action={$module.functions.translations.uri|ezurl} method="post" >

<h1>{"Active translations"|i18n("design/standard/content")}</h1>

<p>{"Below you'll find a list of active translations which content objects may be translated into."|i18n("design/standard/content")}</p>

<table width="100%" cellspacing="0" cellpadding="2">
<tr>
	<th align="left">{"Language name"|i18n("design/standard/content")}</th>
	<th align="left">{"Locale code"|i18n("design/standard/content")}</th>
	<th align="left">{"Edit"|i18n("design/standard/content")}</th>
        <th width="1%" align="right">{"Remove"|i18n("design/standard/content")}</th>
</tr>

{section name=Translation loop=$existing_translations sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Translation:sequence}">{section show=$:item.name}{$:item.name}{section-else}{$:item.locale_object.language_name}{/section}</td>
	<td class="{$Translation:sequence}">{$:item.locale}</td>
	<td class="{$Translation:sequence}" align="right" >
          <input class="button" type="image" src={"edit.png"|ezimage} name="EditButton" value="{'Edit'|i18n('design/standard/content')}" />
        </td>  
	<td class="{$Translation:sequence}" align="right" >
           <input type="checkbox" name="DeleteIDArray[]" value="{$Translation:item.id}" />
        </td>  
</tr>
{/section}
<tr>
  <td colspan="3"><input class="defaultbutton" type="submit" name="NewButton" value="{'New'|i18n('design/standard/content')}" /></td>
  <td align="right"><input class="button" type="image" src={"remove.png"|ezimage} name="RemoveButton" value="{'Remove'|i18n('design/standard/content')}" /></td>
</tr>

</table>

</form>
