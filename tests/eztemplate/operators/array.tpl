{array( 1, 2, 3, 4)|implode( ", ")}

{let kake=4}
{array( 1, 2, 3, $kake)|implode( ", " )}
{/let}


{let arr=array(1,2)}
$arr|array_prepend(3)|implode(',')='{$arr|array_prepend(3)|implode(',')}'
$arr|array_append(3)|implode(',')='{$arr|array_append(3)|implode(',')}'
$arr|array_merge(3,4)|implode(',')='{$arr|array_merge(3,4)|implode(',')}'
{/let}
