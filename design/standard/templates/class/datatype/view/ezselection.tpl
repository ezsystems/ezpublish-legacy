<div class="block">
 {section show=$class_attribute.data_int1}{"Multiple choice"|i18n("design/standard/class/datatype"}{section-else}{"Single choice"|i18n("design/standard/class/datatype"}{/section}
</div> 
 
{section name=Option loop=$class_attribute.content.options}
  {$Option:item.name|wash(xhtml)}<br />
{/section}
