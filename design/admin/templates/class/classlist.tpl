{* Generic script for toggling the status of a bunch of checkboxes. *}
{literal}
<script language="JavaScript1.2" type="text/javascript">
<!--
function toggleCheckboxes( formname, checkboxname )
{
    with( formname )
	{
        for( var i=0; i<elements.length; i++ )
        {
            if( elements[i].type == 'checkbox' && elements[i].name == checkboxname && elements[i].disabled == "" )
            {
                if( elements[i].checked == true )
                {
                    elements[i].checked = false;
                }
                else
                {
                    elements[i].checked = true;
                }
            }
	    }
    }
}
//-->
</script>
{/literal}

<div class="context-block">
<h2 class="context-title">{$group.name|classgroup_icon( 'normal', $group.name )}&nbsp;{'%group_name [Class group]'|i18n( 'design/admin/class/classlist',, hash( '%group_name', $group.name ) )|wash}</h2>

<div class="context-information">
<p>{'Last modified'|i18n( 'design/admin/class/classlist' )}: {$group.modified|l10n( shortdatetime )}, {$group_modifier.name}</p>
</div>

<div class="context-attributes">

<div class="block">
<label>ID</label>
{$group.id}
</div>

<div class="block">
<label>Name</label>
{$group.name}
</div>


</div>

<div class="controlbar">
<div class="block">
<form action={'class/grouplist'|ezurl} method="post" name="GroupList">
    <input type="hidden" name="DeleteIDArray[]" value="{$group.id}" />
    <input type="hidden" name="EditGroupID" value="{$group.id}" />
    <input class="button" type="submit" name="EditGroupButton" value="{'Edit'|i18n( 'design/admin/class/classlist' )}">
    <input class="button" type="submit" name="RemoveGroupButton" value="{'Remove'|i18n( 'design/admin/class/classlist' )}">
</form>
</div>
</div>

</div>


<form action={concat( 'class/classlist/', $GroupID )|ezurl} method="post" name="ClassList">

<div class="context-block">
<h2 class="context-title"><a href={'/class/grouplist'|ezurl}><img src={'back-button-16x16.gif'|ezimage} alt="{'Back to class groups'|i18n( 'design/admin/class/classlist' )}" title="{'Back to class groups'|i18n( 'design/standard/node/view' )}" /></a>&nbsp;{'Classes inside <%group_name> [%class_count]'|i18n( 'design/admin/class/classlist',, hash( '%group_name', $group.name, '%class_count', $class_count ) )|wash}</h2>

{section show=$class_count}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" title="{'Invert selection.'|i18n( 'design/admin/class/classlist' )}" onclick="toggleCheckboxes( document.ClassList, 'DeleteIDArray[]' ); return false;" /></th>
    <th>{'Name'|i18n('design/admin/class/classlist')}</th>
    <th>{'ID'|i18n('design/admin/class/classlist')}</th>
    <th>{'Identifier'|i18n('design/admin/class/classlist')}</th>
    <th>{'Modifier'|i18n('design/admin/class/classlist')}</th>
    <th>{'Modified'|i18n('design/admin/class/classlist')}</th>
    <th>{'Objects'|i18n('design/admin/class/classlist')}</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
</tr>

{section name=Classes loop=$groupclasses sequence=array(bglight,bgdark)}
<tr class="{$Classes:sequence}">
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$Classes:item.id}"></td>
    <td>{$Classes:item.identifier|class_icon( small, $Classes:item.name )}&nbsp;<a href={concat( "/class/view/", $Classes:item.id )|ezurl}>{$Classes:item.name|wash}</a></td>
    <td>{$Classes:item.id}</td>
    <td>{$Classes:item.identifier|wash}</td>
    <td>{content_view_gui view=text_linked content_object=$Classes:item.modifier.contentobject}</td>
    <td>{$Classes:item.modified|l10n(shortdatetime)}</td>
    <td>{$Classes:item.object_count}</td>
    <td><a href={concat("class/copy/",$Classes:item.id)|ezurl}><img class="button" src={"copy.gif"|ezimage} width="16" height="16" alt="edit" /></a></td>
    <td><a href={concat("class/edit/",$Classes:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="edit" /></a></td>
</tr>
{/section}
</table>
{section-else}

<p>{'There are no classes in this group.'|i18n( 'design/admin/class/classlist' )}</p>

{/section}


<div class="controlbar">
<div class="block">
<input type="hidden" name = "CurrentGroupID" value="{$GroupID}" />
<input type="hidden" name = "CurrentGroupName" value="{$group.name}" />
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/class/classlist' )}" {section show=$class_count|not}disabled="disabled"{/section} />
<input class="button" type="submit" name="NewButton" value="{'New class'|i18n( 'design/admin/class/classlist' )}" />
</div>
</div>

</div>

</form>



