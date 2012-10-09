<div id="header-topmenu">
<ul>
{foreach topmenu($ui_context, true() ) as $menu}
    {include uri='design:page_topmenuitem.tpl' menu_item=$menu navigationpart_identifier=$navigation_part.identifier}
{/foreach}
</ul>
</div>

<script type="text/javascript">
{literal}
jQuery( '#header-topmenu ul li' ).click(function(){ jQuery(this).addClass('active'); });
{/literal}
</script>
