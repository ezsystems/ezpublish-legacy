{set-block scope=global variable=title}{'Poll %pollname'|i18n('design/standard/content/poll',,hash('%pollname',$node.name))}{/set-block}

<h1>{'Poll results'|i18n( 'design/standard/content/poll' )}</h1>

{section show=$error}

{section show=$error_existing_data}
<p>{'You have already voted for this poll.'|i18n('design/standard/content/form)}</p>
{/section}

{/section}

<h2>{$node.name}</h2>

{section loop=$object.contentobject_attributes}
    {section show=$:item.contentclass_attribute.is_information_collector}

        <h3>{$:item.contentclass_attribute.name}</h3>
        {attribute_result_gui view=count attribute=$:item}

    {section-else}

        {section show=$attribute_hide_list|contains($:item.contentclass_attribute.identifier)|not}
            <h3>{$:item.contentclass_attribute.name}</h3>
            {attribute_view_gui attribute=$:item}
        {/section}

    {/section}

{/section}

<br/>

{"%count total votes"|i18n( 'design/standard/content/poll' ,,
                             hash( '%count', fetch( content, collected_info_count, hash( object_id, $object.id ) ) ) )}
