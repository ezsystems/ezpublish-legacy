{* Generate complete PDF file from definition and stream or save it *}

{pdf(execute, $pdf_definition)}

{section show=$generate_stream|eq(1)}
{/section}

{section show=$generate_file|eq(1)}
  {pdf(close, $filename)}
{/section}