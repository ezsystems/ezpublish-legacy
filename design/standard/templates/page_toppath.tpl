{*?template charset=latin1?*}
    <p class="path">
    &nbsp;
    {section name=Path loop=$module_result.path}
        {section show=$Path:item.url}
        <a class="path" href={$Path:item.url|ezurl}>{$Path:item.text|wash}</a>
        {section-else}
        {$Path:item.text|wash}
        {/section}

        {delimiter}
        <span class="slash">/</span>
        {/delimiter}
    {/section}
    &nbsp;</p>
