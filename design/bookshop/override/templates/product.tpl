{* Product template *}

{default with_children=true()
         is_editable=true()
	 is_standalone=true()
         content_object=$node.object
         content_version=$node.contentobject_version_object
}
{let map=$content_version.data_map
     comment_limit=3
     list_count=fetch( content, list_count, hash( parent_node_id, $node.node_id ) )}

{section show=$is_standalone}
    <form method="post" action={"content/action/"|ezurl}>
{/section}

<div class="maincontentheader">
    <h1>{attribute_view_gui attribute=$map.title}</h1>
</div>

{attribute_view_gui attribute=$map.photo border_size=1 hspace=10 alignment=right image_class=medium}

<div class="byline">
    <p class="price">Our Price: {attribute_view_gui attribute=$map.price}</p>
    <p class="number">Product nr: {attribute_view_gui attribute=$map.product_nr|wash}</p>
</div>

{attribute_view_gui attribute=$map.intro}
{attribute_view_gui attribute=$map.description}

{section show=$is_editable}
    <div class="buttonblock">
        {section name=ContentAction loop=$content_object.content_action_list show=$content_object.content_action_list}
            <input class="button" type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name|wash}" />
        {/section}
    </div>
{/section}

{section show=and( $with_children, $list_count )}
    <h2>Reviews</h2> 
    <h3>Number of Reviews: {$list_count}</h3>

    {section name=Comment loop=fetch( content, list, hash( 
                                                     parent_node_id, $node.node_id,
                                                     offset, $view_parameters.offset,
                                                     limit, $comment_limit ) )}
        {let object=$Comment:item.object
             map=$Comment:object.data_map}
        <div class="comment">
            {section name=Enum loop=$Comment:map.rating.content.enumobject_list}
	        <img src={concat( "star-", $Comment:Enum:item.enumvalue, ".gif" )|ezimage} border="0" alt="" /><br />
	    {/section}
	    <h3>{attribute_view_gui attribute=$Comment:map.title}</h3>
	    <div class="byline">
	        <p class="author">Reviewer: {attribute_view_gui attribute=$Comment:map.reviewer_name} from {attribute_view_gui attribute=$Comment:map.geography}</p>
	        <p class="date">{$Comment:object.published|l10n(shortdate)}</p>
	    </div>
	    <p>{attribute_view_gui attribute=$Comment:map.review}</p>
	</div>
        {/let}
    {/section}
{/section}

{section show=$is_editable}
    <div class="buttonblock">
        <input type="hidden" name="NodeID" value="{$node.node_id}" />
        <input class="button" type="submit" name="NewButton" value="New review" />
        <input type="hidden" name="ClassID" value="9" />
        <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
    </div>
{/section}

{section show=$is_standalone}
    </form>
{/section}

{/let}
{/default}