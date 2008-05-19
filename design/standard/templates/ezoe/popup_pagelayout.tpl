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
<script type="text/javascript" src={"javascript/tiny_mce_popup.js"|ezdesign}></script>
<script type="text/javascript" src={"javascript/utils/validate.js"|ezdesign}></script>

{if $module_result.persistent_variable.scripts}
{foreach $module_result.persistent_variable.scripts as $script}
    <script type="text/javascript" src={$script|ezdesign}></script>

{/foreach}
{/if}
<style type="text/css">
<!--
{literal}

div#search_box_prev a { display: block; clear: both }
div#search_box_prev a.contenttype_image, .image-thumbnail-item a.contenttype_image
{
    float:left;
    margin-right: 6px;
}

table#browse_box_prev { border-collapse: collapse; }
table#browse_box_prev thead td { padding-bottom: 5px; }
table#browse_box_prev tfoot td { padding-top: 5px; }

#embed_preview_heading { margin: 14px 10px 2px 10px; color: #999; }
#embed_preview { text-align: center; }
#embed_preview.object_preview { margin: 0 10px 10px 10px; border: 1px solid #ddd; padding: 5px; clear: both; float: left; height: auto;}
#embed_preview img { margin: auto; }

#table_cell_size_grid { border-spacing: 0px; border-collapse: collapse; border: 1px solid #ccc; margin-bottom: 4px; }
#table_cell_size_grid td { padding: 0px; }
#table_cell_size_grid td div { width: 12px; height: 12px; border: 1px solid #fff;  }

-->
</style>
{/literal}
{if $module_result.persistent_variable.css}
{foreach $module_result.persistent_variable.css as $css}
    <link type="text/css" rel="stylesheet" href={$css|ezdesign} />

{/foreach}
{/if}
</head>
<body>

    <div class="block">
    {$module_result.content}
    </div>

<!--DEBUG_REPORT-->

</body>
</html>
