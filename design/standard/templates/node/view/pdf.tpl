{* Default template for generating PDFs from content node. *}

{let content_version=$node.contentobject_version_object
     node_name=$node.name
     children=$node.children}

{pdf(header, hash( level, 1,
                   text, $node_name|wash(pdf),
		   size, 26,
		   align, left ) )}

{section name=ContentObjectAttribute loop=$content_version.contentobject_attributes}
  {attribute_pdf_gui attribute=$ContentObjectAttribute:item}
  {pdf(newline)}
{/section}

{section show=$tree_traverse|eq(1)}
  {section name=Child loop=$children}
    {section show=$class_array|contains($Child:item.object.contentclass_id)}
      {include uri="design:node/view/pdf.tpl" node=$Child:item tree_traverse=$tree_traverse class_array=$class_array}
    {/section}
  {/section}
{/section}

{/let}

{section show=$generate_toc|eq(1)}
  {include uri="design:content/pdf/toc.tpl"}
{/section}