{if is_unset( $file_name_attribute )}
    {def $file_name_attribute = ''}
{/if}
<tr>
    <td class="column1"><label id="titlelabel" for="objectName">{'Name'|i18n('design/standard/ezoe')}</label></td>
    <td colspan="2"><input id="objectName" name="objectName" size="40" type="text" value="" title="{'Name for the uploaded object, filename is used if none is specified.'|i18n('design/standard/ezoe/wai')}" /></td>
</tr>
<tr>
    <td class="column1"><label id="srclabel" for="fileName">{'File'|i18n('design/standard/ezoe')}</label></td>
    <td colspan="2"><input name="fileName" type="file" id="fileName" size="40" {$file_name_attribute} value="" title="{'Choose file to upload from your local machine.'|i18n('design/standard/ezoe/wai')}" /></td>
</tr>
<tr id="embedlistsrcrow">
    <td class="column1"><label for="location">{'Location'|i18n('design/standard/ezoe')}</label></td>
    <td colspan="2" id="embedlistsrccontainer">
      <select name="location" id="location" title="{'Lets you specify where in eZ Publish to store the uploaded object.'|i18n('design/standard/ezoe/wai')}">
        <option value="auto">{'Automatic'|i18n('design/standard/ezoe')}</option>

        {if $object.published}
            <option value="{$object.main_node_id}">{$object.name|shorten( 35 )} ({'this'|i18n('design/standard/ezoe')})</option>
        {/if}

        {def $root_node_value = ezini( 'LocationSettings', 'RootNode', 'upload.ini' )
             $root_node = cond( $root_node_value|is_numeric, fetch( 'content', 'node', hash( 'node_id', $root_node_value ) ),
                             fetch( 'content', 'node', hash( 'node_path', $root_node_value ) ) )
             $selection_list = fetch( 'content', 'list',
                                     hash( 'parent_node_id', $root_node.node_id,
                                           'class_filter_type', include,
                                           'class_filter_array', ezini( 'LocationSettings', 'ClassList', 'upload.ini' ),
                                           'load_data_map', false(),
                                           'sort_by', $root_node.sort_array|append( array('name', true() ) ),
                                           'limit', ezini( 'LocationSettings', 'MaxItems', 'upload.ini' ) ) )
             $selection_list_2 = 0
             $selection_list_3 = 0
             $selection_depth = ezini( 'LocationSettings', 'MaxDepth', 'upload.ini' )}
        {foreach $selection_list as $item}
            {if $item.can_create}
                <option value="{$item.node_id}">{$item.name|wash|shorten( 35 )}</option>
            {/if}
            {if and( $selection_depth|ge( 2 ), first_set( $item.is_container, $item.object.content_class.is_container, false() ) )}
                {set $selection_list_2 = fetch( 'content', 'list',
                                         hash( 'parent_node_id', $item.node_id,
                                               'class_filter_type', include,
                                               'class_filter_array', ezini( 'LocationSettings', 'ClassList', 'upload.ini' ),
                                               'load_data_map', false(),
                                               'sort_by', $item.sort_array|append( array('name', true() ) ),
                                               'limit', ezini( 'LocationSettings', 'MaxItems', 'upload.ini' ) ) )}
                {foreach $selection_list_2 as $item_2}
                    {if $item_2.can_create}
                        <option value="{$item_2.node_id}">&nbsp;{$item_2.name|wash|shorten( 35 )}</option>
                    {/if}
                    {if and( $selection_depth|ge( 3 ), first_set( $item_2.is_container, $item_2.object.content_class.is_container, false() ) )}
                        {set $selection_list_3 = fetch( 'content', 'list',
                                                 hash( 'parent_node_id', $item_2.node_id,
                                                       'class_filter_type', include,
                                                       'class_filter_array', ezini( 'LocationSettings', 'ClassList', 'upload.ini' ),
                                                       'load_data_map', false(),
                                                       'sort_by', $item_2.sort_array|append( array('name', true() ) ),
                                                       'limit', ezini( 'LocationSettings', 'MaxItems', 'upload.ini' ) ) )}
                        {foreach $selection_list_3 as $item_3}
                            {if $item_3.can_create}
                                <option value="{$item_3.node_id}">&nbsp;&nbsp;{$item_3.name|wash|shorten( 35 )}</option>
                            {/if}
                        {/foreach}
                    {/if}
                {/foreach}

            {/if}
        {/foreach}

      </select>
    </td>
</tr>