<div class="context-block">

<h2 class="title">Additional information</h2>

<table class="list" cellspacing="0">
<tr>
    <th class="tight">Node ID:</th>
    <th class="wide">Created:</th>
    <th class="tight">Related objects:</th>
    <th class="tight" colspan="2">Versions:</th>
</tr>
<tr class="bglight">
    <td>{$node.node_id}</td>
    <td>{$node.object.owner.name} <br />({$node.object.published|l10n(shortdatetime)})</td>
    <td>{$node.object.related_contentobject_count}</td>
    <td colspan="2">{$node.object.versions|count()}</td>
</tr>
<tr>
    <th>Object ID:</th>
    <th>Last modified:</th>
    <th>Used by:</th>
    <th>Translations:</th>
    <th>Section:</th>
</tr>
<tr class="bglight">
    <td>{$node.object.id}</td>
    <td>{$node.object.current.creator.name}<br /> ({$node.object.modified|l10n(shortdatetime)})</td>
    <td>{$node.object.reverse_related_contentobject_count}</td>
    <td>{$node.contentobject_version_object.language_list|count}</td>
    <td>{$node.object.section_id}</td>
</tr>
</table>

</div>