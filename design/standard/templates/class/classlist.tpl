{*?template charset=iso-8859-1 ?*}
<form action={concat($module.functions.classlist.uri,"/",$GroupID)|ezurl} method="post" name="ClassList">
{switch name=Sw1 match=$count}
  {case match=0}
  <h3>{"No classes have been defined for "|i18n('content/class')}{$group_name}.</h3>
  <h3>{"Click on 'New Class' button to creat a class."}</h3>
  {/case}
  {case}
      {switch name=Sw2 match=$class_count}
      {case match=0}
      <h3>{"No classes have been defined for "|i18n('content/class')}{$group_name}.</h3>
      {/case}
      {case}
      <h1>{"Defined class types for "|i18n('content/class')}{$group_name} </h1>
      {/case}
      {/switch}
  {/case}
{/switch}
{section show=$groupclasses}
<table width="100%" cellspacing="0">
<tr>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/id")|ezurl}>{"ID"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/name")|ezurl}>{"Name"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/identifier")|ezurl}>{"Identifier"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/status")|ezurl}>{"Status"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/creator")|ezurl}>{"Creator"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/modifier")|ezurl}>{"Modifier"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/created")|ezurl}>{"Created"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/modified")|ezurl}>{"Modified"|i18n('content/class')}</a></th>
</tr>
{section name=Classes loop=$groupclasses sequence=array(bglight,bgdark)}
<tr>
  <td class="{$Classes:sequence}" width="3%">{$Classes:item.id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.name}</td>
  <td class="{$Classes:sequence}">{$Classes:item.identifier}</td>
  <td class="{$Classes:sequence}">{$Classes:item.version_status|choose("Temporary","Defined","Modified")}</td>
  <td class="{$Classes:sequence}">{$Classes:item.creator_id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.modifier_id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.created|l10n(shortdatetime)}</td>
  <td class="{$Classes:sequence}">{$Classes:item.modified|l10n(shortdatetime)}</td>
  <td class="{$Classes:sequence}" width="1%"><a href={concat($module.functions.edit.uri,"/",$Classes:item.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
  <td class="{$Classes:sequence}" width="1%"><input type="checkbox" name="ContentClass_id_checked[]" value="{$Classes:item.id}"></td>
</tr>
{/section}
</table>
{/section}
{section show=$temp_groupclasses}
<br/>
<br/>
<h3>{"Temporary class types for "|i18n('content/class')}{$group_name} </h3> 
<p>{"Items are sorted by modification date."|i18n('content/class')}
</p>
<table width="100%" cellspacing="0">
<tr>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/id")|ezurl}>{"ID"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/name")|ezurl}>{"Name"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/identifier")|ezurl}>{"Identifier"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/status")|ezurl}>{"Status"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/creator")|ezurl}>{"Creator"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/modifier")|ezurl}>{"Modifier"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/created")|ezurl}>{"Created"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.classlist.uri,"/",$GroupID,"/modified")|ezurl}>{"Modified"|i18n('content/class')}</a></th>
</tr>
{section name=TempClasses loop=$temp_groupclasses sequence=array(bglight,bgdark)}
<tr>
  <td class="{$TempClasses:sequence}" width="1%">{$TempClasses:item.id}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.name}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.identifier}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.version_status|choose("Temporary","Defined","Modified")}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.creator_id}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.modifier_id}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.created|l10n(shortdatetime)}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.modified|l10n(shortdatetime)}</td>
  <td class="{$TempClasses:sequence}" width="1%"><a href={concat($module.functions.edit.uri,"/",$TempClasses:item.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
  <td class="{$TempClasses:sequence}" width="1%"><input type="checkbox" name="TempContentClass_id_checked[]" value="{$TempClasses:item.id}"></td>
</tr>
{/section}
</table>
{/section}

<table width="100%">
<tr>
  <td width="99%"></td>
  <td>{include uri="design:gui/button.tpl" name=new id_name=NewButton value="New Class"|i18n('content/class')}</td>
{switch name=Sw match=$count}
  {case match=0}
  {/case}
  {case}
  <td>{include uri="design:gui/button.tpl" name=delete id_name=DeleteButton value="Delete"|i18n('content/class')}</td>
  {/case}
{/switch}
</tr>
</table>
<input type="hidden" name = "CurrentGroupID" value="{$GroupID}">
<input type="hidden" name = "CurrentGroupName" value="{$group_name}">
</form>