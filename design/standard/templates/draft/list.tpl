<form action={concat("draft/list")|ezurl} method="post" >

<h1>{"My drafts"|i18n('draft/list')}</h1>

<table width="100%" cellspacing="0" >
<tr>
  <th align="left">ID</th>
  <th align="left">Name</th>
  <th align="left">Version</th>
  <th align="left">edit</th>
  <th align="left">Remove</th>
</tr>

{section name=Draft loop=$draft_list sequence=array(bglight,bgdark)}
<tr>
  <td width="10%" class="{$Draft:sequence}">{$Draft:item.contentobject.id}</td>
  <td class="{$Draft:sequence}">
    <a href={concat("/content/versionview/",$Draft:item.contentobject.id,"/",$Draft:item.version,"/")|ezurl}>
    {$Draft:item.contentobject.name}</a>
  </td>
  <td class="{$Draft:sequence}">
    {$Draft:item.version}
  </td>
  <td class="{$Draft:sequence}">
    <a href={concat("/content/edit/",$Draft:item.contentobject.id,"/",$Draft:item.version,"/")|ezurl}><img src={"edit.png"|ezimage} border="0"></a>
  </td>
  <td class="{$Draft:sequence}">
    <input type="checkbox" name="DeleteIDArray[]" value="{$Draft:item.id}" />
    <img src={"editdelete.png"|ezimage} border="0">
  </td>
</tr>
{/section}
<tr>
</table>

<br/>

<input type="submit" name="RemoveButton" value="Remove draft" />
