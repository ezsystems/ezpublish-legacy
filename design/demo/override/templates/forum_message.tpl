{* Forum message template *}

{default with_children=true()
         is_editable=true()
	 is_standalone=true()
         content_object=$node.object
         content_version=$node.contentobject_version_object}

{section show=$is_standalone}
    <form method="post" action={"content/action/"|ezurl}>
{/section}

{section show=$with_children}
    <table class="forummessage" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <th class="forumheader" width="75%">
        {attribute_view_gui attribute=$content_version.data_map.topic}
        </th>
        <th class="dateheader" width="25%">
        {$content_object.published|l10n( shortdatetime )}
        </th>
    </tr>
    <tr>
        <td class="messagecontent" colspan="2">
        {attribute_view_gui attribute=$content_version.data_map.message}
        </td>
    </tr>
    </table>

    {section name=Child loop=fetch( content, list, hash(
                                                       parent_node_id, $node.node_id,
                                                       limit, $page_limit, 
                                                       offset, $view_parameters.offset ) )
             sequence=array( FDF4D9, FDF1CE ) }
        <table class="forumanswer" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <th class="answerheader" style="background-color: #{$Child:sequence};" width="75%">
            {attribute_view_gui attribute=$Child:item.object.data_map.topic}
            </th>
            <th class="answerdateheader" style="background-color: #{$Child:sequence};" width="25%">
            {$Child:item.object.published|l10n(shortdatetime)} <br />by {$Child:item.object.owner.name|wash}
            </th>
        </tr>
        <tr>
            <td class="answercontent" style="background-color: #{$Child:sequence};" colspan="2">
            {attribute_view_gui attribute=$Child:item.object.data_map.message}
            </td>
        </tr>
        </table>
    {/section}
{/section}

{section show=$is_editable}
    <div class="buttonblock">
    <input type="hidden" name="NodeID" value="{$node.node_id}" />
    <input class="button" type="submit" name="NewButton" value="New reply" />
    <input type="hidden" name="ClassID" value="7" />
    <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
    </div>
{/section}

{section show=$is_standalone}
    </form>
{/section}

{/default}
