{let article=$node.contentobject_version_object
     node_name=$node.name}

  {pdf(pageNumber, hash( identifier, "main",
                         start, 1 ) )}

{attribute_pdf_gui attribute=$article.data_map.title}

{attribute_pdf_gui attribute=$article.data_map.author}

{attribute_pdf_gui attribute=$article.data_map.intro}

{attribute_pdf_gui attribute=$article.data_map.body}

{pdf(pageNumber, hash( identifier, "main",
                       stop, 1 ) )}

{include uri="design:content/pdf/footer.tpl" node=$node}

{/let}