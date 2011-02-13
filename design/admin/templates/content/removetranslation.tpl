<form method="post" action={'content/translation'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Confirm translation removal'|i18n( 'design/admin/node/removeobject' )}</h2>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<p>{"Are you sure you want to remove the following translations from object <%1>?"|i18n("design/standard/node",,hash("%1",$object.name))|wash}</p>
</div>

<table class="list" cellspacing="0">
    <tr>
        <th>{"Language"|i18n("design/standard/content/removetranslation")}</th>
    </tr>
    {foreach $languages as $language sequence array( bglight, bgdark ) as $class}
    <tr class="{$class}">
        <td><input type="hidden" name="LanguageID[]" value="{$language.id}" />{$language.name|wash}</td>
    </tr>
    {/foreach}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

    <input type="hidden" name="ContentObjectID" value="{$object_id|wash}" />
    <input type="hidden" name="ContentNodeID" value="{$node_id|wash}" />
    <input type="hidden" name="ContentObjectLanguageCode" value="{$language_code|wash}" />
    <input type="hidden" name="ViewMode" value="{$view_mode|wash}" />
    <input type="hidden" name="ConfirmRemoval" value="1" />

    <input type="submit" class="button" name="RemoveTranslationButton" value="{'OK'|i18n( 'design/admin/node/removeobject' )}" />
    <input type="submit" class="button" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/node/removeobject' )}" title="{'Cancel the removal of translations.'|i18n( 'design/admin/node/removeobject' )}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>
