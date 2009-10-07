<form action={concat($module.functions.removeobject.uri)|ezurl} method="post" name="ObjectRemove">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

{if $remove_info.can_remove_all}
<h2 class="context-title">{'Confirm location removal'|i18n( 'design/admin/node/removeobject' )}</h2>
{else}
<h2 class="context-title">{'Insufficient permissions'|i18n( 'design/admin/node/removeobject' )}</h2>
{/if}

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{if $total_child_count|gt( 0 )}
<div class="block">
    <p>{'Some of the items that are about to be removed contain sub items.'|i18n( 'design/admin/node/removeobject' )}</p>

    {if $reverse_related}
        <p>{'Some of the subtrees or objects selected for removal are used by other objects. Select the menu from the content tree, and'|i18n( 'design/admin/node/removeobject' )}
           <strong>{'Advanced'|i18n( 'design/admin/node/removeobject' )}</strong>-&gt;
           <strong>{'Reverse related for subtree'|i18n( 'design/admin/node/removeobject' )}</strong>
        </p>
    {/if}

    {if eq( $exceeded_limit, true() )}
        <hr />
    <h4>Warnings:</h4>
        <p>{'The lines marked with red contain more than the maximum possible nodes for subtree removal and will not be deleted. You can remove this subtree using the ezsubtreeremove.php script.'|i18n( 'design/admin/node/removeobject' )}</p>
    <hr />
    {/if}

    {if $remove_info.can_remove_all}
        <p>{'Removing the items will also result in the removal of their sub items.'|i18n( 'design/admin/node/removeobject' )}</p>
        <p>{'Are you sure you want to remove the items along with their contents?'|i18n( 'design/admin/node/removeobject' )}</p>
    {else}
        <p>{'The lines marked with red contain items that you do not have permission to remove.'|i18n( 'design/admin/node/removeobject' )}</p>
        <p>{'Click the "Cancel" button and try removing only the locations that you are allowed to remove.'|i18n( 'design/admin/node/removeobject' )}</p>
    {/if}
</div>
{else}
   {if $reverse_related}
   <div class="block">
        <p>{'Some of the objects selected for removal are used by other objects. Select the menu from the content tree, and'|i18n( 'design/admin/node/removeobject' )}
           <strong>{'Advanced'|i18n( 'design/admin/node/removeobject' )}</strong>-&gt;
           <strong>{'Reverse related for subtree'|i18n( 'design/admin/node/removeobject' )}</strong>
        </p>
   </div>
   {/if}
{/if}

<table class="list" cellspacing="0">
<tr>
    <th colspan="2">{'Item'|i18n( 'design/admin/node/removeobject' )}</th>
    <th>{'Type'|i18n( 'design/admin/node/removeobject' )}</th>
    <th>{'Sub items'|i18n( 'design/admin/node/removeobject' )}</th>
</tr>
{section var=remove_item loop=$remove_list sequence=array( bglight, bgdark )}

<tr class="{$remove_item.sequence}{if or( $remove_item.can_remove|not, and( is_set( $remove_item.exceeded_limit_of_subitems ), eq( $remove_item.exceeded_limit_of_subitems, true() ) ) )} object-cannot-remove{/if}">
    {* Object icon. *}
    <td class="tight">{$remove_item.class.identifier|class_icon( small, $remove_item.class.name|wash )}</td>

    {* Location. *}
    <td>
        {section var=path_node loop=$remove_item.node.path|append( $remove_item.node )}
          {$path_node.name|wash}
        {delimiter} / {/delimiter}
        {/section}
    </td>

    {* Type. *}
    <td>
        {$remove_item.object.class_name|wash}
    </td>

{* Sub items. *}
    <td>
    {if $remove_item.child_count|eq( 1 )}
        {'%child_count item'
         |i18n( 'design/admin/content/removeobject',,
                hash( '%child_count', $remove_item.child_count ) )}
     {else}
        {'%child_count items'
         |i18n( 'design/admin/content/removeobject',,
                hash( '%child_count', $remove_item.child_count ) )}
     {/if}
     </td>

</tr>
{/section}
</table>

<div class="block">
{if and( $remove_info.can_remove_all, eq( $delete_items_exist,true() ) )}
    {if $move_to_trash_allowed}
    <input type="hidden" name="SupportsMoveToTrash" value="1" />
    <p><input type="checkbox" name="MoveToTrash" value="1" {if $move_to_trash}checked="checked" {/if}title="{'If "Move to trash" is checked, the items will be moved to the trash instead of being permanently deleted.'|i18n( 'design/admin/node/removeobject' )|wash}" />{'Move to trash'|i18n('design/admin/node/removeobject')}</p>
    {/if}
{/if}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

    {if and( $remove_info.can_remove_all, eq( $delete_items_exist, true() ) )}
        <input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/node/removeobject' )}" />
    {else}
        <input class="button-disabled" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/node/removeobject' )}" title="{'You cannot continue because you do not have permission to remove some of the selected locations.'|i18n( 'design/admin/node/removeobject' )}" disabled="disabled" />
    {/if}

    <input type="submit" class="button" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/node/removeobject' )}" title="{'Cancel the removal of locations.'|i18n( 'design/admin/node/removeobject' )}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>
