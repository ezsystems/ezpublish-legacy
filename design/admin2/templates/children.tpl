<div class="content-view-children">

{* Generic children list for admin interface. *}
{* TODO: admin_children_viewmode should be replaced w/the table column preferences *} 
{def $item_type    = ezpreference( 'admin_list_limit' )
     $number_of_items = min( $item_type, 3)|choose( 10, 10, 25, 50 )
     $can_remove   = false()
     $can_move     = false()
     $can_edit     = false()
     $can_create   = false()
     $can_copy     = false()
     $current_path = first_set( $node.path_array[1], 1 )
     $admin_children_viewmode = ezpreference( 'admin_children_viewmode' )
     $children_count = fetch( content, list_count, hash( 'parent_node_id', $node.node_id,
                                                         'objectname_filter', $view_parameters.namefilter ) )
     $children    = array()
     $priority    = and( eq( $node.sort_array[0][0], 'priority' ), $node.can_edit, $children_count )
     $priority_dd = and( $priority, $admin_children_viewmode|ne( 'thumbnail' ), $view_parameters.offset|eq( 0 ) )}
     

<!-- Children START -->

<div class="context-block">
<form name="children" method="post" action={'content/action'|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />

{if $children_count}
    {set $children = fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                            'sort_by', $node.sort_array,
                                            'limit', $number_of_items,
                                            'offset', $view_parameters.offset,
                                            'objectname_filter', $view_parameters.namefilter ) )}
{/if}


{* DESIGN: Header START *}<div class="box-header">

    <div class="button-left">
    <h2 class="context-title"><a href={$node.depth|gt(1)|choose('/'|ezurl,$node.parent.url_alias|ezurl )} title="{'Up one level.'|i18n(  'design/admin/node/view/full'  )}"><img src={'up-16x16-grey.png'|ezimage} alt="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" title="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" /></a>&nbsp;{'Sub items (%children_count)'|i18n( 'design/admin/node/view/full',, hash( '%children_count', $children_count ) )}</h2>
    </div>

    <div class="button-right button-header">
    </div>

<div class="float-break"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">

{* If there are children: show list and buttons that belong to the list. *}
{if $children_count}

