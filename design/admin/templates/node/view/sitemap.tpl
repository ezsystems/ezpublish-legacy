{default with_children=true()
	 is_standalone=true()}
{let page_limit=0
     tree=and($with_children,fetch('content','tree',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset)))
     tree_count=and($with_children,fetch('content','tree_count',hash(parent_node_id,$node.node_id)))}
{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{"Content list"|i18n("design/admin/node/view")}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

{let children=fetch('content','list',hash(parent_node_id,$node.node_id,sort_by,$node.sort_array,limit,$page_limit,offset,$view_parameters.offset))}

{section name=Child loop=$children}

    <h2><a href={concat( "/content/view/full/", $Child:item.node_id, "/")|ezurl}>{$Child:item.name}</a></h2>
    {let grandchildren=fetch('content','list',hash(parent_node_id,$Child:item.node_id,sort_by,$node.sort_array,limit,$page_limit,offset,$view_parameters.offset))}

        {section show=$Child:grandchildren|eq(true())}
        <ul>
        {/section}
        {section name=Grandchild loop=$Child:grandchildren}

            <li><a href={concat( "/content/view/full/", $Child:Grandchild:item.node_id, "/")|ezurl}>{$Child:Grandchild:item.name}</a></li>

        {/section}
        {section show=$Child:grandchildren|eq(true())}
        </ul>
        {/section}

    {/let}

{/section}

{/let}

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/default}
{/let}
{/default}
