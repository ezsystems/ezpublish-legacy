&gt;
{section name=Path loop=$items}
<input type="hidden" name="Path[]" value="{$Path:item.name}" />
 <a href={concat($base_uri,"/full/",$Path:item.node_id)|ezurl}>{$Path:item.name}</a>
 /
{/section}
