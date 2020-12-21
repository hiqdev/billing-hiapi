# Billing API Overview

Billing API provides endpoints to create and manage Sales.

## Creating a new Sale

Briefly, can be done in several steps:

1. Search for the available Tariff Plans with a [PlansSearch](#PlansSearch) command. 

When the command is called with the customer's identity, it returns tariff plans, marked as public by the seller or personally assigned to a Customer. The plans are available for self purchasing.

The command can be also called without authentication, supplied with a query parameter `where[available_for_seller]=$SELLER_LOGIN`. This will show tariff plans, assigned as public by the seller, specified in `$SELLER_LOGIN`.

Each tariff consists of a set of prices and may include some additional information. See examples in [PlansSearch](#planssearch-for-plans-and-get-its-prices)

2. Then purchase a Target with `PurchaseTarget` command.
This command creates a Sale i.e. connection between user, billed object (named Target) and a (tariff) Plan.
After running a `PurchaseTarget` command, the billing starts and continues until the sale gets removed with the `SaleClose` command.

The list of current active sales can be retrieved with command [`SalesSearch`](#salessearch).

3. Billing charges users with Bills. Each bill is comprised of charges, each representing a price, that produced it.
The list of bills and charges can be received with the [`BillsSearch`](#billssearch) command.

---


## PlansSearch – search for plans and get its prices

A response is a data structure as follows, each record represents a tariff plan:

```json
{
    data: [
        { id: 2, name: "Anycast CDN: 5 TB", seller: {…}, prices: {…} },
        { id: 5, name: "Anycast CDN: 25 TB", seller: {…}, prices: {…} },
        { id: 6, name: "Anycast CDN: 50 TB", seller: {…}, prices: {…} },
        { id: 8, name: "Anycast CDN: 100 TB", seller: {…}, prices: {…} },
        { id: 9, name: "Anycast CDN: 500 TB", seller: {…}, prices: {…} }
    ]
}
```

### Price meaning

Each `price` consists of the following data:

```json
{
    id: 231,
    type: { id: 421, name: "overuse,storage_du" },
    price: { amount: "3000", currency: "USD" },
    prepaid: { unit: "tb", quantity: "0.5" },
    target: []
}
```

- **type**: a billable service *type*. The name is unique among the billing system. The name is an well-known comma-separated string of tags. The quantity of comma-separated tags and their values may vary, but there is a convention in naming of the first block:

  - `monthly` – billed once a month when the Sale is active regardless of the consumption
  - `overuse` – billed once a month depending on the consumed amount of service

- **price**: how much costs the consumption of one *unit* of this service.

- **prepaid**: contains a measure *unit* of this price and the *quantity* that is prepaid in this tariff plan. The prepaid *quantity* will NOT be charged. The example price will cost `3 000 USD` for each *TB* of the `storage_du` (storage disk usage), if exceeds the *prepaid.quantity* of `0.5 TB`

- **target**: when empty, the price will be applied to any service, sold by this tariff plan. When filled, it reduces the price applicability scope to the specific object, e.g. service, or its specific part.

### Calculations of a total monthly price of the tariff plan

The total monthly price of a Plan can be calculation by summing all `monthly` prices in it.


## PurchaseTarget – Creates a new Sale of the Target

 Something that can be billed is called `Target`. In order to buy a target, call the `PurchaseTarget` command using the Customer's identity. See [Swagger](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_TargetsPurchase) for the request type and details.

```json
{
  "customer_username": "string",
  "customer_id": "string",
  "plan_id": "string",
  "plan_name": "string",
  "plan_seller": "string",
  "name": "string",
  "type": "string",
  "remoteid": "string",
  "time": "string"
}
```

- **customer_username**, **customer_id** (*optional*) – must be filled, when the authenticated Customer purchases a Target on behalf of other Customer
- **plan_id** – ID of plan among of available via [PlansSearch](#planssearchsearch-for-plans-and-get-its-prices)
- **plan_name**, **plan_seller** (*optional*) – either **plan_id**, or a pair of a Plan name and Seller login must be provided
- **name** – arbitrary string, the service name, as is registered at the Service Provider subsystem
- **remoteid** – arbitrary string, the service ID, as is registered at the Service Provider subsystem. Will be used to find a proper Target, or to create a new one.
- **type** – the Target type. Muse be supported by billing. E.g.: `anycastcdn`, `videocdn`, etc. Will be used to find a proper Target, or to create a new one.
- **time** - the ISO8601 time, since when the Target should be billed. Might be useful for the trial period. If the sale date is greater than the start of current month, the `monthly` prices will be charged proportionally to the active service time in the month. If the date is less then current month, charges will NOT be created for the previous billing periods.

Success command run results in creation of a sale, the response contains an `id` of the Target, registered in our system. It's recommended to save is as a foreign identifier of the **remoteid**.

## SaleClose – interrupt the sale and stop charging

```json
{
  "customer_id": "string",
  "plan_id": "string",
  "target_id": "string",
  "time": "string"
}
```

- **customer_id** (*optional*) – defaults to currently authenticated Customer. Must be filled only when the Sale is getting closed belongs to another Customer.
- **plan_id** – the Plan ID, sale is active for
- **target_id** – the Target ID
- **time** – the ISO8601 time of the active Sale

## SalesSearch

> // TBD

## BillsSearch

> // TBD

# Aliases for API Gateway compatibility

- GET `/api/v1/clients` - alias to [clientsSearch](http://swagger.hiqdev.com/#/client/post_clientsSearch)
- GET `/api/v1/client/:id` - alias to [clientGetInfo?id=:id](http://swagger.hiqdev.com/#/client/post_clientGetInfo)
- GET `/api/v1/plans` - alias to [PlansSearch](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_PlansSearch)
- GET `/api/v1/plans/public` - alias to [plansGetAvailable](http://swagger.hiqdev.com/#/client/post_plansGetAvailable)
- GET `/api/v1/plan/:id` - alias to [PlanGetInfo?id=:id](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_PlanGetInfo)
- GET `/api/v1/targets` - alias to [TargetsSearch](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_TargetsSearch)
- GET `/api/v1/target/:id` - alias to [TargetGetInfo?id=:id](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_TargetGetInfo)
- POST `/api/v1/target` - alias to [TargetPurchase](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_TargetPurchase)
- GET `/api/v1/sales` - alias to [SalesSearch](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_SalesSearch)
- POST `/api/v1/sales` - alias to [SaleCreate](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_SaleCreate)
- DELETE `/api/v1/sales` - alias to [SaleClose](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_SaleClose)
- GET `/api/v1/bills` - alias to [BillsSearch](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_BillsSearch)
- GET `/api/v1/bill/:id` - alias to [BillGetInfo?id=:id](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_BillGetInfo)
- POST `/api/v1/feature/purchase` - alias to [FeaturePurchase](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_FeaturePurchase)
- POST `/api/v1/feature/cancel` - alias to [FeatureCancel](http://swagger.hiqdev.com/?urls.primaryName=Billing%20API#/default/post_FeatureCancel)