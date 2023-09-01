<style type="text/css">
{literal}
table.list td { max-width: 500px; word-wrap: break-word; }
table.list td.width-280 { max-width: 280px; }
.mt-20 { margin-top: 20px; }
{/literal}
</style>
{let item_type = ezpreference( 'admin_list_limit' )
     number_of_items = min( $item_type, 3)|choose( 10, 10, 25, 50 )
     trash_sort_field = first_set(  $view_parameters.sort_field, 'trashed' )
     trash_sort_order = first_set(  $view_parameters.sort_order, '0' )
     list_count = fetch( 'content', 'trash_count', hash( 'objectname_filter', $view_parameters.namefilter ) ) }

{def
    $sort_method = 1
    $sort_order  = ''
    $col_class   = ''
    $link_order  = 1
}

{if eq($trash_sort_order, '0')}
    {set $sort_order = 'Descending'|i18n( 'design/admin/node/view/full' )}
{elseif eq($trash_sort_order, '1')}
    {set $sort_order = 'Ascending'|i18n( 'design/admin/node/view/full' )}
{/if}

<form name="trashform" action={'content/trash/'|ezurl} method="post" >

<div class="context-block content-trash">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Trash (%list_count)'|i18n( 'design/admin/content/trash',, hash( '%list_count', $list_count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{if $list_count}
{* Items per page selector. *}
<div class="context-toolbar">
<div class="button-left">
    <p class="table-preferences">
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/admin_list_limit/1'|ezurl}>10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_list_limit/3'|ezurl}>50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_list_limit/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/admin_list_limit/2'|ezurl}>25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_list_limit/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/admin_list_limit/3'|ezurl}>50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="float-break"></div>
</div>

