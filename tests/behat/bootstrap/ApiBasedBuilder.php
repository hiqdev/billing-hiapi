<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\behat\bootstrap;

use hiqdev\billing\hiapi\plan\PlanFactory;
use hiqdev\billing\hiapi\price\PriceFactory;
use hiqdev\billing\hiapi\target\TargetFactory;
use hiqdev\billing\hiapi\type\TypeFactory;
use hiqdev\php\billing\sale\SaleFactory;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\tests\behat\bootstrap\BuilderInterface;
use hiqdev\php\billing\tests\support\tools\SimpleFactory;

class ApiBasedBuilder implements BuilderInterface
{
    private ApiClient $client;

    protected SimpleFactory $factory;

    protected string $reseller;

    protected string $customer;

    protected string $manager;

    protected string $admin;

    protected string $root;

    protected array $prices = [];

    protected array $plan;

    protected static $lastPlan;
    protected static array $plans = [];

    protected array $sale;

    protected array $actions = [];

    public function __construct()
    {
        $this->client = new ApiClient();
        $this->factory = new SimpleFactory([
            'plan'      => new PlanFactory(),
            'price'     => new PriceFactory(),
            'target'    => new TargetFactory(),
            'type'      => new TypeFactory(),
            'sale'      => new SaleFactory(),
        ]);
    }

    public function buildReseller(string $login): void
    {
        $this->reseller = $login;
    }

    public function buildCustomer(string $login): void
    {
        $this->customer = $login;
        $this->makeAsCustomer('CleanForTests');
    }

    public function buildPlan(string $name, string $type, bool $grouping = false): void
    {
        $this->prices = [];
        $plan = [
            'name'          => $name,
            'type'          => $type,
            'is_grouping'   => $grouping,
            'currency'      => 'usd',
            'is_available'  => true,
        ];
        if (empty(static::$plans[$name]) || !empty(array_diff($plan, static::$plans[$name]))) {
            static::$plans[$name] = $plan;
        }
    }

    public function buildPrice(array $data): void
    {
        $this->prices[] = $data;
    }

    public function performBilling(string $time): void
    {
        $this->makeAsReseller('client-perform-billing', [
            'client' => $this->customer,
            'time' => $time,
        ]);
    }

    public function recreatePlan(string $name): void
    {
        if (!empty(static::$plans[$name]['id'])) {
            return;
        }
        $this->deletePlan($name);
        $this->createPlan($name);
        $this->assignPlan($name);
        $this->createPrices($name);
    }

    public function createPrices(string $name): void
    {
        $plan = static::$plans[$name];
        foreach ($this->prices as &$price) {
            $price['plan_id'] = $plan['id'] ?? null;
            $price['quantity'] = $price['prepaid'] ?? 0;
            $price['currency'] = $this->preparePriceCurrency($price['currency']);
            $price['object'] = $price['target'] ?? null;
            if (isset($price['sums'])) {
                $price['sums'] = array_map(fn ($price) => (int) ((float) $price * 100), $price['sums']);
                $price['price'] = 0;
                $price['class'] = 'CertificatePrice';
            }
            $this->makeAsReseller('price-create', $price);
        }
    }

    private function preparePriceCurrency(?string $currency): ?string
    {
        if ($currency === '%') {
            return null;
        }

        return strtolower($currency);
    }

    public function deletePlan(string $name): void
    {
        $plan = static::$plans[$name];
        $found = $this->makeAsReseller('plans-search', ['name' => $name, 'limit' => 1]);
        $old = reset($found);
        if (empty($old)) {
            return;
        }
        $plan['id'] = $old['id'];
        $this->makeAsReseller('tariff-delete', $plan);
        $this->makeAsReseller('tariff-delete', $plan);
    }

    public function createPlan(string $name): array
    {
        $plan = static::$plans[$name];
        $plan = array_merge($plan, $this->makeAsReseller('plan-create', $plan));
        static::$plans[$name] = $plan;
        static::$lastPlan = $plan;
        $this->plan = $plan;

        return $plan;
    }

    public function assignPlan(string $name)
    {
        $assignedTariffsResponse = $this->makeAsReseller('client-get-tariffs', [
            'client' => $this->customer,
        ]);

        $tariff_ids = [];
        if ($assignedTariffsResponse['tariff_ids'] !== null) {
            $tariff_ids = explode(',', $assignedTariffsResponse['tariff_ids']);
        }

        $this->makeAsReseller('client-set-tariffs', [
            'client' => $this->customer,
            'tariff_ids' => array_merge($tariff_ids, [static::$plans[$name]['id']]),
        ]);
    }

