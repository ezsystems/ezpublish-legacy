{* Full class template for Multipage Article *}

<h1>{$node.name}</h1>
<div class="byline">Last updated {$node.parent.object.published|l10n(datetime)}</div>

<div class="block">
{attribute_view_gui attribute=$node.object.data_map.intro}
</div>

<div class="block">
{attribute_view_gui attribute=$node.object.data_map.body}
</div>

{let children=fetch('content', 'list', hash(parent_node_id, $node.main_node_id, class_filter, include, class_filter_array(25)))
}

<td class="rightmenuarea" width="140" valign="top" align="left">
&nbsp;
{* right area *}
<ul>
{section show=$children}
<h2>Sections</h2>
<li>{$node.name}</li>
{/section}
{section name=Child loop=$children}
    <li><a href={$Child:item.url_alias|ezurl}>{$Child:item.name}</a></li>
{/section}
</ul>
</td>
{/let}
