<form action={concat( 'url/edit/', $url.id )|ezurl} method="post">
<div class="context-block">
<h2 class="context-title">{'url'|icon( 'normal', 'URL'|i18n( 'design/admin/url/list' ) )}&nbsp;{'Edit URL #%url_id'|i18n( 'design/admin/url/view',, hash( '%url_id', $url.id ) )}</h2>

    <div class="context-attributes">
        <div class="block">
            <label>{'Address'|i18n( 'design/admin/url/edit' )}</label>
            {include uri="design:gui/lineedit.tpl" name=link id_name=link value=$url.url}
        </div>
    </div>

    <div class="controlbar">
        <div class="block">
            <input class="button" type="submit" name="Store" value="{'OK'|i18n( 'design/admin/url/edit' )}" />
            <input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n( 'design/admin/url/edit' )}" />
        </div>
    </div>
</div>
</form>
