# BILLING TESTING

## General idea

We have tests of different levels:

- unit tests for in-class functionality
  (done in every package)
- BDD-tests for charges calculation for all expected cases
  (done in [php-billing] and specific billing implementations)
- PACTs for API interactions
- acceptance tests for billing is really properly calculated
  for manually created cases

## Acceptance tests

- insert all the needed billing data:
    - customers and targets - with DB-migrations
        - all billable targets to be covered!
    - plans, sales and resource consumption - through API (tests API)
        - all used plan types to be covered!
- check everything is calculated properly
    - check ad-hoc calculations - through API
    - run billing calculation - throuh CLI (in prod run with CRON)
    - compare calculated values
        - get bills and charges - through API
        - compare with precalculated values
- clean - through API or DB
    - no need to clean customers and targets
- repeat
- profit

[php-billing]: https://github.com/hiqdev/php-billing
