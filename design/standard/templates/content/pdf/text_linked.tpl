{default object_name=$object.name}
{pdf(link, hash( url, concat('content/view/full/',$object.main_node_id)|ezurl(no),
                 text, $object_name|wash(pdf) ) )}
{/default}
