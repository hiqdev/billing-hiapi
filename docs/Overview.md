# Billing API Overview

Briefly work with billing can be represented in several steps:

Start with getting available tariffs with `PlansSearch` command.

Then purchase object with `PurchaseTarget` command.
This command creates subscription i.e. conection between user, object (target) and tariff.
After issuing this command billing for the object starts and continues until the subscription
is removed with command `SaleClose`.
List of current subscription can be received with command `SalesSearch`.

Billing charges users with bills (comprised of charges).
List of bills and charges can be received with `BillsSearch` command.

## PlansSearch

## PurchaseTarget

## SaleClose

## SalesSearch

## BillsSearch
