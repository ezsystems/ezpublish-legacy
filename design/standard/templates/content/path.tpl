&gt;
{section name=Path loop=$items}
<input type="hidden" name="Path[]" value="{$Path:item.name}" />
 <a href="{$base_uri}/full/{$Path:item.node_id}">{$Path:item.name}</a>
 /
{/section}
