{switch match=$attribute.data_int}
{case match=1}
<img src="/{$attribute.content|ezpackage(filepath,"thumbnail")}" />
{/case}
{case}
{$attribute.data_text}
{/case}
{/switch}

