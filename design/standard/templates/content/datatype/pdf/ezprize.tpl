{section show=$attribute.content.has_discount}
  {pdf(text, "Price:"|i18n("design/standard/content/datatype"))}
  {pdf(strike, $attribute.content.inc_vat_price|l10n(currency))}
  {pdf(text, concat("Your price:"|i18n("design/standard/content/datatype"), " ", $attribute.content.discount_price_inc_vat|l10n(currency), "\n"))}
  {pdf(text, concat("You save:"|i18n("design/standard/content/datatype"), " ", 
                    sub($attribute.content.inc_vat_price,$attribute.content.discount_price_inc_vat)|l10n(currency), 
	  	    " ( ", $attribute.content.discount_percent, " % )" ))}
{section-else}
  {pdf(text, concat("Price"|i18n("design/standard/content/datatype"), " ", $attribute.content.inc_vat_price|l10n(currency), "\n" ))}
{/section}