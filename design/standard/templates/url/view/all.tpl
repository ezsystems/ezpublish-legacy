<form method="post" action={concat("url/list/all",$view_parameters.offset|gt(0)|choose(,array("/offset/",$view_parameters)))|ezurl}>
{let url_limit=10
     url_count=fetch('url','list_count')
     url_list=fetch('url','list',hash(offset,$view_parameters.offset,limit,$url_limit))}
<h1>All URLs</h1>
{include uri="design:url/header.tpl" current_view_id='all'}

{"NPK"|texttoimage('arial')}

{*
{image(
imagefile('var/cache/texttoimage/church.jpg'),
array("NPK"|texttoimage('arial'),hash(transparency,0.8,halign,right))
)
}
*}

{image(
imagefile('var/cache/texttoimage/church.jpg'),
array(imagefile('var/cache/texttoimage/odin.jpg'),hash(transparency,0.5,halign,center,valign,center)),
array("Midgard"|texttoimage('arial'),hash(transparency,0.2,halign,center,valign,top,yrel,0.3))
)
}

{include uri="design:url/url_list.tpl"
         url_list=$url_list url_count=$url_count
         view_parameters=$view_parameters
         show_make_valid=true()
         show_make_invalid=true()
         current_view_id='all'}

{/let}
</form>
