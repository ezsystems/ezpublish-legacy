{set-block scope=global variable=title}{'Poll %pollname'|i18n('design/standard/content/poll',,hash('%pollname',$node.name))}{/set-block}
<div class="content-view-full">
    <div class="class-poll">
        <div class="poll-result">

        <h1>{'Results'|i18n( 'design/standard/content/poll' )}</h1>

        {section show=$error}

        {section show=$error_anonymous_user}
        <div class="warning">
            <p>{'Anonymous users are not allowed to vote on this poll, please login.'|i18n('design/standard/content/poll')}</p>
        </div>
        {/section}

        {section show=$error_existing_data}
        <div class="warning">
            <p>{'You have already voted for this poll.'|i18n('design/standard/content/poll')}</p>
        </div>
        {/section}

        {/section}

        {section loop=$object.contentobject_attributes}
            {section show=$:item.contentclass_attribute.is_information_collector}
        	{let  attribute=$:item
        	      contentobject_attribute_id=cond( $attribute|get_class|eq( 'ezinformationcollectionattribute' ),$attribute.contentobject_attribute_id,
                                                   $attribute|get_class|eq( 'ezcontentobjectattribute' ),$attribute.id )
                  contentobject_attribute=cond( $attribute|get_class|eq( 'ezinformationcollectionattribute' ),$attribute.contentobject_attribute,
                                                $attribute|get_class|eq( 'ezcontentobjectattribute' ),$attribute )
                  total_count=fetch( 'content', 'collected_info_count', hash( 'object_attribute_id', $contentobject_attribute_id ) )
                  item_counts=fetch( 'content', 'collected_info_count_list', hash( 'object_attribute_id', $contentobject_attribute_id  ) ) }

                <h3>{$contentobject_attribute.content.name}</h3>

                <table class="poll-resultlist">
                <tr>

                {section name=Option loop=$contentobject_attribute.content.option_list}
                    {let item_count=0}
                    {section show=is_set($item_counts[$:item.id])}
                        {set item_count=$item_counts[$:item.id]}
                    {/section}
                    <td class="poll-resultname">
                        <p>
                            {$:item.value}
                        </p>
                    </td>
                    <td class="poll-resultbar">
                        <table class="poll-resultbar">
                        <tr>
                            <td class="poll-percentage">
                                <i>{round(div(mul($:item_count,100),$total_count))}%</i>
                            </td>
                            <td class="poll-votecount">
                                {'Votes:'|i18n('design/standard/content/poll')} {$:item_count}
                            </td>
                        </tr>
                        <tr>
                            <div class="chart-bar-edge-start">
                                <div class="chart-bar-edge-end">
                                    <div class="chart-bar-resultbox">
                                        <div class="chart-bar-resultbar" class="chart-bar-{round(div(mul($:item_count,100),$total_count))}-of-10" class="chart-bar-{round(div(mul($:item_count,10),$total_count))}-of-10" style="width: {round(div(mul($:item_count,100),$total_count))}%;">
                                            <div class="chart-bar-result-divider"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                    <div class="attribute-short">{attribute_view_gui attribute=$:item}</div>
                {/section}
            {/section}
        {/section}

        <br/>

        {"%count total votes"|i18n( 'design/standard/content/poll' ,,
                                     hash( '%count', fetch( content, collected_info_count, hash( object_id, $object.id ) ) ) )}

            <div class="content-results">
                <div class="attribute-link">
                    <p><a href={$node.url_alias|ezurl}>{"Back to poll"|i18n("design/base")}</a></p>
                </div>
            </div>

        </div>
    </div>
</div>