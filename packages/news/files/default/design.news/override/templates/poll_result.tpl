{set-block scope=global variable=title}{'Poll %pollname'|i18n('design/standard/content/poll',,hash('%pollname',$node.name))}{/set-block}

<h1>{'Results'|i18n( 'design/news/content/poll' )}</h1>

{section show=$error}

{section show=$error_anonymous_user}
<div class="warning">
    <p>{'Anonymous users are not allowed to vote on this poll, please login.'|i18n('design/news/content/poll')}</p>
</div>
{/section}

{section show=$error_existing_data}
<div class="warning">
    <p>{'You have already voted for this poll.'|i18n('design/news/content/poll')}</p>
</div>
{/section}

{/section}

<div id="poll_result">
{section loop=$object.contentobject_attributes}
    {section show=$:item.contentclass_attribute.is_information_collector}
	{let  attribute=$:item
	      contentobject_attribute_id=cond($attribute|get_class|eq('ezinformationcollectionattribute'),$attribute.contentobject_attribute_id,
                                     $attribute|get_class|eq('ezcontentobjectattribute'),$attribute.id)
     contentobject_attribute=cond($attribute|get_class|eq('ezinformationcollectionattribute'),$attribute.contentobject_attribute,
			          $attribute|get_class|eq('ezcontentobjectattribute'),$attribute)
     total_count=fetch('content','collected_info_count', hash( 'object_attribute_id', $contentobject_attribute_id ) )
     item_counts=fetch('content','collected_info_count_list', hash( 'object_attribute_id', $contentobject_attribute_id  ) ) }

<h3>{$contentobject_attribute.content.name}</h3>

<table cellspacing="0">
<tr>

{section name=Option loop=$contentobject_attribute.content.option_list}
    {let item_count=0}
    {section show=is_set($item_counts[$:item.id])}
        {set item_count=$item_counts[$:item.id]}
    {/section}
    <td>
        {$:item.value}
    </td>
    <td>
        <table width="300">
	<tr>
            <td class="small" align="left">
                <i>{round(div(mul($:item_count,100),$total_count))}%</i>
            </td>
            <td class="small" align="right">
                Votes: {$:item_count}
            </td>
        </tr>
        <tr>
            <td bgcolor="#ff6633" width="{div(mul($:item_count,300),$total_count)}">
                &nbsp;
            </td>
            <td bgcolor="#eeeeee" width="{sub(300,div(mul($:item_count,300),$total_count))}">
    
            </td>
        </tr>
        </table>
    </td>
    {/let}

    {delimiter}
</tr>
<tr>
    {/delimiter}

{/section}
</tr>
</table>

{/let}

    {section-else}

        {section show=$attribute_hide_list|contains($:item.contentclass_attribute.identifier)|not}
            <div class="object_title">{attribute_view_gui attribute=$:item}</div>
        {/section}

    {/section}

{/section}

<br/>

{"%count total votes"|i18n( 'design/standard/content/poll' ,,
                             hash( '%count', fetch( content, collected_info_count, hash( object_id, $object.id ) ) ) )}
</div>
