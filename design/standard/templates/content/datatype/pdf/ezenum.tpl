{section name=EnumObjectList loop=$attribute.content.enumobject_list}
{pdf(text, $EnumObjectList:item.enumelement|wash(pdf))}
{pdf(newline)}
{/section}