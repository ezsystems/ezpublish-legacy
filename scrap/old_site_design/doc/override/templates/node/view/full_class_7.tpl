{* Full class template for Tech Page *}

{let children=fetch('content', 'list', hash(parent_node_id, $node.parent_node_id, class_filter, include, class_filter_array(25)))
}

<div class="block">
{attribute_view_gui attribute=$node.object.data_map.title}
</div>
<div class="byline">Last updated {$node.parent.object.published|l10n(datetime)}</div>

<div class="block">
{attribute_view_gui attribute=$node.object.data_map.body}
</div>

<td class="rightmenuarea" width="140" valign="top">
&nbsp;
{* right area *}
<ul>
<h2>Sections</h2>
<li><a href={$node.parent.url_alias|ezurl}>{$node.parent.name}</a></li>
{section name=Child loop=$children}
{section show=eq($Child:item.node_id, $node.node_id)}
    <li>{$Child:item.name}</li>
{section-else}
    <li><a href={$Child:item.url_alias|ezurl}>{$Child:item.name}</a></li>
{/section}

{/section}
</ul>
</td>