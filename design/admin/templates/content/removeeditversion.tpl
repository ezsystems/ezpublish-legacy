{let version=fetch( content, version, hash( object_id, $object_id,
                                            version_id, $object_version ) )}
<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/edit_draft' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<p>Test</p>

</div></div></div></div></div></div>

</div>

</div>
</div>

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

<form action={"content/removeeditversion"|ezurl} method="post" name="EditVersionRemove">

<div class="warning">
<h2>{"Are you sure you want to discard the draft %versionname?"
     |i18n( 'design/standard/content/edit',,
            hash( '%versionname', concat( '<i>', $version.version_name, '</i>' ) ) )}</h2>
</div>

<div class="buttonblock">
    {include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/content/edit")}
    {include uri="design:gui/defaultbutton.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/content/edit")}
</div>

</form>

{/let}

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
