{let item_type=ezpreference( 'admin_list_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     object_list=fetch( content, trash_object_list, hash( limit,  $number_of_items,
                                                          offset, $view_parameters.offset ) )
     list_count=fetch( content, trash_count ) }

<form name="trashform" action={'content/trash/'|ezurl} method="post" >

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Trash [%list_count]'|i18n( 'design/admin/content/trash',, hash( '%list_count', $list_count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$object_list}

{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
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
<div class="break"></div>
</div>
</div>

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Invert selection." onclick="ezjs_toggleCheckboxes( document.trashform, 'DeleteIDArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/trash' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/content/trash')}</th>
    <th>{'Type'|i18n( 'design/admin/content/trash')}</th>
    <th>{'Section'|i18n( 'design/admin/content/trash')}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Objects loop=$object_list sequence=array( bglight, bgdark )}
<tr class="{$Objects.sequence}">
    <td>
    <input type="checkbox" name="DeleteIDArray[]" value="{$Objects.item.id}" title="{'Use these checkboxes to mark items for removal. Click the "Remove selected" button to actually remove the selected items.'|i18n( 'design/admin/content/trash' )|wash()}" />
    </td>
    <td>
    {$Objects.item.content_class.identifier|class_icon( small, $Objects.item.content_class.name )}&nbsp;<a href={concat( '/content/versionview/', $Objects.item.id, '/', $Objects.item.current_version, '/' )|ezurl}>{$Objects.item.name|wash}</a>
    </td>
    <td>
    {$Objects.item.content_class.name|wash}
    </td>
    <td>
    {let section_object=fetch( section, object, hash( section_id, $Objects.item.section_id ) )}{section show=$section_object}{$section_object.name|wash}{section-else}<i>{'Unknown section'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit' )}</i>{/section}{/let}
    </td>
    <td>
    <a href={concat( '/content/edit/', $Objects.item.id, '/' )|ezurl}><img src={'edit.png'|ezimage} border="0" alt="{'Edit'|i18n( 'design/admin/content/trash' )}" /></a>
    </td>
</tr>
{/section}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/content/trash'
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{section-else}

<div class="block">
    <p>{'There are no items in the trash'|i18n( 'design/admin/content/trash' )}.</p>
</div>

{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
{section show=$object_list}
    <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/trash' )}"  title="{'Permanently remove the selected items.'|i18n( 'design/admin/content/trash' )}" />
    <input class="button" type="submit" name="EmptyButton"  value="{'Empty trash'|i18n( 'design/admin/content/trash' )}" title="{'Permanently remove all items from the trash.'|i18n( 'design/admin/content/trash' )}" />
{section-else}
    <input class="button-disabled" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/trash' )}" disabled="disabled" />
    <input class="button-disabled" type="submit" name="EmptyButton"  value="{'Empty trash'|i18n( 'design/admin/content/trash' )}" disabled="disabled" />
{/section}
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

{/let}