<div class="content-navigation-childlist yui-dt">
    <table class="list" cellspacing="0">
    <tr>
        <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} width="16" height="16" alt="Invert selection." onclick="ezjs_toggleCheckboxes( document.trashform, 'DeleteIDArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/trash' )}" /></th>
        {* set Name column link & asc/desc icon *}
        {set
            $col_class  = ''
            $link_order = 1
        }
        {if eq( 'name', $trash_sort_field )}
            {if eq($trash_sort_order, '0')}
                {set $col_class = ' yui-dt-desc'}
            {elseif eq($trash_sort_order, '1')}
                {set
                    $col_class  = ' yui-dt-asc'
                    $link_order = 0
                }
            {/if}
        {/if}
        <th class="yui-dt-col-name yui-dt-sortable{$col_class}">
            <div id="yui-dt0-th-class_name-liner" class="yui-dt-liner">
                <span class="yui-dt-label">
                    <a href="{concat( 'content/trash/(sort_field)/name/(sort_order)/', $link_order )|ezurl( 'no' )}" title="Click to sort {$sort_order}" class="yui-dt-sortable">{'Name'|i18n( 'design/adin/content/trash')}</a>
                </span>
            </div>
        </th>
        {* set Class name column link & asc/desc icon *}
        {set
            $col_class  = ''
            $link_order = 1
        }
        {if eq( 'class_name', $trash_sort_field )}
            {if eq($trash_sort_order, '0')}
                {set $col_class = ' yui-dt-desc'}
            {elseif eq($trash_sort_order, '1')}
                {set
                    $col_class  = ' yui-dt-asc'
                    $link_order = 0
                }
            {/if}
        {/if}
        <th class="yui-dt-col-name yui-dt-sortable{$col_class}">
            <div id="yui-dt0-th-class_name-liner" class="yui-dt-liner">
                <span class="yui-dt-label">
                    <a href="{concat( 'content/trash/(sort_field)/class_name/(sort_order)/', $link_order )|ezurl( 'no' )}" title="Click to sort {$sort_order}" class="yui-dt-sortable">{'Type'|i18n( 'design/admin/content/trash')}</a>
                </span>
            </div>
        </th>
        {* set Section column link & asc/desc icon *}
        {set
            $col_class  = ''
            $link_order = 1
        }
        {if eq( 'section', $trash_sort_field )}
            {if eq($trash_sort_order, '0')}
                {set $col_class = ' yui-dt-desc'}
            {elseif eq($trash_sort_order, '1')}
                {set
                    $col_class  = ' yui-dt-asc'
                    $link_order = 0
                }
            {/if}
        {/if}
        <th class="yui-dt-col-name yui-dt-sortable{$col_class}">
            <div id="yui-dt0-th-class_name-liner" class="yui-dt-liner">
                <span class="yui-dt-label">
                    <a href="{concat( 'content/trash/(sort_field)/section/(sort_order)/', $link_order )|ezurl( 'no' )}" title="Click to sort {$sort_order}" class="yui-dt-sortable">{'Section'|i18n( 'design/admin/content/trash')}</a>
                </span>
            </div>
        </th>
        <th class="yui-dt-col-name yui-dt-sortable">{'Original Placement'|i18n( 'design/admin/content/trash')}</th>
        {* set Trashed column link & asc/desc icon *}
        {set
            $col_class  = ''
            $link_order = 1
        }
        {if eq( 'trashed', $trash_sort_field )}
            {if eq($trash_sort_order, '0')}
                {set $col_class = ' yui-dt-desc'}
            {elseif eq($trash_sort_order, '1')}
                {set
                    $col_class  = ' yui-dt-asc'
                    $link_order = 0
                }
            {/if}
        {/if}
        <th class="yui-dt-col-name yui-dt-sortable{$col_class}">
            <div id="yui-dt0-th-class_name-liner" class="yui-dt-liner">
                <span class="yui-dt-label">
                    <a href="{concat( 'content/trash/(sort_field)/trashed/(sort_order)/', $link_order )|ezurl( 'no' )}" title="Click to sort {$sort_order}" class="yui-dt-sortable">{'Date trashed'|i18n( 'design/admin/content/trash')}</a>
                </span>
            </div>
        </th>
        <th class="tight yui-dt-col-name yui-dt-sortable">&nbsp;</th>
    </tr>

    {section var=tObjects loop=fetch( 'content', 'trash_object_list', hash( 'limit',  $number_of_items,
                                                                            'offset', $view_parameters.offset,
                                                                            'sort_by', array( $trash_sort_field, $trash_sort_order ),
                                                                            'objectname_filter', $view_parameters.namefilter ) ) sequence=array( bglight, bgdark )}
    {let cur_c_object=$tObjects.item.object
         original_parent = $tObjects.item.original_parent}

    <tr class="{$tObjects.sequence}">
        <td>
        <input type="checkbox" name="DeleteIDArray[]" value="{$cur_c_object.id}" title="{'Use these checkboxes to mark items for removal. Click the "Remove selected" button to remove the selected items.'|i18n( 'design/admin/content/trash' )|wash()}" />
        </td>
        <td>
        {$tObjects.item.class_identifier|class_icon( small, $tObjects.item.class_name|wash )}&nbsp;<a href={concat( '/content/versionview/', $cur_c_object.id, '/', $cur_c_object.current_version, '/' )|ezurl}>{$tObjects.item.name|wash}</a>
        </td>
        <td>
        {$tObjects.item.class_name|wash}
        </td>
        <td>
        {let section_object=fetch( section, object, hash( section_id, $cur_c_object.section_id ) )}{section show=$section_object}{$section_object.name|wash}{section-else}<i>{'Unknown'|i18n( 'design/admin/content/trash' )}</i>{/section}{/let}
        </td>
        <td class="width-280">
        {if $original_parent}<a href={concat( '/', $original_parent.path_identification_string )|ezurl}>{/if}/{$tObjects.item.original_parent_path_id_string|wash}{if $original_parent}</a>{/if}
        </td>
        <td>
        {$tObjects.item.trashed|l10n( 'shortdatetime' )}
        </td>
        <td>
        <a href={concat( '/content/restore/', $cur_c_object.id, '/' )|ezurl}><img src={'edit.gif'|ezimage} border="0" alt="{'Restore'|i18n( 'design/admin/content/trash' )}" /></a>
        </td>
    </tr>

    {/let}
    {/section}
    </table>
</div>

{else}

<div class="block">
    <p>{'There are no items in the trash'|i18n( 'design/admin/content/trash' )}.</p>
</div>

{/if}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/alphabetical.tpl'
         page_uri='/content/trash'
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$number_of_items
         show_google_navigator=true()}
</div>


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="button-left mt-20">
    {if $list_count}
        <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/trash' )}"  title="{'Permanently remove the selected items.'|i18n( 'design/admin/content/trash' )}" />
        <input class="button" type="submit" name="EmptyButton"  value="{'Empty trash'|i18n( 'design/admin/content/trash' )}" title="{'Permanently remove all items from the trash.'|i18n( 'design/admin/content/trash' )}" />
    {else}
        <input class="button-disabled" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/trash' )}" disabled="disabled" />
        <input class="button-disabled" type="submit" name="EmptyButton"  value="{'Empty trash'|i18n( 'design/admin/content/trash' )}" disabled="disabled" />
    {/if}
</div>

</div>

<div class="float-break"></div>
{* DESIGN: Control bar END *}</div></div>
</div>
</div>
</form>
{/let}

