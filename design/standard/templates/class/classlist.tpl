<form action={concat("class/classlist/",$GroupID)|ezurl} method="post" name="ClassList">

{switch name=Sw1 match=$count}
  {case match=0}
    <div class="warning">
    <h2>{"No classes have been defined for "|i18n('content/class')}{$group_name}.</h2>
    <p>{"Click on 'New Class' button to creat a class."}</p>
    </div>
  {/case}
  {case}
      {switch name=Sw2 match=$class_count}
      {case match=0}
        <div class="warning">
        <h2>{"No classes have been defined for "|i18n('content/class')}{$group_name}.</h2>
        </div>
      {/case}
      {case}
        <div class="maincontentheader">
        <h1>{"Defined class types for "|i18n('content/class')}{$group_name}</h1>
        </div>
      {/case}
      {/switch}
  {/case}
{/switch}
{section show=$groupclasses}
<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{"ID"|i18n('content/class')}:</th>
    <th>{"Name"|i18n('content/class')}:</th>
    <th>{"Identifier"|i18n('content/class')}:</th>
    <th>{"Modifier"|i18n('content/class')}:</th>
    <th>{"Modified"|i18n('content/class')}:</th>
</tr>

{section name=Classes loop=$groupclasses sequence=array(bglight,bgdark)}
<tr>
    <td class="{$Classes:sequence}" width="1%">{$Classes:item.id}</td>
    <td class="{$Classes:sequence}">{$Classes:item.name}</td>
    <td class="{$Classes:sequence}">{$Classes:item.identifier}</td>
    <td class="{$Classes:sequence}">{content_view_gui view=text_linked content_object=$Classes:item.modifier.contentobject}</td>
    <td class="{$Classes:sequence}"><span class="small">{$Classes:item.modified|l10n(shortdatetime)}</span></td>
    <td class="{$Classes:sequence}" width="1%"><div class="listbutton"><a href={concat($module.functions.edit.uri,"/",$Classes:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="edit" /></a></div></td>
    <td class="{$Classes:sequence}" width="1%"><input type="checkbox" name="ContentClass_id_checked[]" value="{$Classes:item.id}"></td>
</tr>
{/section}

</table>
{/section}

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=new id_name=NewButton value="New"|i18n('content/class')}
{switch name=Sw match=$count}
  {case match=0}
  {/case}
  {case}
  {include uri="design:gui/button.tpl" name=delete id_name=DeleteButton value="Remove"|i18n('content/class')}
  {/case}
{/switch}
</div>

<input type="hidden" name = "CurrentGroupID" value="{$GroupID}" />
<input type="hidden" name = "CurrentGroupName" value="{$group_name}" />

</form>