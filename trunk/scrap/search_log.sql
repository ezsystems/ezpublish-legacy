SELECT count(*) as count, ezsearch_search_phrase.*FROM
  ezsearch_search_phrase,
  ezsearch_return_count
WHERE
  ezsearch_search_phrase.id = ezsearch_return_count.phrase_id
GROUP BY
  ezsearch_return_count.phrase_id
