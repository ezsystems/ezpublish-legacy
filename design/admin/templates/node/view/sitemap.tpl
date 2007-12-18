{let page_limit=0}
{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name|wash}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Two-level index for <%node_name>'|i18n( 'design/admin/node/view',, hash( '%node_name', $node.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

<div class="content-viewindex">

{let children=fetch( 'content', 'list',
                     hash( parent_node_id, $node.node_id,
                           sort_by, $node.sort_array,
                           limit, $page_limit,
                           offset, $view_parameters.offset ) )}

{section var=child loop=$children}

    <h2>{$child.class_identifier|class_icon( small, $child.class_name )}&nbsp;<a href={$child.url|ezurl}>{$child.name|wash}</a></h2>
    {let grandchildren=fetch( 'content', 'list',
                              hash( parent_node_id, $child.node_id,
                                    sort_by, $node.sort_array,
                                    limit, $page_limit,
                                    offset, $view_parameters.offset ) )}

        {section show=$grandchildren|count|gt( 0 )}
        <ul>
            {section var=grandchild loop=$grandchildren}

                <li>{$grandchild.class_identifier|class_icon( small, $grandchild.class_name )}&nbsp;<a href={$grandchild.url|ezurl}>{$grandchild.name|wash}</a></li>

            {/section}
        </ul>
        {/section}

    {/let}

{/section}

{/let}

</div>

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/default}
{/let}
{/default}
