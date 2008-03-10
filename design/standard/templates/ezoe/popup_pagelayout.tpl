{* eZ Online Editor MCE popup pagelayout *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>{$module_result.persistent_variable.title}</title>
<script type="text/javascript">
<!--

var eZOeMCE = new Object();
eZOeMCE['root'] = {'/'|ezroot};
eZOeMCE['extension_url'] = {'/ezoe/'|ezurl};

// -->
</script>
    <script language="javascript" type="text/javascript" src={"javascript/tiny_mce_popup.js"|ezdesign}></script>

{if $module_result.persistent_variable.scripts}
{foreach $module_result.persistent_variable.scripts as $script}
    <script language="javascript" type="text/javascript" src={$script|ezdesign}></script>

{/foreach}
{/if}
<style>
<!--
{literal}

div#search_box_prev a { display: block; clear: both }
div#search_box_prev a.contenttype_image, .image-thumbnail-item a.contenttype_image
{
    float:left;
    margin-right: 6px;
}

div.slide
{
    margin: 0;
    padding: 1em;
    overflow: auto;
    border: 1px solid #ccc;
    width: 440px;
    background-color: #fff;
}

#tabs { height: 28px; }

#tabs div.tab
{
    float: left;
    margin-right: 10px;
    font: bold 12px Verdana, Arial, sans-serif;
    color: blue;
    cursor: pointer;
}

#tabs div.tab.accordion_selected { color: #999; }


#embed_preview_heading { margin: 14px 10px 2px 10px; color: #999; }
#embed_preview { text-align: center; }
#embed_preview.object_preview { margin: 0 10px 10px 10px; border: 1px solid #ddd; padding:5px; clear:both;float: left; }
#embed_preview img { margin: auto; }


-->
</style>
{/literal}
</head>
<body>

    <div class="block">
    {$module_result.content}
    </div>

<!--DEBUG_REPORT-->

</body>
</html>
