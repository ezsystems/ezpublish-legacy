{array( "a", "b", "c" )|repeat(3)|implode(', ')}
{"eZ "|repeat(3)}

{let arr=array( "a", "b", "c" )}
{$arr|repeat(3)|implode(', ')}
{/let}


{let str="eZ "}
{$str|repeat(3)}
{/let}