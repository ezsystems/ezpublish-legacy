{let name="Sub1" 
     var1="kake"
     var2=concat($Sub1:var1, "mann")}
{set var1="balle"}
{$Sub1:var1}
{$Sub1:var2}
{$:var1}
{$:var2}
{$var1}
{$var2}
{let name="Sub2"
     var1="eZ"
     var2=concat($Sub1:var1, "brok")}
{$Sub1:Sub2:var1}
{$Sub1:Sub2:var2}
{$:var1}
{$:var2}
{$Sub1:var1}
{$Sub1:var2}
{/let}
{/let}


{default page_uri_suffix=false()
         left_max=7
         right_max=6
         item_limit=5
         item_count=30
         view_parameters=hash( offset, 0 )}

{default name=ViewParameter
         page_uri_suffix=false()
         left_max=$left_max
         right_max=$right_max}

{let item_previous=sub( $view_parameters.offset,
                        $item_limit )
     item_next=sum( $view_parameters.offset,
                    $item_limit )
     page_count=int( ceil( div( $item_count, $item_limit ) ) )
     current_page=int( ceil( div( $view_parameters.offset,
                                  $item_limit ) ) )

     left_length=min( $ViewParameter:current_page, $:left_max )
     right_length=min( sub( $ViewParameter:page_count, $ViewParameter:current_page, 1 ), $:right_max )
     view_parameter_text=""}

$:item_previous='{$:item_previous}'
$:item_next='{$:item_next}'
$:page_count='{$:page_count}'
$:current_page='{$:current_page}'
$:left_length='{$:left_length}'
$:right_length='{$:right_length}'
$:view_parameter_text='{$:view_parameter_text}'
$:right_max='{$:right_max}',$right_max='{$right_max}'
$:left_max='{$:left_max}',$left_max='{$left_max}'

{/let}

{/default}

{/default}
