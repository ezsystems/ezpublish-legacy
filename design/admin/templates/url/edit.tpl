<form action={concat( 'url/edit/', $url.id )|ezurl} method="post">
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'url'|icon( 'normal', 'URL'|i18n( 'design/admin/url/edit' ) )}&nbsp;{'Edit URL #%url_id'|i18n( 'design/admin/url/edit',, hash( '%url_id', $url.id ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">


    <div class="context-attributes">
        <div class="block">
            <label>{'Address'|i18n( 'design/admin/url/edit' )}:</label>
            <input class="box" id="address" type="text" name="link" value="{$url.url}" />
        </div>
    </div>

{* DESIGN: Content END *}</div></div></div>

    <div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
        <div class="block">
            <input class="button" type="submit" name="Store" value="{'OK'|i18n( 'design/admin/url/edit' )}" />
            <input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n( 'design/admin/url/edit' )}" />
        </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
    </div>
</div>
</form>

{literal}
<script language="JavaScript" type="text/javascript">
<!--
    window.onload=function()
    {
        document.getElementById('address').select();
        document.getElementById('address').focus();
    }
-->
</script>
{/literal}
