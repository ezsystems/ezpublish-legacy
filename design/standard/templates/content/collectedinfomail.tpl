{set-block scope=root variable=subject}{"Collected information from:"|i18n("design/standard/content/edit")} {$collection.object.name} {/set-block}
{set-block scope=root variable=email_receiver}nospam@ez.no{/set-block}

{"The following information was collected:"|i18n("design/standard/content/edit")}

{section name=Attribute loop=$collection.attributes}
{$Attribute:item.contentclass_attribute_name}:
{$Attribute:item.data_text}
{/section}
