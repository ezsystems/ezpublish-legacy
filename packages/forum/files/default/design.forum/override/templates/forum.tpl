{* Forum template *}
{default with_children=true()
         is_editable=true()
	 is_standalone=true()
         content_object=$node.object}

{section show=$is_standalone}
    <form method="post" action={"/content/action/"|ezurl}>
{/section}

{section show=$with_children}
    <table class="forumlist" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <th class="forumheader">
            Topic
        </th>
        <th class="postedheader">
            Posted
        </th>
    </tr>
    {section name=Message sequence=array( FDF4D9, FDF1CE ) loop=fetch('content', 'list', hash( parent_node_id, $node.node_id, 
    							                                      limit, $page_limit,
                                                                                              offset, $view_parameters.offset) ) }
        <tr>
            <td class="messagetopic" style="background-color: #{$Message:sequence};" width="75%">
                <a href={concat('content/view/full/',$Message:item.node_id,'/')|ezurl}>{$Message:item.name|wash}</a>
                {let list_count=fetch( 'content', 'tree_count', hash( parent_node_id, $Message:item.node_id ) ) }
                ( {$Message:list_count} )
                {/let}
            </td>
            <td class="messageauthor" style="background-color: #{$Message:sequence};" width="25%">
                {$Message:item.object.published|l10n( shortdatetime )}<br />
                by {$Message:item.object.owner.name|wash}
            </td>
        </tr>
    {/section}
    </table>
{/section}


{section show=$is_editable}
    <div class="buttonblock">
        <input type="hidden" name="NodeID" value="{$node.node_id}" />
        <input class="button" type="submit" name="NewButton" value="New topic" />
        <input type="hidden" name="ClassID" value="7" />
        <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
    </div>
{/section}

{section show=$is_standalone}
    </form>
{/section}

{/default}
