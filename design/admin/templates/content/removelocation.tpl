<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Remove location?'|i18n( 'design/admin/content/removelocation' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

<form action={"/content/removelocation/"|ezurl} method="post">

<h1>{'Removal of locations'|i18n( 'design/admin/content/removelocation' )}</h1>

<p>{'Some of the locations you tried to remove has children, are you sure you want to remove those locations?
If you do all the children will be removed as well.'|i18n( 'design/admin/content/removelocation' )}</p>

</div>

<table class="list" cellspacing="0">
<tr>
    <th>{'Path'|i18n( 'design/standard/location' )}</th>
    <th>{'Count'|i18n( 'design/standard/location' )}</th>
</tr>
{section var=remove_item loop=$node_remove_list sequence=array( bglight, bgdark )}
<tr class="{$remove_item.sequence}">
    <td>{section var=path_node loop=$remove_item.node.path}{$path_node.name|wash}{delimiter} / {/delimiter}{/section}</td>
    <td>{section show=$remove_item.count|eq( 1 )}{$remove_item.count} child{section-else}{$remove_item.count} children{/section}</td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

<input type="submit" class="button" name="ConfirmRemovalButton" value="{'Remove locations'|i18n( 'design/standard/location' )}" />
<input type="submit" class="button" name="CancelRemovalButton" value="{'Cancel removal'|i18n( 'design/standard/location' )}" />

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>

