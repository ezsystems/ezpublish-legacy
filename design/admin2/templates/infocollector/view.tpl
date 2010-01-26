<form name="objects" method="post" action={concat( '/infocollector/collectionlist/', $collection.contentobject_id )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Collection #%collection_id for <%object_name>'|i18n( 'design/admin/infocollector/view',, hash( '%collection_id', $collection.id, '%object_name', $collection.object.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-information">
<p class="left modified">{'Last modified'|i18n( 'design/admin/infocollector/view' )}: {$collection.created|l10n( shortdatetime )}, {if $collection.creator} {$collection.creator.contentobject.name|wash} {else} {'Unknown user'|i18n( 'design/admin/infocollector/view' )} {/if} </p>
</div>

<div class="context-attributes">
{section var=CollectedAttributes loop=$collection.attributes}
<div class="block">
<label>{$CollectedAttributes.item.contentclass_attribute_name|wash}:</label>
{attribute_result_gui view=info attribute=$CollectedAttributes.item}
</div>
{/section}
</div>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="button" type="submit" name="RemoveCollectionsButton" value="{'Remove'|i18n( 'design/admin/infocollector/view' )}" title="{'Remove collection.'|i18n( 'design/admin/infocollector/view' )}" />
<input type="hidden" name="CollectionIDArray[]" value="{$collection.id}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
