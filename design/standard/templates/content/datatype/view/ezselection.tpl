{let selected_id_array=$attribute.content}
{section name=Option loop=$attribute.class_content.options}
{section show=$selected_id_array|contains($Option:item.id)}{$Option:item.name|wash(xhtml)}<br/>{/section}
{/section}
{/let}