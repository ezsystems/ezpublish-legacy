<form action={concat($module.functions.removeobject.uri)|ezurl} method="post" name="ObjectRemove">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">
{section show=$remove_list|count|eq( 1 )}
    {'Remove object'|i18n( 'design/admin/node/removeobject' )}
{section-else}
    {'Remove objects'|i18n( 'design/admin/node/removeobject' )}
{/section}
</h2>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$total_child_count|gt( 0 )}
    <div class="message-confirmation">
        <p>{'Some of the objects that are about to be removed have sub items.'|i18n( 'design/admin/node/removeobject' )}</p>
        <p>{'Removing the objects will also result in the removal of the sub items.'|i18n( 'design/admin/node/removeobject' )}</p>
        <p>{'Are you sure you want to remove these objects along with their contents?'|i18n( 'design/admin/node/removeobject' )}</p>
    </div>
{/section}

{section show=$remove_info.can_remove_all|not}
    <div class="message-confirmation">
        <p>{'Some of the items cannot be removed, you will need to unselect the items marked in red.'
            |i18n( 'design/admin/node/removeobject' )}</p>
    </div>
{/section}

<table class="list" cellspacing="0">
<tr>
    <th colspan="2">{'Location'|i18n( 'design/admin/node/removeobject' )}</th>
    <th>{'Sub items'|i18n( 'design/admin/node/removeobject' )}</th>
</tr>
{section var=remove_item loop=$remove_list sequence=array( bglight, bgdark )}
<tr class="{$remove_item.sequence}{section show=$remove_item.can_remove|not} object-cannot-remove{/section}">
    {* Object icon. *}
    <td class="class-icon">{$remove_item.class.identifier|class_icon( small, $remove_item.class.name )}</td>

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


{section show=$remove_info.can_remove_all}
    {section show=$move_to_trash_allowed}
        <input type="hidden" name="SupportsMoveToTrash" value="1" />
        <p><input type="checkbox" name="MoveToTrash" value="1" checked="checked" title="{'If "Move to trash" is checked you will find the removed items in the trash afterwards.'|i18n( 'design/admin/node/removeobject' )|wash}" />{'Move to trash'|i18n('design/admin/node/removeobject')}</p>
    {/section}
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

    {section show=$remove_info.can_remove_all}
        {include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="OK"|i18n("design/admin/node/removeobject")}
    {/section}
    {include uri="design:gui/defaultbutton.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/admin/node/removeobject")}

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>
