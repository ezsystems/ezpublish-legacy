{literal}
<script language="JavaScript1.2" type="text/javascript">
<!--
function toggleCheckboxes( formname, checkboxname )
{
    with( formname )
	{
        for( var i=0; i<elements.length; i++ )
        {
            if( elements[i].type == 'checkbox' && elements[i].name == checkboxname && elements[i].disabled == "" )
            {
                if( elements[i].checked == true )
                {
                    elements[i].checked = false;
                }
                else
                {
                    elements[i].checked = true;
                }
            }
	    }
    }
}
//-->
</script>
{/literal}

{let item_type=ezpreference( 'items' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     object_list=fetch( content, trash_object_list, hash( limit,  $number_of_items,
                                                          offset, $view_parameters.offset ) )
     list_count=fetch( 'content', 'trash_count' ) }

{section show=$object_list}

<div class="context-block">
<form name="trashform" action={'content/trash/'|ezurl} method="post" >
<h2 class="context-title">{'Trash'|i18n( 'design/admin/content/trash' )} [{$list_count}]</h2>

{* Items per page selector. *}
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

<table class="list" cellspacing="0">
<tr>
    <th><img src={'toggle-button-16x16.gif'|ezimage} alt="Toggle selection" onclick="toggleCheckboxes( document.trashform, 'DeleteIDArray[]' ); return false;"/></th>
    <th>{'Name'|i18n( 'design/admin/content/trash ')}</th>
    <th>{'Type'|i18n( 'design/admin/content/trash ')}</th>
    <th>{'Section'|i18n( 'design/admin/content/trash ')}</th>
    <th>&nbsp;</th>
</tr>
{section var=Objects loop=$object_list sequence=array( bglight, bgdark )}
<tr class="{$Objects.sequence}">
    <td>
    <input type="checkbox" name="DeleteIDArray[]" value="{$Objects.item.id}" />
    </td>
    <td>
    {$Objects.item.content_class.identifier|class_icon( small, $Objects.item.content_class.name )}&nbsp;    <a href={concat( '/content/versionview/', $Objects.item.id, '/', $Objects.item.current_version, '/' )|ezurl}>{$Objects.item.name|wash}</a>
    </td>
    <td>
    {$Objects.item.content_class.name|wash}
    </td>
    <td>
    {fetch( section, object, hash( section_id, $Objects.item.section_id ) ).name|wash} ({$Objects.item.section_id})
    </td>
    <td>
    <a href={concat( '/content/edit/', $Objects.item.id, '/' )|ezurl}><img src={'edit.png'|ezimage} border="0"></a>
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

<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/trash' )}"  title="{'Permanently remove the selected items.'|i18n( 'design/admin/content/trash' )}" />
<input class="button" type="submit" name="EmptyButton"  value="{'Empty trash'|i18n( 'design/admin/content/trash' )}" title="{'Permanently remove all items from the trash.'|i18n( 'design/admin/content/trash' )}" />
</div>
</div>
</form>
</div>

{section-else}

<div class="feedback">
<h2>{'There are no items in the trash.'|i18n( 'design/admin/content/trash' )}</h2>
</div>

{/section}

{/let}
