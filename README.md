# Mautic EcommerceBundle

## Installation

- for now add the repository manually inside your composer.json

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../MauticEcommerceBundle"
    }
  ]
}
```
- then require it via composer
```
composer require 'webanyone/mautic-ecommerce-bundle:*'
```

## Configuration

Open the plugin page, and you must saw 2 plugins, prestashop & woocommerce

### Prestashop

Enabled
- url (must end with /api)
- token (readonly for now)
Feature
- check everything
Field mapping
- choose each field (not sure if we need this)

TODO: How to create a token in prestashop ?

### WooCommerce

Enabled
- url (must end with /wp-json/wc/v3)
- consumer key
- consumer secret
  Feature
- check everything
  Field mapping
- choose each field (not sure if we need this)

TODO: How to create a token in woocommerce ?

## Sync

Customer & Product of ecommerce solution are sync using sync engine of Mautic, to retrieve theme add the following cron to your server

```
bin/console mautic:integrations:sync -f -vvv -- WooCommerce
# or
bin/console mautic:integrations:sync -f -vvv -- Prestashop
```

Transaction are sync using a different command which must run after

```
bin/console ecommerce:transaction:import WooCommerce
# or
bin/console ecommerce:transaction:import Prestashop
```

## Segments

TODO: list & explain each of the filter choice provided

## Emails

You have access to information about the last transaction made by a lead inside the mails:

```
{last_transaction}
<ul>
    <li>{transaction:date}</li>
    <li>{transaction:price}</li>
    <li>{transaction:nb_products}</li>
    <li>
        {transaction_products}
            <ul>
                <li>{product:name}</li>
                <li>{product:unit_price}</li>
                <li>{product:quantity}</li>
            </ul>
        {/transaction_products}
    </li>
</ul>
{/last_transaction}
```

### The following tags can be used in the {last_transaction} block:

- `{transaction:date}`  
   Returns: the date the transaction was made.  
   Optional param: format {transaction:date format="d-m-Y H:i"}
- `{transaction:price}`  
   Returns: the total price, including taxes
- `{transaction:nb_products}`  
   Returns: how many different product is present inside the transaction

### The following tags can be used in the {transaction_products} block:

#### Merge tags

- `{product:name}`  
  Returns: the name of the product
- `{product:unit_price}`  
  Returns: the total price, including taxes
- `{product:quantity}`  
  Returns: how many item of this product is present inside the transaction
