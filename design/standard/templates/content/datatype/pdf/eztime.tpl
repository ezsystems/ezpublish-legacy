{section show=$attribute.content.is_valid}
  {pdf(text, concat($attribute.content.hour,':',$attribute.content.minute)|wash(pdf))}
{/section}