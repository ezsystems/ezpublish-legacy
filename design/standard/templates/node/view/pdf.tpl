{* Default template for generating PDFs from content node. *}

{let content_version=$node.contentobject_version_object
     node_name=$node.name
     children=$node.children}

{pdf(header, hash( type, 1,
                   text, $node_name,
		   size, 26,
		   align, center ) )}

{section name=ContentObjectAttribute loop=$content_version.contentobject_attributes}
  {attribute_pdf_gui attribute=$ContentObjectAttribute:item}
{/section}

{section var=Child loop=$children show=$tree_traverse|eq(1)}
  {section show=$class_array|contains($Child.item.object.contentclass_id)}
    {$Child.item|attribute(show)}
    {section var=Child2 loop=$Child.item.children}
      {$Child2.item|attribute(show)}    
    {/section}
    {include uri="design:node/view/pdf.tpl" node=$Child.item}
  {/section}
{/section}



{/let}