<h1>{"Latest blogs"|i18n("design/blog/layout")}</h1>

{let log_list=fetch( content, tree, hash( parent_node_id, 2, 
                                          sort_by, array( array( published, false() ) ),
                                          class_filter_type, 'include',
                                          class_filter_array, array( 'log' ) ) )}
    {section name=Log loop=$log_list}
       {node_view_gui view=line content_node=$:item}
    {/section}
{/let}

