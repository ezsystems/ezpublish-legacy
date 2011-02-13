<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Remove node?'|i18n( 'design/admin/node/removenode' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

<h2>{'Are you sure you want to remove %1 from node %2?'|i18n( 'design/admin/node/removenode',, hash( '%1', $object.name, '%2', $node.object.name ) )|wash}</h2>
<ul>
    <li>{'Removing this assignment will also remove its %1 children.'|i18n( 'design/admin/node/removenode',, hash( '%1', $ChildObjectsCount ) )}</li>
</ul>

<p><b>{'Note'|i18n( 'design/admin/node/removenode' )}:</b> {'Removed nodes can be retrieved later. You will find them in the trash.'|i18n( 'design/admin/node/removenode' )}</p>
<br/>

<h1>{'Removing node assignment of %1'|i18n( 'design/admin/node/removenode',, array( $object.name ) )|wash}</h1>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

<form enctype="multipart/form-data" method="post" action={concat( '/content/removenode/', $object.id, '/', $edit_version, '/', $node.node_id, '/' )|ezurl}>
    <input type="hidden" name=RemoveNodeID value={$node.node_id} />
    <input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/node/removenode' )}" />
    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/node/removenode' )} />
</form>

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>
