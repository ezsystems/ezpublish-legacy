<form action={"class/list"|ezurl} method="post" name="ClassEdit">

<h1>{"Defined class types"|i18n('content/class')}</h1>
<table width="100%" cellspacing="0">
<tr>
  <th align="left">{"ID"|i18n('content/class')}</th>
  <th align="left">{"Name"|i18n('content/class')}</th>
  <th align="left">{"Identifier"|i18n('content/class')}</th>
  <th align="left">{"Status"|i18n('content/class')}</th>
  <th align="left">{"Creator"|i18n('content/class')}</th>
  <th align="left">{"Modifier"|i18n('content/class')}</th>
  <th align="left">{"Created"|i18n('content/class')}</th>
  <th align="left">{"Modified"|i18n('content/class')}</th>
</tr>

{section name=Classes loop=$classes sequence=array(bglight,bgdark)}
<tr>
  <td class="{$Classes:sequence}" width="1%">{$Classes:item.id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.name}</td>
  <td class="{$Classes:sequence}">{$Classes:item.identifier}</td>
  <td class="{$Classes:sequence}">{$Classes:item.version_status|choose("Temporary","Defined","Modified")}</td>
  <td class="{$Classes:sequence}">{$Classes:item.creator_id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.modifier_id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.created|l10n(shortdatetime)}</td>
  <td class="{$Classes:sequence}">{$Classes:item.modified|l10n(shortdatetime)}</td>
  <td class="{$Classes:sequence}" width="1%"><a href={concat("class/edit/",$Classes:item.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
  <td class="{$Classes:sequence}" width="1%"><input type="checkbox" name="ContentClass_id_checked[]" value="{$Classes:item.id}"></td>
</tr>
{/section}

{section show=$temp_classes}

<tr><td colspan="10">
<br/>
<h3>{"Temporary class types"|i18n('content/class')}</h3>
<p>{"Items are sorted by modification date."|i18n('content/class')}
</p>
</td></tr>

<tr>
  <th align="left">{"ID"|i18n('content/class')}</th>
  <th align="left">{"Name"|i18n('content/class')}</th>
  <th align="left">{"Identifier"|i18n('content/class')}</th>
  <th align="left">{"Status"|i18n('content/class')}</th>
  <th align="left">{"Creator"|i18n('content/class')}</th>
  <th align="left">{"Modifier"|i18n('content/class')}</th>
  <th align="left">{"Created"|i18n('content/class')}</th>
  <th align="left">{"Modified"|i18n('content/class')}</th>
</tr>

{section name=Classes loop=$temp_classes sequence=array(bglight,bgdark)}
<tr>
  <td class="{$Classes:sequence}" width="1%">{$Classes:item.id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.name}</td>
  <td class="{$Classes:sequence}">{$Classes:item.identifier}</td>
  <td class="{$Classes:sequence}">{$Classes:item.version_status|choose("Temporary","Defined","Modified")}</td>
  <td class="{$Classes:sequence}">{$Classes:item.creator_id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.modifier_id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.created|l10n(shortdatetime)}</td>
  <td class="{$Classes:sequence}">{$Classes:item.modified|l10n(shortdatetime)}</td>
  <td class="{$Classes:sequence}" width="1%"><a href={concat("class/edit/",$Classes:item.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
  <td class="{$Classes:sequence}" width="1%"><input type="checkbox" name="TempContentClass_id_checked[]" value="{$Classes:item.id}"></td>
</tr>
{/section}
{/section}
</table>

<table width="100%">
<tr>
  <td width="99%"></td>
  <td>{include uri="design:gui/button.tpl" name=new id_name=NewButton value="New Class"|i18n('content/class')}</td>
  <td>{include uri="design:gui/button.tpl" name=delete id_name=DeleteButton value="Delete"|i18n('content/class')}</td>
</tr>
</table>

</form>