{* This tests if the include function sets the correct 'current' and 'root' namespace
for the included template by having the same variable with different content in multiple namespaces *}
START:


{let test='global'}
{let name=first_ns test='in first_ns namespace'}

{let name=second_ns}
{include uri="tests/eztemplate/functions/include_with_namespaces.tpl.inc"}
{/let}

{/let}


{let name=first_ns test='in first_ns namespace'}

{include name=include_ns uri="tests/eztemplate/functions/include_with_namespaces.tpl.inc"}

{/let}
{/let}

END:
