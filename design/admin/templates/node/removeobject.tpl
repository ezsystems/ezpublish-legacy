<form action={concat($module.functions.removeobject.uri)|ezurl} method="post" name="ObjectRemove">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

{section show=$remove_info.can_remove_all}
<h2 class="context-title">{'Confirm location removal'|i18n( 'design/admin/content/removelocation' )}</h2>
{section-else}
<h2 class="context-title">{'Insufficient permissions'|i18n( 'design/admin/content/removelocation' )}</h2>
{/section}

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">


{section show=$total_child_count|gt( 0 )}
<div class="block">
    <p>{'Some of the items that are about to be removed contain sub items.'|i18n( 'design/admin/content/removelocation' )}</p>

    {section show=$remove_info.can_remove_all}
        <p>{'Removing the items will also result in the removal of their sub items.'|i18n( 'design/admin/content/removelocation' )}</p>
        <p>{'Are you sure you want to remove the items along with their contents?'|i18n( 'design/admin/content/removelocation' )}</p>
    {section-else}
        <p>{'The lines marked with red contain items that you do not have permissions to remove.'|i18n( 'design/admin/content/removelocation' )}</p>
        <p>{'Click the "Cancel" button and try removing only the locations that you are allowed to remove.'|i18n( 'design/admin/content/removelocation' )}</p>
    {/section}
</div>
{/section}

<table class="list" cellspacing="0">
<tr>
    <th colspan="2">{'Item'|i18n( 'design/admin/node/removeobject' )}</th>
    <th>{'Sub items'|i18n( 'design/admin/node/removeobject' )}</th>
</tr>
{section var=remove_item loop=$remove_list sequence=array( bglight, bgdark )}
<tr class="{$remove_item.sequence}{section show=$remove_item.can_remove|not} object-cannot-remove{/section}">
    {* Object icon. *}
    <td class="tight">{$remove_item.class.identifier|class_icon( small, $remove_item.class.name )}</td>

    {* Location. *}
    <td>
    {section var=path_node loop=$remove_item.node.path|append( $remove_item.node )}
        {$path_node.name|wash}
    {delimiter} / {/delimiter}
    {/section}
    </td>

    {* Sub items. *}
    <td>
    {section show=$remove_item.child_count|eq( 1 )}
        {'%child_count item'
         |i18n( 'design/admin/content/removeassignment',,
                hash( '%child_count', $remove_item.child_count ) )}
     {section-else}
        {'%child_count items'
         |i18n( 'design/admin/content/removeassignment',,
                hash( '%child_count', $remove_item.child_count ) )}
     {/section}
     </td>
</tr>
{/section}
</table>

<div class="block">
{section show=$remove_info.can_remove_all}
    {section show=$move_to_trash_allowed}
        <input type="hidden" name="SupportsMoveToTrash" value="1" />
        <p><input type="checkbox" name="MoveToTrash" value="1" checked="checked" title="{'If "Move to trash" is checked you will find the removed items in the trash afterwards.'|i18n( 'design/admin/node/removeobject' )|wash}" />{'Move to trash'|i18n('design/admin/node/removeobject')}</p>
    {/section}
{/section}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

    {section show=$remove_info.can_remove_all}
        <input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/content/removelocation' )}" />
    {section-else}
        <input class="button-disabled" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/content/removelocation' )}" title="{'You can not continue because you do not have permissions to remove some of the selected locations.'|i18n( 'design/admin/content/removelocation' )}" disabled="disabled" />
    {/section}

    <input type="submit" class="button" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/content/removelocation' )}" title="{'Cancel the removal of locations.'|i18n( 'design/admin/content/removelocation' )}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>
