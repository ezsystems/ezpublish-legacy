{let browse_indentation=5
     page_limit=15
     browse_list_count=fetch(content,list_count,hash(parent_node_id,$node_id,depth,1))
     object_array=fetch(content,list,hash(parent_node_id,$node_id,depth,1,offset,$view_parameters.offset,limit,$page_limit,sort_by,$main_node.sort_array))
     bookmark_list=fetch('content','bookmarks',array())
     recent_list=fetch('content','recent',array())

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

<form action={concat($browse.from_page)|ezurl} method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>

    <td width="80%" valign="top">

{section show=$browse.description_template}
    {include name=Description uri=$browse.description_template browse=$browse main_node=$main_node}
{section-else}
    <div class="maincontentheader">
    <h1>{"Browse"|i18n("design/standard/content/view")} - {$main_node.name|wash}</h1>
    </div>

    <p>{'To select objects, choose the appriate radiobutton or checkbox(es), and click the "Choose" button.'|i18n("design/standard/content/view")}</p>
    <p>{'To select an object that is a child of one of the displayed objects, click the object name and you will get a list of the children of the object.'|i18n("design/standard/content/view")}</p>
{/section}

        {* Browse listing start *}
        <table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <th width="1">
            </th>
            <th width="69%">
            {"Name"|i18n("design/standard/content/view")}
            </th>
            <th width="30%">
            {"Class"|i18n("design/standard/content/view")}
            </th>
            <th width="30%">
            {"Section"|i18n("design/standard/content/view")}
            </th>
        </tr>
        <tr>
            <td class="bglight">
                <input type="{$select_type}" name="{$select_name}[]" value="{$main_node[$select_attribute]}" {section show=eq($browse.selection,'single')}checked="checked"{/section} />
            </td>
        
            <td class="bglight">
                <img src={"class_2.png"|ezimage} border="0" alt="{'Document'|i18n('design/standard/node/view')}" />
                {$main_node.name|wash}
                {section show=$main_node.depth|gt(1)}
                    <a href={concat("/content/browse/",$main_node.parent_node_id,"/")|ezurl}>
                        [{'Up one level'|i18n('design/standard/content/view')}]
                    </a>
                {/section}
            </td>
        
            <td class="bglight">
            {$main_node.object.content_class.name|wash}
            </td>
        
            <td class="bglight">
            {$main_node.object.section_id}
            </td>
        </tr>
        {section name=Object loop=$object_array sequence=array(bgdark,bglight)}
        <tr class="{$Object:sequence}">
            <td>
                <input type="{$select_type}" name="{$select_name}[]" value="{$:item[$select_attribute]}" />
            </td>
        
            <td>
                <img src={"1x1.gif"|ezimage} width="{mul(sub($:item.depth,$main_node.depth),$browse_indentation)}" height="1" alt="" border="0" />
            <img src={"class_2.png"|ezimage} border="0" alt="{'Document'|i18n('design/standard/node/view')}" />
            <a href={concat("/content/browse/",$Object:item.node_id,"/")|ezurl}>
            {$Object:item.name|wash}
                </a>
            </td>
        
            <td>
                    {$Object:item.object.content_class.name|wash}
            </td>
        
            <td>
                    {$:item.object.section_id}
            </td>
        </tr>
        {/section}
        <tr>
            <td colspan="4">
                <div class="buttonblock">
                    <input class="button" type="submit" name="SelectButton" value="{'Select'|i18n('design/standard/content/view')}" />
                </div>
            </td>
        </tr>
        </table>
        {* Browse listing end *}

    </td>

    <td width="20">
    </td>

    <td width="200" valign="top">

        {* Recent and bookmark start *}
        <table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr>
            <th colspan="2">
                {"Top levels"|i18n("design/standard/content/view")}
            </th>
        </tr>

        <tr>
            <td colspan="2">
                {'Switch top levels by clicking one of these items.'|i18n('design/standard/content/view')}
            </td>
        </tr>
        
        {section name=TopLevel loop=$browse.top_level_nodes
                 sequence=array(bgdark,bglight)}
        <tr class="{$:sequence}">
            <td width="1">
                <input type="{$select_type}" name="{$select_name}[]" value="{$:item}" />
            </td>
        
            <td>
                <img src={"class_2.png"|ezimage} border="0" alt="{'Document'|i18n('design/standard/node/view')}" />
                {section show=eq($:item,$main_node.node_id)}
                    {fetch(content,node,hash(node_id,$:item)).name|wash}
                {section-else}
                    <a href={concat("/content/browse/",$:item,"/")|ezurl}>
                        {fetch(content,node,hash(node_id,$:item)).name|wash}
                    </a>
                {/section}
            </td>
        </tr>
        {/section}

        <tr>
            <th colspan="2">
                {"Bookmarks"|i18n("design/standard/content/view")}
            </th>
        </tr>
        
        {section name=Bookmark loop=$bookmark_list show=$bookmark_list sequence=array(bgdark,bglight)}
        <tr class="{$:sequence}">
            <td width="1">
                <input type="{$select_type}" name="{$select_name}[]" value="{$:item.node[$select_attribute]}" />
            </td>
        
            <td>
                <img src={"class_2.png"|ezimage} border="0" alt="{'Document'|i18n('design/standard/node/view')}" />
                {section show=eq($:item.node_id,$main_node.node_id)}
                    {$:item.node.name|wash}
                {section-else}
                    <a href={concat("/content/browse/",$:item.node_id,"/")|ezurl}>
                        {$:item.node.name|wash}
                    </a>
                {/section}
            </td>
        </tr>
        {section-else}
        <tr>
            <td colspan="2">
                {'Bookmark items are managed using %bookmarkname in the %personalname part.'
                 |i18n('design/standard/content/view',,
                       hash('%bookmarkname',concat('<i>','My bookmarks'|i18n('design/admin/layout'),'</i>'),
                            '%personalname',concat('<i>','Personal'|i18n('design/admin/layout'),'</i>')))}
            </td>
        </tr>
        {/section}

        <tr height="6">
            <td>
            </td>
        </tr>

        <tr>
            <th colspan="2">
                {"Recent items"|i18n("design/standard/content/view")}
            </th>
        </tr>
        
        {section show=$recent_list}
            {section name=Recent loop=$recent_list sequence=array(bgdark,bglight)}
            <tr class="{$:sequence}">
                <td width="1">
                    <input type="{$select_type}" name="{$select_name}[]" value="{$:item[$select_attribute]}" />
                </td>
            
                <td>
                    <img src={"class_2.png"|ezimage} border="0" alt="{'Document'|i18n('design/standard/node/view')}" />
                    {section show=eq($:item.node_id,$main_node.node_id)}
                        {$:item.name|wash}
                    {section-else}
                        <a href={concat("/content/browse/",$:item.node_id,"/")|ezurl}>
                            {$:item.name|wash}
                        </a>
                    {/section}
                </td>
            </tr>
            {/section}
        {section-else}
        <tr>
            <td colspan="2">
                {'Recent items are added on publishing.'|i18n('design/standard/content/view')}
            </td>
        </tr>
        {/section}
        </table>
        {* Recent and bookmark end *}

    </td>

</tr>
</table>

{include name=Navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/browse/',$main_node.node_id)
         item_count=$browse_list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}


{section name=Persistent show=$browse.persistent_data loop=$browse.persistent_data}
    <input type="hidden" name="{$:key|wash}" value="{$:item|wash}" />
{/section}

<input type="hidden" name="BrowseActionName" value="{$browse.action_name}" />
{section show=$browse.browse_custom_action}
<input type="hidden" name="{$browse.browse_custom_action.name}" value="{$browse.browse_custom_action.value}" />
{/section}

</form>
{/let}
