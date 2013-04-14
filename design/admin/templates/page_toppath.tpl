{let name=Path
     use_urlalias=ezini('URLTranslator','Translation')|eq('enabled')}

<p class="path"><span class="path-here-text">{'You are here:'|i18n( 'design/admin/pagelayout/path' )}</span>
{section loop=$module_result.path}
    {if $:item.url}
        {if ne($ui_context,'edit')}
        <a class="path" href={cond( and( $:use_urlalias, is_set( $:item.url_alias ) ), $:item.url_alias,
                                    $:item.url )|ezurl}>{$:item.text|shorten( 18 )|wash}</a>
        {else}
        <span class="disabled">{$:item.text|shorten( 18 )|wash}</span>
        {/if}
    {else}
        <span class="path">{$:item.text|wash}</span>
    {/if}

    {delimiter}
        <span class="slash">/</span>
    {/delimiter}
{/section}
&nbsp;</p>
{/let}
