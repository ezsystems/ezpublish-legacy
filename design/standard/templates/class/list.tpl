<form action={"class/list"|ezurl} method="post" name="ClassEdit">

<div class="maincontentheader">
<h1>{"Defined class types"|i18n('content/class')}</h1>
</div>

<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{"ID"|i18n('content/class')}:</th>
    <th>{"Name"|i18n('content/class')}:</th>
    <th>{"Identifier"|i18n('content/class')}:</th>
    <th>{"Status"|i18n('content/class')}:</th>
    <th>{"Creator"|i18n('content/class')}:</th>
    <th>{"Modifier"|i18n('content/class')}:</th>
    <th>{"Created"|i18n('content/class')}:</th>
    <th>{"Modified"|i18n('content/class')}:</th>
</tr>

{section name=Classes loop=$classes sequence=array(bglight,bgdark)}
<tr>
  <td class="{$Classes:sequence}" width="1%">{$Classes:item.id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.name}</td>
  <td class="{$Classes:sequence}">{$Classes:item.identifier}</td>
  <td class="{$Classes:sequence}">{$Classes:item.version_status|choose("Temporary","Defined","Modified")}</td>
  <td class="{$Classes:sequence}">{$Classes:item.creator_id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.modifier_id}</td>
  <td class="{$Classes:sequence}"><span class="small">{$Classes:item.created|l10n(shortdatetime)}</span></td>
  <td class="{$Classes:sequence}"><span class="small">{$Classes:item.modified|l10n(shortdatetime)}</span></td>
  <td class="{$Classes:sequence}" width="1%"><div class="listbutton"><a href={concat("class/edit/",$Classes:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></div></td>
  <td class="{$Classes:sequence}" width="1%"><input type="checkbox" name="ContentClass_id_checked[]" value="{$Classes:item.id}"></td>
</tr>
{/section}

</table>

{section show=$temp_classes}

<h2>{"Temporary class types"|i18n('content/class')}</h2>
<p>{"Items are sorted by modification date."|i18n('content/class')}</p>

<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{"ID"|i18n('content/class')}:</th>
    <th>{"Name"|i18n('content/class')}:</th>
    <th>{"Identifier"|i18n('content/class')}:</th>
    <th>{"Status"|i18n('content/class')}:</th>
    <th>{"Creator"|i18n('content/class')}:</th>
    <th>{"Modifier"|i18n('content/class')}:</th>
    <th>{"Created"|i18n('content/class')}:</th>
    <th>{"Modified"|i18n('content/class')}:</th>
</tr>

{section name=Classes loop=$temp_classes sequence=array(bglight,bgdark)}
<tr>
<<<<<<< .mine
    <td class="{$Classes:sequence}" width="1%">{$Classes:item.id}</td>
    <td class="{$Classes:sequence}">{$Classes:item.name}</td>
    <td class="{$Classes:sequence}">{$Classes:item.identifier}</td>
    <td class="{$Classes:sequence}">{$Classes:item.version_status|choose("Temporary","Defined","Modified")}</td>
    <td class="{$Classes:sequence}">{$Classes:item.creator_id}</td>
    <td class="{$Classes:sequence}">{$Classes:item.modifier_id}</td>
    <td class="{$Classes:sequence}"><span class="small">{$Classes:item.created|l10n(shortdatetime)}</span></td>
    <td class="{$Classes:sequence}"><span class="small">{$Classes:item.modified|l10n(shortdatetime)}</span></td>
    <td class="{$Classes:sequence}" width="1%"><div class="listbutton"><a href="{$module.functions.edit.uri}/{$Classes:item.id}"><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></div></td>
    <td class="{$Classes:sequence}" width="1%"><input type="checkbox" name="TempContentClass_id_checked[]" value="{$Classes:item.id}"></td>
=======
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
>>>>>>> .r620
</tr>
{/section}

</table>

{/section}

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=new id_name=NewButton value="New Class"|i18n('content/class')}
{include uri="design:gui/button.tpl" name=delete id_name=DeleteButton value="Delete"|i18n('content/class')}
</div>

</form>