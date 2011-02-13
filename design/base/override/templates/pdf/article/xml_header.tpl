{switch name=sw match=$level}
{case match=1}
{pdf(header, hash( level, 1,
                   text, $content|wash(pdf),
		   size, 20,
		   align, left ) )}
{/case}
{case match=2}
{pdf(header, hash( level, 2,
                   text, $content|wash(pdf),
		   size, 18,
		   align, left ) )}
{/case}
{case match=3}
{pdf(header, hash( level, 3,
                   text, $content|wash(pdf),
		   size, 16,
		   align, left ) )}
{/case}
{case match=4}
{pdf(header, hash( level, 4,
                   text, $content|wash(pdf),
		   size, 14,
		   align, left ) )}
{/case}
{case}
{pdf(header, hash( level, 5,
                   text, $content|wash(pdf),
		   size, 12 ) )}
{/case}
{/switch}
