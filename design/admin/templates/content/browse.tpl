{let item_type=ezpreference( 'admin_list_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     browse_list_count=fetch( content, list_count, hash( parent_node_id, $node_id, depth, 1))
     object_array=fetch( content, list, hash( parent_node_id, $node_id, depth, 1, offset, $view_parameters.offset, limit, $number_of_items, sort_by, $main_node.sort_array ) )
     select_name='SelectedObjectIDArray'
     select_type='checkbox'
     select_attribute='contentobject_id'}

{section show=eq( $browse.return_type, 'NodeID' )}
    {set select_name='SelectedNodeIDArray'}
    {set select_attribute='node_id'}
{/section}

{section show=eq( $browse.selection, 'single' )}
    {set select_type='radio'}
{/section}

{section show=$browse.description_template}
    {include name=Description uri=$browse.description_template browse=$browse main_node=$main_node}
{section-else}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Browse'|i18n( 'design/admin/content/browse' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

<p>{'To select objects, choose the appropriate radiobutton or checkbox(es), and click the "Choose" button.'|i18n( 'design/admin/content/browse' )}</p>
<p>{'To select an object that is a child of one of the displayed objects, click the object name and you will get a list of the children of the object.'|i18n( 'design/admin/content/browse' )}</p>

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/section}


<div class="context-block">

<form name="browse" method="post" action={$browse.from_page|ezurl}>

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

{let current_node=fetch( content, node, hash( node_id, $browse.start_node ) )}
{section show=$browse.start_node|gt( 1 )}
    <h2 class="context-title">
    <a href={concat( '/content/browse/', $main_node.parent_node_id, '/' )|ezurl}><img src={'back-button-16x16.gif'|ezimage} alt="{'Back'|i18n( 'design/admin/content/browse' )}" /></a>
    {$current_node.object.content_class.identifier|class_icon( small, $current_node.object.content_class.name )}&nbsp;{$current_node.name|wash}&nbsp;[{$current_node.children_count}]</h2>
{section-else}
    <h2 class="context-title"><img src={'back-button-16x16.gif'|ezimage} alt="Back" /> {'folder'|class_icon( small, $current_node.object.content_class.name )}&nbsp;{'Top level'|i18n( 'design/admin/content/browse' )}&nbsp;[{$current_node.children_count}]</h2>
{/section}
{/let}

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={concat( '/user/preferences/set/admin_list_limit/1/content/browse/', $node_id ) |ezurl}>10</a>
        <span class="current">25</span>
        <a href={concat( '/user/preferences/set/admin_list_limit/3/content/browse/', $node_id )|ezurl}>50</a>

        {/case}

        {case match=50}
        <a href={concat( '/user/preferences/set/admin_list_limit/1/content/browse/', $node_id )|ezurl}>10</a>
        <a href={concat( '/user/preferences/set/admin_list_limit/2/content/browse/', $node_id )|ezurl}>25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={concat( '/user/preferences/set/admin_list_limit/2/content/browse/', $node_id )|ezurl}>25</a>
        <a href={concat( '/user/preferences/set/admin_list_limit/3/content/browse/', $node_id )|ezurl}>50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="break"></div>
</div>
</div>

{* Browse listing start *}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">
    {section show=eq( $select_type, 'checkbox' )}
        <img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/content/browse' )}" title="{'Invert selection.'|i18n( 'design/admin/content/browse' )}" onclick="ezjs_toggleCheckboxes( document.browse, '{$select_name}[]' ); return false;" />
    {section-else}
        &nbsp;
    {/section}
    </th>
    <th class="wide">
    {'Name'|i18n( 'design/admin/content/browse' )}
    </th>
    <th class="tight">
    {'Type'|i18n( 'design/admin/content/browse' )}
    </th>
</tr>

{section var=Nodes loop=$object_array sequence=array( bglight, bgdark )}
    <tr class="{$Nodes.sequence}">
    <td>
    {section show=and( or( $browse.permission|not,
                           cond( is_set( $browse.permission.contentclass_id ),
                                 fetch( content, access, hash( access,          $browse.permission.access,
                                                               contentobject,   $Nodes.item,
                                                               contentclass_id, $browse.permission.contentclass_id ) ),
                                 fetch( content, access, hash( access,          $browse.permission.access,
                                                               contentobject,   $Nodes.item ) ) ) ),
                           $browse.ignore_nodes_select|contains( $Nodes.item.node_id )|not() )}
        {section show=is_array( $browse.class_array )}
            {section show=$browse.class_array|contains( $Nodes.item.object.content_class.identifier )}
                <input type="{$select_type}" name="{$select_name}[]" value="{$Nodes.item[$select_attribute]}" />
            {section-else}
                <input type="{$select_type}" name="" value="" disabled="disabled" />
            {/section}
        {section-else}
            {section show=and( or( eq( $browse.action_name, 'MoveNode' ), eq( $browse.action_name, 'CopyNode' ) ), $Nodes.item.object.content_class.is_container|not )}
                <input type="{$select_type}" name="{$select_name}[]" value="{$Nodes.item[$select_attribute]}" disabled="disabled" />
            {section-else}
                <input type="{$select_type}" name="{$select_name}[]" value="{$Nodes.item[$select_attribute]}" />

            {/section}
        {/section}
    {section-else}
        <input type="{$select_type}" name="" value="" disabled="disabled" />
    {/section}
    </td>
    <td>

    {* Replaces node_view_gui... *}
    {section show=$browse.ignore_nodes_click|contains( $Nodes.item.node_id )|not}
        {section show=and( or( ne( $browse.action_name, 'MoveNode' ), ne( $browse.action_name, 'CopyNode' ) ), $Nodes.item.object.content_class.is_container )}
            {$Nodes.item.object.class_identifier|class_icon( small, $Nodes.item.object.class_name )}&nbsp;<a href={concat( '/content/browse/', $Nodes.item.node_id )|ezurl}>{$Nodes.item.name|wash}</a>
        {section-else}
            {$Nodes.item.object.class_identifier|class_icon( small, $Nodes.item.object.class_name )}&nbsp;{$Nodes.item.name|wash}
        {/section}
    {section-else}
        {$Nodes.item.object.class_identifier|class_icon( small, $Nodes.item.object.class_name )}&nbsp;{$Nodes.item.name|wash}
    {/section}

    </td>
    <td class="class">
    {$Nodes.item.object.content_class.name|wash}
    </td>
 </tr>
{/section}
</table>

<div class="context-toolbar">
{include name=Navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/content/browse/', $main_node.node_id )
         item_count=$browse_list_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{section var=PersistentData show=$browse.persistent_data loop=$browse.persistent_data}
    <input type="hidden" name="{$PersistentData.key|wash}" value="{$PersistentData.item|wash}" />
{/section}

<input type="hidden" name="BrowseActionName" value="{$browse.action_name}" />
{section show=$browse.browse_custom_action}
    <input type="hidden" name="{$browse.browse_custom_action.name}" value="{$browse.browse_custom_action.value}" />
{/section}

{section show=$cancel_action}
<input type="hidden" name="BrowseCancelURI" value="{$cancel_action}" />
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="SelectButton" value="{'OK'|i18n( 'design/admin/content/browse' )}" />
    <input class="button" type="submit" name="BrowseCancelButton" value="{'Cancel'|i18n( 'design/admin/content/browse' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</form>

{/let}

</div>
