{let selected_id_array=$attribute.content}
  {section name=Option loop=$attribute.class_content.options}
    {section show=$selected_id_array|contains($Option:item.id)}
      {pdf(text, $Option:item.name|wash(pdf))}
      {pdf(newline)}
    {/section}
  {/section}
{/let}