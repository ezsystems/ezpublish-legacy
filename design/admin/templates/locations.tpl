{* Locations window. *}
{let assigned_nodes=$node.object.current.node_assignments
     assignment_count=$assigned_nodes|count}

<form name="locationsform" method="post" action={'content/action'|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="{$viewmode|wash}" />
<input type="hidden" name="ContentObjectLanguageCode" value="{$language_code|wash}" />

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Locations [%locations]'|i18n( 'design/admin/node/view/full',, hash( '%locations', $assigned_nodes|count ) )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" title="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" onclick="ezjs_toggleCheckboxes( document.locationsform, 'AssignmentIDSelection[]' ); return false;"/></th>
    <th class="wide">{'Location'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="wide">{'Sub items'|i18n( 'design/admin/node/view/full' )}</th>
{*   <th class="tight">{'Sorting'|i18n( 'design/admin/node/view/full' )}</th> *}
    <th class="tight">{'Visibility'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">{'Main'|i18n( 'design/admin/node/view/full' )}</th>
</tr>
{section var=assignment loop=$assigned_nodes sequence=array( bglight, bgdark )}
    {let assignment_node=$assignment.node
     assignment_path=$assignment_node.path|append( $assignment_node )}

<tr class="{$assignment.sequence}">

    {* Location. *}
    <td><input type="checkbox" name="AssignmentIDSelection[]" {section show=or( $assignment_node.can_remove|not, eq( $assignment.parent_node, $node.parent_node_id ) )}disabled="disabled"{/section} value="{$assignment.id}" /></td>
    {section show=and( eq( $assignment.node.path_string, $node.path_string ), $assigned_nodes|count|gt(1))}
    <td><b>{section var=node_path loop=$assignment_path} <a href={$node_path.url|ezurl}>{$node_path.name|wash}</a>{delimiter} / {/delimiter}{/section}</b></td>
    {section-else}
    <td>{section var=node_path loop=$assignment_path} <a href={$node_path.url|ezurl}>{$node_path.name|wash}</a>{delimiter} / {/delimiter}{/section}</td>
    {/section}


    {* Sub items. *}
    <td>{$assignment.item.node.children_count}</td>

    {* Sorting. *}
{*
    <td class="nowrap">{$assignment.item.node.sort_array[0][0]} / {$assignment.item.node.sort_array[0][1]|choose( 'down'|i18n( 'design/admin/node/view/full' ), 'up'|i18n( 'design/admin/node/view/full' ) )}</td>
*}

    {* Visibility. *}
    <td class="nowrap">
    {section show=$assignment.item.node.is_invisible}
        {section show=$assignment.item.node.is_hidden}
            {'Hidden'|i18n( 'design/admin/node/view/full' )}
            [ <a href={concat( '/content/hide/', $assignment.item.node.node_id )|ezurl} title="{'Make location and all sub items visible.'|i18n( 'design/admin/node/view/full' )}">{'Reveal'|i18n( 'design/admin/node/view/full' )}</a> ]
        {section-else}
            {'Hidden by superior'|i18n( 'design/admin/node/view/full' )}
            [ <a href={concat( '/content/hide/', $assignment.item.node.node_id )|ezurl} title="{'Hide location and all sub items.'|i18n( 'design/admin/node/view/full' )}">{'Hide'|i18n( 'design/admin/node/view/full' )}</a> ]
        {/section}
    {section-else}
        {'Visible'|i18n( 'design/admin/node/view/full' )}
        [ <a href={concat( '/content/hide/', $assignment.item.node.node_id )|ezurl} title="{'Hide location and all sub items.'|i18n( 'design/admin/node/view/full' )}" >{'Hide'|i18n( 'design/admin/node/view/full' )}</a> ]
    {/section}
    </td>

    {* Main node. *}
    <td><input type="radio" {section show=ne( $assignment.is_main, 0 )}checked="checked"{/section} name="MainAssignmentCheck" {section show=or( $assignment_node.can_edit|not, $assignment_count|le( 1 ) )}disabled="disabled"{/section} value="{$assignment_node.node_id}" /></td>

</tr>
{/let}
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

{* Required to get the main node selection to work,
   unchecked radiobuttons will not be sent by browser. *}
<input type="hidden" name="HasMainAssignment" value="1" />

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
<div class="button-left">
{section show=$node.can_edit}
    <input class="button{section show=$assignment_count|le( 1 )}-disabled{/section}" type="submit" name="RemoveAssignmentButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title="{'Remove selected locations from the list above.'|i18n( 'design/admin/node/view/full' )}" {section show=$assignment_count|le( 1 )}disabled="disabled"{/section} />
    <input class="button{section show=or( eq( $node.node_id, ezini( 'NodeSettings', 'RootNode','content.ini' ) ), eq( $node.node_id, ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) ), eq( $node.node_id, ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) ) )}-disabled{/section}" type="submit" name="AddAssignmentButton" value="{'New location'|i18n( 'design/admin/node/view/full' )}" title="{'Add new location(s).'|i18n( 'design/admin/node/view/full' )}" {section show=or( eq( $node.node_id, ezini( 'NodeSettings', 'RootNode','content.ini' ) ), eq( $node.node_id, ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) ), eq( $node.node_id, ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) ) )}disabled="disabled"{/section} />
{section-else}
    <input class="button-disabled" type="submit" name="" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title={'You can not remove any locations because you do not have permissions to edit the current item.'|i18n( 'design/admin/node/view/full' )} disabled="disabled" />
    <input class="button-disabled" type="submit" name="" value="{'New location'|i18n( 'design/admin/node/view/full' )}" title={'You can not add new locations because you do not have permissions to edit the current item.'|i18n( 'design/admin/node/view/full' )} disabled="disabled" />
{/section}
</div>

<div class="button-right">
{section show=$node.can_edit}
    <input class="button{section show=$assignment_count|le( 1 )}-disabled{/section}" type="submit" name="UpdateMainAssignmentButton" value="{'Set main'|i18n( 'design/admin/node/view/full' )}" title=""{section show=$assignment_count|le( 1 )}title="{'You can not set the main location because there is only one existing location for the current item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled"{/section} />
{section-else}
    <input class="button-disabled" type="submit" name="" value="{'Set main'|i18n( 'design/admin/node/view/full' )}" title="{'You can not set the main location because you do not have permissions to edit the current item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}
</div>

<div class="break"></div>
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>
</div>

</form>
{/let}
