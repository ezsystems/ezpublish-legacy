<table class="list">
<tr>
<th>Created</th>
<th>Last modified</th>
<th>Versions</th>
<th>Translations</th>
<th>Related items</th>
<th>Used by</th>
<th>Section</th>
<th>Node ID</th>
<th>Object ID</th>
</tr>
<tr>
<td>{$node.object.owner.name} <br />({$node.object.published|l10n(shortdatetime)})</td>
<td>{$node.object.current.creator.name}<br /> ({$node.object.modified|l10n(shortdatetime)})</td>
<td>{$node.object.versions|count()}</td>
<td>{$node.contentobject_version_object.language_list|count}</td>
<td>{$node.object.related_contentobject_count}</td>
<td>{$node.object.reverse_related_contentobject_count}</td>
<td>{$node.object.section_id}</td>
<td>{$node.node_id}</td>
<td>{$node.object.id}</td>
</tr>
</table>