    public function buildSale(string $target, string $plan, string $time): void
    {
        $planExist = $this->makeAsReseller('plans-search', ['select' => 'column', 'name' => $plan, 'limit' => 1]);
        if (empty($planExist)) {
            return;
        }
        $plan = reset($planExist);
        if (empty($plan['id'])) {
            return;
        }

        [$class, $name] = explode(':', $target);
        if ($class === 'client') {
            $this->sale = $this->makeAsReseller('client-sell', [
                'client' => $this->customer,
                'subject' => $name,
                'plan_id' => $plan['id'],
                'sale_time' => $time,
            ]);
        } elseif ($class === 'class' && $name === 'certificate') {
            $this->makeAsCustomer('SaleCreate', [
                'target_type' => $class,
                'target_name' => $name,
                'plan_id' => $plan['id'],
                'time' => $time,
            ]);
        } else {
            $this->sale = $this->makeAsReseller('client-set-tariffs', [
                'client' => $this->customer,
                'tariff_ids' => [$plan['id']],
            ]);
        }
    }

    public function targetChangePlan(string $target, string $planName, string $date, string $wallTime = null)
    {
        [$class, $name] = explode(':', $target);
        $options = [
            'name' => $name,
            'type' => $class,
            'plan_name' => $planName,
            'time' => $date,
        ];

        if ($wallTime !== null) {
            $this->makeAsReseller('TargetChangePlan', array_merge($options, ['wall_time' => $wallTime, 'customer_username' => $this->customer]));
        } else {
            $this->makeAsCustomer('TargetChangePlan', $options);
        }
    }

    public function buildPurchase(string $target, string $plan, string $time): void
    {
        $target = $this->factory->get('target', $target);
        $plan = static::$plans[$plan];
        $this->makeAsCustomer('TargetPurchase', [
            'type' => $target->getType(),
            'name' => $target->getName(),
            'plan_id' => $plan['id'],
            'time' => $time,
        ]);
    }

    public function findBills(array $params): array
    {
        $target = $this->factory->get('target', $params['target']);
        $rows = $this->makeAsCustomer('BillsSearch', [
            'with' => ['charges'],
            'where' => [
                'customer-login' => 'hipanel_test_user',  /// XXX to be removed!
                'type-name' => $params['type'],
                'target-type' => $target->getType(),
                'target-name' => $target->getName(),
                'time' => $params['time'] ?? null,
            ],
        ]);
        $res = [];
        foreach ($rows as $row) {
            $res[] = $this->factory->get('bill', $row);
        }

        return $res;
    }

    public function buildTarget(string $name)
    {
        /** @var TargetInterface $target */
        $target = $this->factory->get('target', $name);

        $this->makeAsCustomer('TargetCreate', [
            'name' => $target->getName(),
            'type' => $target->getType(),
            'remoteid' => random_int(111111, 999999),
        ]);
    }

    public function findHistoricalSales(array $params)
    {
        $target = $this->factory->get('target', $params['target']);

        $res = [];

        $rows = $this->makeAsCustomer('SalesSearch', [
            'include' => ['history'],
            'where' => [
                'target-type' => $target->getType(),
                'target-name' => $target->getName(),
                'customer-login' => $this->customer,
            ],
        ]);

        foreach ($rows as $key => $row) {
            $res[$key] = $this->factory->create('sale', $row);
        }

        return $res;
    }

    public function flushEntitiesCache(): void
    {
        $this->factory->clearEntitiesCache();
    }

    public function setConsumption(string $type, int $amount, string $unit, string $target, string $time): void
    {
        $this->makeAsManager('use-set', [
            'object' => $target,
            'type'   => $type,
            'time'   => $time,
            'amount' => $amount,
            'unit'   => $unit,
        ]);
    }

    public function setAction(string $type, int $amount, string $unit, string $target, string $time): void
    {
        $this->actions[] = [
            'target_fullname'   => $target,
            'type_name'         => $type,
            'amount'            => $amount,
            'unit'              => $unit,
            'time'              => $time,
            'plan_id'           => static::$lastPlan['id'],
        ];
    }

    public function performCalculation(string $time): array
    {
        $charges = $this->makeAsCustomer('ActionsCalculate', $this->actions);
        foreach ($charges as $key =>  $charge) {
            unset($charge['price']);
            $res[$key] = $this->factory->get('charge', $charge);
        }

        return $res;
    }

    protected function isAssigned(int $planId, string $login): bool
    {
        $assignments = $this->makeAsManager('client-get-tariffs', ['client' => $login]);
        $tariffIds = $assignments['tariff_ids'] ? explode(',', $assignments['tariff_ids']) : [];

        return in_array($planId, $tariffIds, true);
    }

    protected function makeAsReseller(string $command, array $payload = []): array
    {
        return $this->client->make($command, $payload, $this->reseller);
    }

    protected function makeAsManager(string $command, array $payload = []): array
    {
        return $this->client->make($command, $payload, $this->manager);
    }

    protected function makeAsCustomer(string $command, array $payload = []): array
    {
        return $this->client->make($command, $payload, $this->customer);
    }

    protected function makeAsAdmin(string $command, array $payload = []): array
    {
        return $this->client->make($command, $payload, $this->admin);
    }

    protected function makeAsRoot(string $command, array $payload = []): array
    {
        return $this->client->make($command, $payload, $this->root);
    }
}
