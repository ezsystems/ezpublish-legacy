{pdf(link, hash( url, $attribute.content.filepath|ezurl,
                 text, $attribute.content.original_filename|wash(pdf)))}
{pdf(text, $attribute.content.filesize|si(byte)|wash(pdf))}