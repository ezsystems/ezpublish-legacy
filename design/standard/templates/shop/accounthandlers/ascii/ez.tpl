Customer information:
{$order.account_information.first_name} {$order.account_information.last_name}{section show=$order.account_information.street1} ({$order.account_information.street1}){/section}

Email:
{$order.account_information.email}

Shipping address:
{$order.account_information.street2}
{$order.account_information.zip} {$order.account_information.place}

{section show=$order.account_information.state}{$order.account_information.state} {/section}{$order.account_information.country}
