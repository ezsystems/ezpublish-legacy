{section show=$error_list|gt( 0 )}
<div class="error">
<ul>
{section var=error loop=$error_list}
    <li><em class="field">{$error.item.field|wash}:</em> {$error.item.description|wash}</li>
{/section}
</ul>
</div>
{/section}
