<p>
{section name=Path loop=$module_result.path}
    {section show=$Path:item.url}
        {section show=is_set($Path:item.url_alias)}
            <a href={$Path:item.url_alias|ezurl}>{$Path:item.text|wash}</a> /
        {section-else}
            <a href={$Path:item.url|ezurl}>{$Path:item.text|wash}</a> /
        {/section}
    {section-else}
        {$Path:item.text|wash}
    {/section}
{section-else}
{/section}
</p>
