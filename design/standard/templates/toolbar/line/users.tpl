{let logged_in_count=fetch( user, logged_in_count )
      anonymous_count=fetch( user, anonymous_count )}
{'%logged_in_count reg. & %anonymous_count anon. online.'|i18n( 'design/standard/toolbar', 'Short user information',
  hash( '%logged_in_count', $logged_in_count, '%anonymous_count', $anonymous_count ) )}
{/let}