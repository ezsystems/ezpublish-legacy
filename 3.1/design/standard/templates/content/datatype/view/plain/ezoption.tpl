{$attribute.content.name|wash(xhtml)}

{section name=Option loop=$attribute.content.option_list}
{$Option:item.value} - {$Option:item.additional_price|l10n(currency)}

{/section}


