<div class="context-block">

<h2 class="context-title">{'Additional information'|i18n( 'design/admin/navigation/information')}</h2>

<table class="list" cellspacing="0">
<tr>
    <th>{'Creator'|i18n( 'design/admin/navigation/information' )}</th>
    <th>{'Created'|i18n( 'design/admin/navigation/information' )}</th>
    <th>{'Versions'|i18n( 'design/admin/navigation/information' )}</th>
    <th>{'Translations'|i18n( 'design/admin/navigation/information')}</th>
    <th>{'Section'|i18n( 'design/admin/navigation/information')}</th>
    <th>{'Node ID'|i18n( 'design/admin/navigation/information')}</th>
    <th>{'Object ID'|i18n( 'design/admin/navigation/information')}</th>
</tr>
<tr class="bglight">
    <td>{$node.object.owner.name}</td>
    <td>{$node.object.published|l10n(shortdatetime)}</td>
    <td>{$node.object.versions|count()}</td>
    <td>{$node.contentobject_version_object.language_list|count}</td>
    <td>{$node.object.section_id}</td>
    <td>{$node.node_id}</td>
    <td>{$node.object.id}</td>
</tr>
</table>

</div>