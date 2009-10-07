<p>
{section name=Path loop=$module_result.path}
    {if $Path:item.url}
        {if is_set($Path:item.url_alias)}
            <a href={$Path:item.url_alias|ezurl}>{$Path:item.text|wash}</a> /
        {else}
            <a href={$Path:item.url|ezurl}>{$Path:item.text|wash}</a> /
        {/if}
    {else}
        {$Path:item.text|wash}
    {/if}
{section-else}
{/section}
</p>
