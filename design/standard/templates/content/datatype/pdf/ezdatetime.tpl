{section show=$attribute.content.is_valid}
  {pdf(text, concat($attribute.content.year,'.',$attribute.content.month,'.',$attribute.content.day,' ',$attribute.content.hour,':',$attribute.content.minute)|wash(pdf))}
{/section}