{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="float-break"></div>
</div>

    {* Copying operation is allowed if the user can create stuff under the current node. *}
    {set can_copy=$node.can_create}

    {* Check if the current user is allowed to *}
    {* edit or delete any of the children.     *}
    {section var=Children loop=$children}
        {if $Children.item.can_remove}
            {set can_remove=true()}
        {/if}
        {if $Children.item.can_move}
            {set $can_move=true()}
        {/if}
        {if $Children.item.can_edit}
            {set can_edit=true()}
        {/if}
        {if $Children.item.can_create}
            {set can_create=true()}
        {/if}
    {/section}

    <input type="hidden" name="NodeID" value="{$node.node_id}" />

    {* Display the actual list of nodes. *}
    {include uri='design:children_detailed.tpl'}

{* Else: there are no children. *}
{else}

<div class="block">
    <p>{'The current item does not contain any sub items.'|i18n( 'design/admin/node/view/full' )}</p>
</div>
<div class='block'>
    <fieldset>
        <legend>{'Create'|i18n( 'design/admin/node/view/full' )}</legend>
        {* The "Create new here" thing: *}
        {if and( $node.is_container,  $node.can_create)}
            <input type="hidden" name="NodeID" value="{$node.node_id}" />
            {if $node.path_array|contains( ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
                {def $group_id = array( ezini( 'ClassGroupIDs', 'Users', 'content.ini' ),
                                        ezini( 'ClassGroupIDs', 'Setup', 'content.ini' ) )}
            {elseif $node.path_array|contains( ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) )}
                {def $group_id = array( ezini( 'ClassGroupIDs', 'Setup', 'content.ini' ),
                                        ezini( 'ClassGroupIDs', 'Content', 'content.ini' ),
                                        ezini( 'ClassGroupIDs', 'Media', 'content.ini' ) )}
            {else}
                {def $group_id = false()}
            {/if}

            {def $can_create_classes = fetch( 'content', 'can_instantiate_class_list', hash( 'parent_node', $node,
                                                                                             'filter_type', 'exclude',
                                                                                             'group_id', $group_id,
                                                                                             'group_by_class_group', true() ) )}


            {def $can_create_languages = fetch( 'content', 'prioritized_languages' )
                 $content_locale = ezini( 'RegionalSettings', 'ContentObjectLocale' )}

            {if and( is_set( $can_create_languages[0] ), eq( $can_create_languages|count, 1 ) )}
                <select id="ClassID" name="ClassID" title="{'Use this menu to select the type of item you want to create then click the "Create here" button. The item will be created in the current location.'|i18n( 'design/admin/node/view/full' )|wash()}">
            {else}
                <select id="ClassID" name="ClassID" onchange="updateLanguageSelector(this)" title="{'Use this menu to select the type of item you want to create then click the "Create here" button. The item will be created in the current location.'|i18n( 'design/admin/node/view/full' )|wash()}">
            {/if}
                {foreach $can_create_classes as $group}
                    <optgroup label="{$group.group_name}">
                    {foreach $group.items as $can_create_class}
                        {if $can_create_class.can_instantiate_languages}
                        <option value="{$can_create_class.id}">{$can_create_class.name|wash()}</option>
                        {/if}
                    {/foreach}
                    </optgroup>
                {/foreach}
            </select>

            {if and( is_set( $can_create_languages[0] ), eq( $can_create_languages|count, 1 ) )}
                <input name="ContentLanguageCode" value="{$can_create_languages[0].locale}" type="hidden" />
            {else}
                <label for="ClassContentLanguageCode" class="inline">{'in'|i18n( 'design/admin/node/view/full' )}</label>
                <select id="ClassContentLanguageCode" name="ContentLanguageCode" onchange="checkLanguageSelector(this)" title="{'Use this menu to select the language you want to use for the creation then click the "Create here" button. The item will be created in the current location.'|i18n( 'design/admin/node/view/full' )|wash()}">
                    {foreach $can_create_languages as $tmp_language}
                        <option value="{$tmp_language.locale|wash()}"{if $content_locale|eq( $tmp_language.locale )} selected="selected"{/if}>{$tmp_language.name|wash()}</option>
                    {/foreach}
               </select>
            {/if}
        
            <input class="button" type="submit" name="NewButton" value="{'Here'|i18n( 'design/admin/node/view/full' )}" title="{'Create a new item in the current location. Use the menu on the left to select the type of  item.'|i18n( 'design/admin/node/view/full' )}" />
            <input type="hidden" name="ViewMode" value="full" />
        
            {if ne( $can_create_languages|count, 1 )}
            <script type="text/javascript">
            <!--
                {literal}
                function updateLanguageSelector( classSelector )
                {
                    var languageSelector = classSelector.form.ContentLanguageCode;
                    if ( !languageSelector )
                    {
                        return;
                    }
        
                    var classID = classSelector.value, languages = languagesByClassID[classID], candidateIndex = -1;
                    for ( var index = 0, length = languageSelector.options.length; index < length; index++ )
                    {
                        var value = languageSelector.options[index].value, disabled = true;
        
                        for ( var indexj = 0, lengthj = languages.length; indexj < lengthj; indexj++ )
                        {
                            if ( languages[indexj] == value )
                            {
                                disabled = false;
                                break;
                            }
                        }
        
                        if ( !disabled && candidateIndex == -1 )
                        {
                            candidateIndex = index;
                        }
        
                        languageSelector.options[index].disabled = disabled;
                        if ( disabled )
                        {
                            languageSelector.options[index].style.color = '#888';
                            if ( languageSelector.options[index].text.substring( 0, 1 ) != '(' )
                            {
                                languageSelector.options[index].text = '(' + languageSelector.options[index].text + ')';
                            }
                        }
                        else
                        {
                            languageSelector.options[index].style.color = '#000';
                            if ( languageSelector.options[index].text.substring( 0, 1 ) == '(' )
                            {
                                languageSelector.options[index].text = languageSelector.options[index].text.substring( 1, languageSelector.options[index].text.length - 1 );
                            }
                        }
                    }
        
                    if ( languageSelector.options[languageSelector.selectedIndex].disabled )
                    {
                        window.languageSelectorIndex = candidateIndex;
                        languageSelector.selectedIndex = candidateIndex;
                    }
                }
        
                function checkLanguageSelector( languageSelector )
                {
                    if ( languageSelector.options[languageSelector.selectedIndex].disabled )
                    {
                        languageSelector.selectedIndex = window.languageSelectorIndex;
                        return;
                    }
                    window.languageSelectorIndex = languageSelector.selectedIndex;
                }
        
                setTimeout( function() { updateLanguageSelector( document.getElementById( 'ClassID' ) ); }, 100 );
        
                var languagesByClassID = {};
                {/literal}
        
                {foreach $can_create_classes as $group}
                    {foreach $group.items as $class}
                    languagesByClassID[{$class.id}] = [ {foreach $class.can_instantiate_languages as $tmp_language}'{$tmp_language}'{delimiter}, {/delimiter} {/foreach} ];
                    {/foreach}
                {/foreach}
            // -->
            </script>
            {/if}
            {undef $can_create_languages $can_create_classes}
        {else}
            <select id="ClassID" name="ClassID" disabled="disabled">
            <option value="">{'Not available'|i18n( 'design/admin/node/view/full' )}</option>
            </select>
            <input class="button-disabled" type="submit" name="NewButton" value="{'Here'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to create new items in the current location.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
        {/if}
    </fieldset>
</div>
{/if}

{* DESIGN: Content END *}</div>

</form>

</div>

{* Load yui code for subitems diplay even if current node has no children (since cache blocks  does not vary by this) *}
{ezscript_require('ezjsc::yui2', 'ezjsc::yui3')}


{* Load drag and drop code if access rights are ok (but not depending on node sort as pagelayout cache-block does not include that in key) *}
{if $node.can_edit}
{ezscript_require( array( 'ezjsc::yui3', 'ezjsc::yui3io', 'ezajaxsubitems_sortdd.js' ) )}
{/if}

{* Execute drag and drop code if sortField=priority and access rights are ok *}
{if $priority_dd}
<script type="text/javascript">
eZAjaxSubitemsSortDD.init();
</script>
{/if}


<!-- Children END -->

{undef $item_type $number_of_items $can_remove $can_move $can_edit $can_create $can_copy $current_path $admin_children_viewmode $children_count $children}
</div>
