{let page_limit=30
     list_count=fetch('content','trash_count')}
<form action={concat("content/trash/")|ezurl} method="post" >

<div class="maincontentheader">
<h1>{"Trash"|i18n("design/standard/content/trash")}</h1>
</div>

{let object_list=fetch('content','trash_object_list',hash(limit,$page_limit,offset,$view_parameters.offset))}
{section show=$object_list}

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{"Name:"|i18n("design/standard/content/trash")}</th>
    <th>{"Version:"|i18n("design/standard/content/trash")}</th>
    <th>{"Restore:"|i18n("design/standard/content/trash")}</th>
    <th>
    <div class="buttonblock">
    <input type="submit" name="RemoveButton" value="{'Remove'|i18n('design/standard/content/trash')}" />
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
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/trash/')
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}


{section-else}

<div class="feedback">
<h2>{"Trash is empty"|i18n("design/standard/content/trash")}</h2>
</div>

{/section}
{/let}
{/let}