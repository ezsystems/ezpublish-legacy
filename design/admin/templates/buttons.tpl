<div class="controlbar">
<div class="editblock">
<form method="post" action={"content/action"|ezurl}>
<input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

{section show=$node.object.can_edit}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" title="{'Click here to edit the item that is being displayed above.'|i18n( 'design/admin/layout' )}" />
{section-else}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" title="{'You do not have permissions to edit the item that is being displayed above.'|i18n( 'design/admin/layout' )} "disabled="disabled" />
{/section}

{* Allow remove button if this is not a root node and if the user is allowed to remove it: *}
{section show=$node.object.can_remove|not}
<input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/standard/node/view')}" title="{'You do not have permissions to remove the item that is being displayed above.'|i18n( 'design/admin/layout' )}"disabled="disabled" />
{section-else}
<input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/standard/node/view')}" title="{'Click here to remove the item that is being displayed above.'|i18n( 'design/admin/layout' )}" />
{/section}

{* The preview button has been commented out. Might be absent until better preview functionality is implemented. *}
{* <input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/standard/node/view')}" /> *}

</form>
</div>
{section show=eq( ezpreference( 'admin_more' ), 'on' )}
<a href={'/user/preferences/set/admin_more/off'|ezurl}>Hide advanced shit</a>
<table class="list">
<tr>
<th>Created</th>
<th>Last modified</th>
<th>Versions</th>
{*<th>Translations</th>*}
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
{*<td>{$node.contentobject_version_object.language_list|count}</td>*}
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
    <th>Sorting</th>
    <th class="main">{'Main'|i18n( 'design/admin/location' )}</th>
</tr>
{section var=assignment loop=$assigned_nodes}
    {let assignment_node=$assignment.node
     assignment_path=$assignment_node.path|append( $assignment_node )}
<tr class="bgdark">
    <td class="delete"><input type="checkbox" name="AssignmentIDSelection[]" {section show=or( $assignment_node.can_remove|not, eq( $assignment.parent_node, $node.parent_node_id ) )}disabled="disabled"{/section} value="{$assignment.id}" /></td>
    <td class="path">{section var=node_path loop=$assignment_path}<a href={$node_path.url|ezurl}>{$node_path.name|wash}</a>{delimiter} / {/delimiter}{/section}</td>
    <td>{$assignment.item.node.sort_array[0][0]} / {$assignment.item.node.sort_array[0][1]|choose( 'up'|i18n( 'design/admin/locations' ), 'down'|i18n( 'design/admin/locations' ) )}</td>
    <td class="main"><input type="radio" {section show=ne( $assignment.is_main, 0 )}checked="checked"{/section} name="MainAssignmentCheck" {section show=or( $assignment_node.can_edit|not, $assignment_count|le( 1 ) )}disabled="disabled"{/section} value="{$assignment_node.node_id}" /></td>
</tr>
{/let}
{/section}
</table>

{* Required to get the main node selection to work,
   unchecked radiobuttons will not be sent by browser. *}
<input type="hidden" name="HasMainAssignment" value="1" />

<div class="button-left">
    <input class="button" type="submit" name="RemoveAssignmentButton" value="{'Remove selected'|i18n( 'design/admin/location' )}" {section show=$assignment_count|le( 1 )}disabled="disabled"{/section} />
    <input class="button" type="submit" name="AddAssignmentButton" value="{'Add new'|i18n( 'design/admin/location' )}" />
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
<a href={'/user/preferences/set/admin_more/on'|ezurl}>Show advanced shit</a>
{/section}


</div>
