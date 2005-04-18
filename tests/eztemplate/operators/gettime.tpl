{let time=gettime( $timestamp )}

{section loop=$time}
{section show=ne($key, 'epoch')}
{$key} = {$item}

{/section}
{/section}

{/let}
