

abc

{$var}

start

{section name=Object loop=array(1,2)}

{$Object:item} - 

{switch name=sw match=$Object:item}
{case match=1}
{$Room1:var}
{$Object:sw:match}
{/case}

{case match=2}
:{$Object:item}:
{$Object:sw:match}
{/case}
{/switch}

{/section}

end

{include name=Room1 uri="scrap/room.tpl"}
{include name=Room2 uri="scrap/room.tpl"}
{include name=Room3 uri="scrap/room.tpl"}
{include name=Room4 uri="scrap/room.tpl"}
{include name=Room5 uri="scrap/room.tpl"}
{include name=Room6 uri="scrap/room.tpl"}
