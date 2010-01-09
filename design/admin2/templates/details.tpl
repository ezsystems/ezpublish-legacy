{* Details window. *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Details'|i18n( 'design/admin/node/view/full' )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list" cellspacing="0" summary="{'Node and object details like creator, when it was created, section it belongs to, number of versions and translations, Node ID and Object ID.'|i18n( 'design/admin/node/view/full' )}">
<tr>
    <th>{'Creator'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Created'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Section'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">{'Versions'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">{'Translations'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">{'Node ID'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">{'Object ID'|i18n( 'design/admin/node/view/full' )}</th>
</tr>
<tr class="bglight">
    <td><a href={$node.object.owner.main_node.url_alias|ezurl}>{$node.object.owner.name|wash}</a></td>
    <td>{$node.object.published|l10n( shortdatetime )}</td>
    <td>{let section_object=fetch( section, object, hash( section_id, $node.object.section_id ) )}{section show=$section_object}<a href={concat( '/section/view/', $node.object.section_id )|ezurl}>{$section_object.name|wash}</a>{section-else}<i>{'Unknown'|i18n( 'design/admin/node/view/full' )}</i>{/section}{/let}</td>
    <td class="number" align="right">{$node.object.versions|count()}</td>
    <td class="number" align="right">{$node.contentobject_version_object.language_list|count}</td>
    <td class="number" align="right">{$node.node_id}</td>
    <td class="number" align="right">{$node.object.id}</td>
</tr>
</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
