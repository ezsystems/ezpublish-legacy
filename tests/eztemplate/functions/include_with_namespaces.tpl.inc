INC START:

{let test='var in root of inc'}
{let name=inc_ns test='var in inc_ns namespace'}
current in inc:{$:test}
root in inc:{$test}
specific ns in inc:{$inc_ns:test}
global in inc:{$#test}
global with ns in inc:{$#first_ns:test}
{/let}
{/let}

INC END:
