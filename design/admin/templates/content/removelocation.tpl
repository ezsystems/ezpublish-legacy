<form method="post" action={'/content/removelocation/'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Confirm location removal'|i18n( 'design/admin/content/removelocation' )}</h2>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">
<p>{'Some of the locations that are about to be removed have sub items.'|i18n( 'design/admin/content/removelocation' )}</p>
<p>{'Removing the locations will also result in the removal of the sub items.'|i18n( 'design/admin/content/removelocation' )}</p>
<p>{'Are you sure you want to remove these locations along with their contents?'|i18n( 'design/admin/content/removelocation' )}</p>
</div>

<table class="list" cellspacing="0">
<tr>
    <th>{'Location'|i18n( 'design/admin/content/removelocation' )}</th>
    <th>{'Sub items'|i18n( 'design/admin/content/removelocation' )}</th>
</tr>
{section var=remove_item loop=$node_remove_list sequence=array( bglight, bgdark )}
<tr class="{$remove_item.sequence}">

    {* Location. *}
    <td>{section var=path_node loop=$remove_item.node.path}{$path_node.name|wash}{delimiter} / {/delimiter}{/section}</td>

    {* Sub items. *}
    <td>{$remove_item.count}</td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
<input type="submit" class="button" name="ConfirmRemovalButton" value="{'OK'|i18n( 'design/admin/content/removelocation' )}" title="{'Remove the locations along with all the sub items.'|i18n( 'design/admin/content/removelocation' )}" />
<input type="submit" class="button" name="CancelRemovalButton" value="{'Cancel'|i18n( 'design/admin/content/removelocation' )}" title="{'Cancel the removal of locations.'|i18n( 'design/admin/content/removelocation' )}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>
