<p>
{section name=Path loop=$module_result.path}
    {section show=$:item.url}
        <a href={$:item.url|ezurl}>{$Path:item.text|wash}</a> /
    {section-else}
        {$:item.text|wash}
    {/section}
{section-else}
{/section}
</p>
