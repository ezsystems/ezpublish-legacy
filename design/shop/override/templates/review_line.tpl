<div class="review">

<h2>{$node.name}</h2>
{$node.object.data_map.description.content|wash|autolink|wordtoimage|break}

<div class="rating">Rating: [{$node.object.data_map.rating.content} of 5 Stars!]</div>

</div>