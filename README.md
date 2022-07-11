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
