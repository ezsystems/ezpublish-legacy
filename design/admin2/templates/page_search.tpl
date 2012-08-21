{def $search_node_id = first_set( $search_subtree_array[0], $module_result.path[0].node_id, 1 )
     $search_title = "Search in all content"|i18n( 'design/admin/pagelayout' )}
<div class="searchblock">
<form action={'/content/search/'|ezurl} method="get">
    {if $ui_context_edit}
        <input id="searchtext" name="SearchText" class="disabled" type="text" size="20" value="{if is_set( $search_text )}{$search_text|wash}{/if}" disabled="disabled" title="{$search_title|wash}" />
        <input id="searchbutton" class="button-disabled hide" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" disabled="disabled" />
        <p class="advanced hide"><span class="disabled">{'Advanced'|i18n( 'design/admin/pagelayout' )}</span></p>
    {else}
        {if $search_node_id|gt( 1 )}
            {set $search_title = "Search in '%node'"|i18n( 'design/admin/pagelayout',, hash( '%node', fetch( 'content', 'node', hash( 'node_id', $search_node_id ) ).name ) )}
        {/if}
        <input id="searchtext" name="SearchText" type="text" size="20" value="{if is_set( $search_text )}{$search_text|wash}{/if}" title="{$search_title|wash}" />
        <input id="searchbutton" class="button hide" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" />
        {if eq( $ui_context, 'browse' ) }
            <input name="Mode" type="hidden" value="browse" />
            <input name="BrowsePageLimit" type="hidden" value="{min( ezpreference( 'admin_list_limit' ), 3)|choose( 10, 10, 25, 50 )}" />
            <p class="advanced hide"><span class="disabled">{'Advanced'|i18n( 'design/admin/pagelayout' )}</span></p>
        {else}
            <p class="advanced hide"><a href={'/content/advancedsearch'|ezurl} title="{'Advanced search.'|i18n( 'design/admin/pagelayout' )}">{'Advanced'|i18n( 'design/admin/pagelayout' )}</a></p>
        {/if}
        <div class="searchbuttonfield" id="searchbuttonfield"></div>
        <div class="searchscope" id="searchscope"></div>

        <div class="searchscope-menu-container">
            <div class="searchscope-pane" id="searchscope-pane">
                <a href="javascript:;" class="close" id="searchscope-pane-close"><span class="mini-icon mini-icon-remove-close"></span></a>
                <div class="searchscope-title">{'Search scope'|i18n( 'design/admin/pagelayout' )}</div>
                <div class="searchscope-body">
                    {def $disabled = false()
                         $nd = 1
                         $left_checked = true()
                         $current_loc = true()}
                    {if eq( $ui_context, 'edit' )}
                        {set $disabled = true()}
                    {else}
                        {if is_set( $module_result.node_id )}
                            {set $nd = $module_result.node_id}
                        {else}
                            {if is_set( $search_subtree_array )}
                                {if count( $search_subtree_array )|eq( 1 )}
                                    {if $search_subtree_array[0]|ne( 1 )}
                                        {set $nd = $search_subtree_array[0]}
                                        {set $left_checked = false()}
                                    {else}
                                        {set $disabled = true()}
                                    {/if}
                                    {set $current_loc = false()}
                                {else}
                                    {set $disabled = true()}
                                {/if}
                            {else}
                                {set $disabled = true()}
                            {/if}
                        {/if}
                    {/if}
                    <label>{'Section'|i18n( 'design/admin/pagelayout' )}</label>
                    <select name="SectionID"{if $disabled} disabled="disabled"{/if}>
                        <option value="-1">{'All'|i18n( 'design/admin/pagelayout' )}</option>
                        {foreach fetch( 'section', 'list' ) as $section}
                        <option value="{$section.id}">{$section.name|wash()}</option>
                        {/foreach}
                    </select>

                    <label{if $disabled} class="disabled"{/if}><input type="radio" name="SubTreeArray" value="{$search_node_id}" checked="checked" title="{$search_title}" />{$search_title}</label>
                    {if $search_node_id|ne( 1 )}
                    <label{if $disabled} class="disabled"{/if}><input type="radio" name="SubTreeArray" value="1"{if $disabled} disabled="disabled"{else} title="{'Search in all content'|i18n( 'design/admin/pagelayout' )}"{/if} />{'Search in all content'|i18n( 'design/admin/pagelayout' )}</label>
                    {/if}
                    <label{if $disabled} class="disabled"{/if}><input type="radio" name="SubTreeArray" value="{$nd}"{if $disabled} disabled="disabled"{else} title="{'Search only from the current location'|i18n( 'design/admin/pagelayout' )}"{/if} />{if $current_loc}{'Current location'|i18n( 'design/admin/pagelayout' )}{else}{'The same location'|i18n( 'design/admin/pagelayout' )}{/if}</label>
                    {undef $disabled $nd $left_checked $current_loc}
                </div>
            </div>
        </div>
    {/if}
</form>
</div>

<script type="text/javascript">

{literal}
(function($){
    if ( !document.getElementById('searchbuttonfield') )return;

    $('#searchbuttonfield').click(function(){
        if ( $('#searchtext').val() === $('#searchtext').attr('title') )return;
          $('#searchbutton').click();
    });
    $('#searchscope').click(function(){
        $('#searchscope-pane').addClass('active');
    });
    $('#searchscope-pane-close').click(function(){
        $('#searchscope-pane').removeClass('active');
    });
    $('input:radio[name=SubTreeArray]').change( function() {
        $('#searchtext').attr('value', $(this).attr('title'));
        $('#searchtext').attr('title', $(this).attr('title'));
    } );
})( jQuery );
{/literal}
</script>
{undef $search_node_id}
