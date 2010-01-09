{def $languages=fetch('content', 'prioritized_languages')
     $classes=fetch( 'content', 'can_instantiate_class_list', hash( 'parent_node', $node_id ) )
     $class=false()
     $can_create=false()}

{foreach $classes as $tmp_class}
    {if $tmp_class.id|eq($class_id)}
        {set $class=$tmp_class}
        {break}
    {/if}
{/foreach}

<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h4>{'Object information'|i18n( 'design/admin/content/edit_languages' )}</h4>

{* DESIGN: Header END *}</div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Object ID *}
<p>
<label>{'ID'|i18n( 'design/admin/content/edit_languages' )}:</label>
0
</p>

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/edit_languages' )}:</label>
{'Not yet published'|i18n( 'design/admin/content/edit_languages' )}
</p>

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/edit_languages' )}:</label>
{'Not yet published'|i18n( 'design/admin/content/edit_languages' )}
</p>

{* Published version *}
<p>
<label>{'Published version'|i18n( 'design/admin/content/edit_languages' )}:</label>
{'Not yet published'|i18n( 'design/admin/content/edit_languages' )}
</p>

</div></div></div></div></div></div>

</div>

</div>
</div>

<div id="maincontent">
<div id="maincontent-design" class="float-break"><div id="fix">
<!-- Maincontent START -->

<form name="CreateLanguages" action={'content/action'|ezurl} method="post">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Language selection'|i18n( 'design/admin/content/create_languages' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">

{if or($class|not,$class.can_instantiate_languages|not)}

<p>{'You do not have permission to create an object of the requested class in any language.'|i18n( 'design/admin/content/create_languages' )}</p>

{else}

    {set $can_create=true()}
    {def $language_codes=$class.can_instantiate_languages}

    <p>{'Select the language in which you want to create the object'|i18n( 'design/admin/content/create_languages' )}:</p>

    {foreach $languages as $language}
        {if $language_codes|contains($language.locale)}
            <label>
                <input name="ContentLanguageCode" type="radio" value="{$language.locale}"{run-once} checked="checked"{/run-once} /> {$language.name|wash}
            </label>
            <div class="labelbreak"></div>
        {/if}
    {/foreach}

{/if}

<input type="hidden" name="NodeID" value="{$node_id|wash}" />
<input type="hidden" name="ClassID" value="{$class_id|wash}" />

{if $assignment_remote_id}
    <input type="hidden" name="AssignmentRemoteID" value="{$assignment_remote_id|wash}" />
{/if}
{if $redirect_uri_after_publish}
    <input type="hidden" name="RedirectURIAfterPublish" value="{$redirect_uri_after_publish|wash}" />
{/if}
{if $redirect_uri_after_discard}
    <input type="hidden" name="RedirectIfDiscarded" value="{$redirect_uri_after_discard|wash}" />
{/if}

<input type="hidden" name="CancelURI" value="content/view/full/{$node_id|wash}" />

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">
    {if $can_create}
        <input class="button" type="submit" name="NewButton" value="{'OK'|i18n('design/admin/content/create_languages')}" />
        <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/admin/content/create_languages')}" />
    {else}
        <input class="button" type="submit" name="CancelButton" value="{'OK'|i18n('design/admin/content/create_languages')}" />
    {/if}
</div>

{* DESIGN: Control bar END *}</div></div>

</div>

</div>

</form>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
