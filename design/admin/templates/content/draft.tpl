{* Generic script for toggling the status of a bunch of checkboxes. *}
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
     list_count=fetch('content','draft_count')
     draft_list=fetch( content, draft_version_list, hash( limit, $number_of_items, offset, $view_parameters.offset ) )}

<form name="draftaction" action={concat( 'content/draft/' )|ezurl} method="post">

<div class="context-block">
<h2 class="context-title">{'My drafts [%draft_count]'|i18n(  'design/standard/content/draft',, hash( '%draft_count', $draft_list|count ) )}</h2>

{section show=$draft_list}

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

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" onclick="toggleCheckboxes( document.draftaction, 'DeleteIDArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" /></th>
    <th>{'Name'|i18n( 'design/standard/content/draft' )}</th>
    <th>{'Type'|i18n( 'design/standard/content/draft' )}</th>
    <th>{'Section'|i18n( 'design/standard/content/draft' )}</th>
    <th>{'Version'|i18n( 'design/standard/content/draft' )}</th>
    <th>{'Modified'|i18n( 'design/standard/content/draft' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Drafts loop=$draft_list sequence=array( bglight, bgdark )}
<tr class="{$Drafts.sequence}">
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$Drafts.item.id}" /></td>
    <td>{$Drafts.item.contentobject.content_class.identifier|class_icon( small, $Drafts.item.contentobject.content_class.name )}&nbsp;<a href={concat( '/content/versionview/', $Drafts.item.contentobject.id, '/', $Drafts.item.version, '/' )|ezurl}>{$Drafts.item.contentobject.name|wash}</a></td>
    <td>{$Drafts.item.contentobject.content_class.name|wash}</td>
    <td>{$Drafts.item.contentobject.section_id}</td>
    <td>{$Drafts.item.version}</td>
    <td>{$Drafts.item.modified|l10n( shortdatetime )}</td>
    <td><a href={concat( '/content/edit/', $Drafts.item.contentobject.id, '/', $Drafts.item.version, '/' )|ezurl}><img src={'edit.png'|ezimage} border="0"></a></td>
</tr>
{/section}
</table>
{section-else}

<p>{'There are no drafts.'|i18n( 'design/standard/content/draft' )}</p>

{/section}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/content/draft'
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/draft')}" {section show=$draft_list|not}disabled="disabled"{/section} />
    <input class="button" type="submit" name="EmptyButton"  value="{'Remove all'|i18n( 'design/admin/content/draft')}" {section show=$draft_list|not}disabled="disabled"{/section} />
</div>
</div>

</div>

{/let}