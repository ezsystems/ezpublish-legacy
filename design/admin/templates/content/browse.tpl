{let item_type=ezpreference( 'items' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     browse_list_count=fetch(content,list_count,hash(parent_node_id,$node_id,depth,1))
     object_array=fetch(content,list,hash(parent_node_id,$node_id,depth,1,offset,$view_parameters.offset,limit,$number_of_items,sort_by,$main_node.sort_array))
     select_name='SelectedObjectIDArray'
     select_type='checkbox'
     select_attribute='contentobject_id'}

{section show=eq($browse.return_type,'NodeID')}
    {set select_name='SelectedNodeIDArray'}
    {set select_attribute='node_id'}
{/section}

{section show=eq($browse.selection,'single')}
    {set select_type='radio'}
{/section}

{section show=$browse.description_template}
    {include name=Description uri=$browse.description_template browse=$browse main_node=$main_node}
{section-else}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Browse'|i18n('design/admin/content/browse')}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

<p>{'To select objects, choose the appropriate radiobutton or checkbox(es), and click the "Choose" button.'|i18n('design/admin/content/browse')}</p>
<p>{'To select an object that is a child of one of the displayed objects, click the object name and you will get a list of the children of the object.'|i18n('design/admin/content/browse')}</p>

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/section}


<div class="context-block">

<form action={$browse.from_page|ezurl} method="post">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

{let current_node=fetch( content, node, hash( node_id, $browse.start_node ) )}
{section show=$browse.start_node|gt(1)}
    <h2 class="context-title">
    <a href={concat("/content/browse/",$main_node.parent_node_id,"/")|ezurl}><img src={'back-button-16x16.gif'|ezimage} alt="{'Back'|i18n( 'design/admin/content/browse' )}" /></a>
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
        <a href={'/user/preferences/set/items/1'|ezurl}>10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/items/3'|ezurl}>50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/items/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/items/2'|ezurl}>25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/items/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/items/3'|ezurl}>50</a>
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
    &nbsp;
    </th>
    <th class="wide">
    {'Name'|i18n('design/admin/content/browse')}
    </th>
    <th class="tight">
    {'Type'|i18n('design/admin/content/browse')}
    </th>
</tr>
<!--
<tr>
    <td>
    {section show=and( or( $browse.permission|not,
                           cond( is_set( $browse.permission.contentclass_id ),
                                 fetch( content, access, hash( access,          $browse.permission.access,
                                                               contentobject,   $main_node,
                                                               contentclass_id, $browse.permission.contentclass_id ) ),
                                 fetch( content, access, hash( access,          $browse.permission.access,
                                                               contentobject,   $main_node ) ) ) ),
                                 $browse.ignore_nodes_select|contains( $main_node.node_id )|not() )}

        {section show=is_array($browse.class_array)}
	        {section show=$browse.class_array|contains($main_node.object.content_class.identifier)}
                <input type="{$select_type}" name="{$select_name}[]" value="{$main_node[$select_attribute]}" {section show=eq($browse.selection,'single')}checked="checked"{/section} />
            {section-else}
                <input type="{$select_type}" name="" value="" disabled="disabled" />
            {/section}
        {section-else}
	        <input type="{$select_type}" name="{$select_name}[]" value="{$main_node[$select_attribute]}" {section show=eq($browse.selection,'single')}checked="checked"{/section} />
        {/section}

    {section-else}

    <input type="{$select_type}" name="" value="" disabled="disabled" />

    {/section}

    </td>

    <td>

    {*$main_node.object.class_identifier|class_icon(small, $main_node.object.class_name)node_view_gui view=line content_node=$main_node node_url=false()*}

    {section show=$main_node.depth|gt(1)}
        <a href={concat("/content/browse/",$main_node.parent_node_id,"/")|ezurl}>[{'Up one level'|i18n('design/admin/content/browse')}]</a>
    {/section}
    </td>

<td>
{$main_node.object.content_class.name|wash}
</td>

</tr>
-->
{section name=Object loop=$object_array sequence=array( bglight, bgdark )}
    <tr class="{$Object:sequence}">
    <td>
    {section show=and( or( $browse.permission|not,
                           cond( is_set( $browse.permission.contentclass_id ),
                                 fetch( content, access, hash( access,          $browse.permission.access,
                                                               contentobject,   $:item,
                                                               contentclass_id, $browse.permission.contentclass_id ) ),
                                 fetch( content, access, hash( access,          $browse.permission.access,
                                                               contentobject,   $:item ) ) ) ),
                           $browse.ignore_nodes_select|contains($:item.node_id)|not() )}
        {section show=is_array($browse.class_array)}
            {section show=$browse.class_array|contains($:item.object.content_class.identifier)}
                <input type="{$select_type}" name="{$select_name}[]" value="{$:item[$select_attribute]}" />
            {section-else}
                <input type="{$select_type}" name="" value="" disabled="disabled" />
            {/section}
        {section-else}
            <input type="{$select_type}" name="{$select_name}[]" value="{$:item[$select_attribute]}" />
        {/section}
    {section-else}
        <input type="{$select_type}" name="" value="" disabled="disabled" />
    {/section}
    </td>
    <td>

    {* Replaces node_view_gui... *}
    {section show=$browse.ignore_nodes_click|contains( $Object:item.node_id )|not}
        {$Object:item.object.class_name|class_icon( small, $Object:item.object.class_identifier )}&nbsp;<a href="{concat( '/content/browse/', $Object:item.node_id )|ezurl}">{$Object:item.name|wash}</a>
    {section-else}
        {$Object:item.object.class_name|class_icon( small, $Object:item.object.class_identifier )}&nbsp;{$Object:item.name|wash}
    {/section}

    {* node_view_gui view=line content_node=$Object:item node_url=cond( $browse.ignore_nodes_click|contains($Object:item.node_id)|not(), concat( 'content/browse/', $Object:item.node_id, '/' ), false() )*}


    </td>
    <td>
    {$Object:item.object.content_class.name|wash}
    </td>
 </tr>
{/section}
</table>

<div class="context-toolbar">
{include name=Navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/browse/',$main_node.node_id)
         item_count=$browse_list_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{section name=Persistent show=$browse.persistent_data loop=$browse.persistent_data}
    <input type="hidden" name="{$:key|wash}" value="{$:item|wash}" />
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
<input class="button" type="submit" name="SelectButton" value="{'OK'|i18n('design/admin/content/browse')}" />
<input class="button" type="submit" name="BrowseCancelButton" value="{'Cancel'|i18n( 'design/admin/content/browse' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</form>

{/let}

</div>
