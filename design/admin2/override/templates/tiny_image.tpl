{default $object_parameters=array()}
{let image_variation="false"
     align="center"
     attribute_parameters=$object_parameters}
{if is_set($attribute_parameters.size)}
{set image_variation=$object.data_map.image.content[$attribute_parameters.size]}
{else}
{set image_variation=$object.data_map.image.content[ezini( 'ImageSettings', 'DefaultEmbedAlias', 'content.ini' )]}
{/if}

{if is_set($link_parameters.href)}<a href={$link_parameters.href|ezurl} target="{$link_parameters.target|wash}"{if is_set($link_parameters.class)} class="{$link_parameters.class|wash}"{/if}{if is_set($link_parameters['xhtml:id'])} id="{$link_parameters['xhtml:id']|wash}"{/if}{if is_set($link_parameters['xhtml:title'])} title="{$link_parameters['xhtml:title']|wash}"{/if}>{/if}
<img src={$image_variation.full_path|ezroot} alt="{$object.data_map.image.content.alternative_text|wash(xhtml)}" />
{if is_set($link_parameters.href)}</a>{/if}
{/let}
{/default}
