{set-block scope=root variable=subject}Collected information from: {$collection.object.name} {/set-block}

The following information was collected:

{section name=Attribute loop=$collection.attributes}
{$Attribute:item.contentclass_attribute_name}:
{$Attribute:item.data_text}
{/section}
