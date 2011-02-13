{pdf(footer, hash( text, $node.name|wash(pdf),
                   size, 10,
	           align, "left" ) ) }
{pdf(footer, hash( text, "#page of #total"|i18n( "design/base" )|wash(pdf),
                   align, "right",
		   size, 10 ) ) }
{pdf(footer, hash( line, hash( margin, 80,
		               thicknes, 1,
			       size, "full" ) ) ) }