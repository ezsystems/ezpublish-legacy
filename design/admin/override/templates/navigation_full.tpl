{* Show the contents of the current node (will probably use an override)*}

<div class="viewbar">
<table>
<tr>
<td>
{$node.object.content_class.identifier|class_icon( normal, $node.object.content_class.name )}
</td>
<td>
Name: {$node.name}
<br />
Type: {$node.object.class_name}<br />
</td>
</tr>
</table>


{section show=eq( ezpreference( 'admin_more' ), 'on' )}
<a href={'/user/preferences/set/admin_more/off'|ezurl}>Hide</a>
<table>
<tr>
<th>Created</th>
<th>Last modified</th>
<th>Versions</th>
<th>Related items</th>
<th>Used by</th>
<th>Section</th>
<th>Node ID</th>
<th>Object ID</th>
</tr>
<tr>
<td>{$node.object.owner.name} <br />({$node.object.published|l10n(shortdatetime)})</td>
<td>{$node.object.current.creator.name}<br /> ({$node.object.modified|l10n(shortdatetime)})</td>
<td>{$node.object.versions|count()}</td>
<td>{$node.object.related_contentobject_count}</td>
<td>{$node.object.reverse_related_contentobject_count}</td>
<td>{$node.object.section_id}</td>
<td>{$node.node_id}</td>
<td>{$node.object.id}</td>
</tr>
</table>


{* Show node assignment controls if there are more than one assignment or advanced mode is used *}
{let assigned_nodes=$node.object.current.node_assignments
     assignment_count=$assigned_nodes|count}
{section show=and( $node.can_edit, or( true(), $assigned_nodes|count|gt( 1 ) ) )}
<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="{$viewmode|wash}" />
<input type="hidden" name="ContentObjectLanguageCode" value="{$language_code|wash}" />

<div class="locationblock">

<table class="list" cellspacing="0">
<tr>
    <th class="delete">&nbsp;</th>
    <th class="path">{'Location'|i18n( 'design/admin/location' )}</th>
    <th class="main">{'Main'|i18n( 'design/admin/location' )}</th>
</tr>
{section var=assignment loop=$assigned_nodes}
{let assignment_node=$assignment.node
     assignment_path=$assignment_node.path|append( $assignment_node )}
<tr class="bgdark">
    <td class="delete"><input type="checkbox" name="AssignmentIDSelection[]" {section show=or( $assignment_node.can_remove|not, eq( $assignment.parent_node, $node.parent_node_id ) )}disabled="disabled"{/section} value="{$assignment.id}" /></td>
    <td class="path">{section var=node_path loop=$assignment_path}<a href={$node_path.url|ezurl}>{$node_path.name|wash}</a>{delimiter} / {/delimiter}{/section}</td>
    <td class="main"><input type="radio" {section show=ne( $assignment.is_main, 0 )}checked="checked"{/section} name="MainAssignmentCheck" {section show=or( $assignment_node.can_edit|not, $assignment_count|le( 1 ) )}disabled="disabled"{/section} value="{$assignment_node.node_id}" /></td>
</tr>
{/let}
{/section}
</table>

{* Required to get the main node selection to work,
   unchecked radiobuttons will not be sent by browser. *}
<input type="hidden" name="HasMainAssignment" value="1" />

<div class="button-left">
    <input class="button" type="submit" name="RemoveAssignmentButton" value="{'Delete location'|i18n( 'design/admin/location' )}" {section show=$assignment_count|le( 1 )}disabled="disabled"{/section} />
    <input class="button" type="submit" name="AddAssignmentButton" value="{'Add location'|i18n( 'design/admin/location' )}" />
</div>

<div class="button-right">
    <input class="button" type="submit" name="UpdateMainAssignmentButton"" value="{'Update main'|i18n( 'design/admin/location' )}" {section show=$assignment_count|le( 1 )}disabled="disabled"{/section} />
</div>

<div class="break"></div>
</div>

</form>
{/section}
{/let}
{section-else}
<a href={'/user/preferences/set/admin_more/on'|ezurl}>Show more info</a>
{/section}

</div>

<div class="mainobject-vindow" title="{$node_name|wash} [{'Folder'|i18n('design/admin/node/view')}], {'Node ID'|i18n( 'design/standard/node/view' )}: {$node.node_id}, {'Object ID'|i18n( 'design/standard/node/view' )}: {$node.object.id}">
{* Show the actual contents of the object. *}
{node_view_gui content_node=$node view=navigation}

{* Show related objects. *}
{* include uri="design:related_objects.tpl" *}
</div>

{* Buttons for remove/edit/etc. and the location interface. *}
{include uri="design:buttons.tpl"}

{* List of children. *}
<div class="content-view-children">
{include uri="design:children.tpl"}
</div>
