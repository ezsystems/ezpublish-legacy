<form action={concat("content/archive/")|ezurl} method="post" >

<div class="maincontentheader">
<h1>{"Archive"|i18n("design/standard/content/view")}</h1>
</div>

{section show=$object_list}

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{"Name:"|i18n("design/standard/content/view")}</th>
    <th>{"Version:"|i18n("design/standard/content/view")}</th>
    <th>{"Restore:"|i18n("design/standard/content/view")}</th>
    <th>
    <div class="buttonblock">
    <input type="submit" name="RemoveButton" value="{'Remove'|i18n('design/standard/content/view')}" />
    </div>
    </th>
</tr>

{section name=Draft loop=$object_list sequence=array(bglight,bgdark)}
<tr>
    <td class="{$Draft:sequence}">
    <a href={concat("/content/versionview/",$Draft:item.id,"/",$Draft:item.current_version,"/")|ezurl}>
    {$Draft:item.name}</a>
    </td>
    <td class="{$Draft:sequence}">
    {$Draft:item.current_version}
    </td>
    <td class="{$Draft:sequence}">
    <a href={concat("/content/edit/",$Draft:item.id,"/" )|ezurl}><img src={"edit.png"|ezimage} border="0"></a>
    </td>
    <td class="{$Draft:sequence}">
    <input type="checkbox" name="DeleteIDArray[]" value="{$Draft:item.id}" />
  </td>
</tr>
{/section}
<tr>
</table>


{section-else}

<div class="feedback">
<h2>{"Archive is empty"|i18n("design/standard/content/view")}</h2>
</div>

{/section}
