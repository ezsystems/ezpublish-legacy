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

{section show=$class_count}

<div class="context-block">
<h2 class="context-title"><a href={'/class/grouplist'|ezurl}><img src={'back-button-16x16.gif'|ezimage} alt="{'Back to class groups'|i18n( 'design/admin/class/classlist' )}" title="{'Back to class groups'|i18n( 'design/standard/node/view' )}" /></a>&nbsp;{'Classes inside'|i18n( 'design/admin/class/classlist' )} {$group_name|wash} [{$class_count}]</h2>

<form action={concat( 'class/classlist/', $GroupID )|ezurl} method="post" name="ClassList">
<table class="list" cellspacing="0">
{section show=$groupclasses}
<tr>
    <th class="tight"><a href="" onclick="toggleCheckboxes( document.ClassList, 'DeleteIDArray[]' ); return false;"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" title="{'Invert selection.'|i18n( 'design/admin/class/classlist' )}" /></a></th>
    <th>{'Name'|i18n('design/admin/class/classlist')}</th>
    <th>{'ID'|i18n('design/admin/class/classlist')}</th>
    <th>{'Identifier'|i18n('design/admin/class/classlist')}</th>
    <th>{'Modifier'|i18n('design/admin/class/classlist')}</th>
    <th>{'Modified'|i18n('design/admin/class/classlist')}</th>
    <th>{'Objects'|i18n('design/admin/class/classlist')}</th>
    <th>{'Edit'|i18n('design/admin/class/classlist')}</th>
    <th>{'Copy'|i18n('design/admin/class/classlist')}</th>
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
    <td><a href={concat("class/edit/",$Classes:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="edit" /></a></td>
    <td><a href={concat("class/copy/",$Classes:item.id)|ezurl}><img class="button" src={"copy.gif"|ezimage} width="16" height="16" alt="edit" /></a></td>
</tr>
{/section}
{/section}
</table>

<div class="controlbar">
<div class="block">
<input type="hidden" name = "CurrentGroupID" value="{$GroupID}" />
<input type="hidden" name = "CurrentGroupName" value="{$group_name}" />
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/class/classlist' )}" />
<input class="button" type="submit" name="NewButton" value="{'New class'|i18n( 'design/admin/class/classlist' )}" />
</div>
</div>

</form>

</div>

{section-else}

<div class="feedback">
    <h2>{"No classes in "|i18n('design/admin/class/classlist')}{$group_name|wash}.</h2>
    <p>{"Click the 'New class' button to create a class."|i18n( 'design/admin/class/classlist' )}</p>
</div>
<form action={concat( 'class/classlist/', $GroupID )|ezurl} method="post" name="ClassList">
<input type="hidden" name = "CurrentGroupID" value="{$GroupID}" />
<input type="hidden" name = "CurrentGroupName" value="{$group_name}" />
<input class="button" type="submit" name="NewButton" value="{'New class'|i18n( 'design/admin/class/classlist' )}" />
</form>

{/section}
