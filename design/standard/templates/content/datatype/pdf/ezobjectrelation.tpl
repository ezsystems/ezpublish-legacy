{section show=$attribute.content}
  {pdf(link, hash(url, concat('content/view/full/',$attribute.content.main_node_id)|ezurl,
                  text, $attribute.content.name))};
{section-else}
   {pdf(text, "No relation"|i18n("design/standard/content/datatype"))}
{/section}