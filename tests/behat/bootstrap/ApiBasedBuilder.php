<?php

namespace hiqdev\billing\hiapi\tests\behat\bootstrap;

use hiqdev\php\billing\tests\support\tools\SimpleFactory;
use hiqdev\php\billing\tests\behat\bootstrap\BuilderInterface;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\Unit;

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

    protected string $plan;

    protected static array $plans = [];

    protected array $sale;

    public function __construct()
    {
        $this->client = new ApiClient();
        $this->factory = new SimpleFactory();
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
            'name'        => $name,
            'type'        => $type,
            'is_grouping' => $grouping,
            'currency'    => 'usd',
        ];
        if (empty(static::$plans[$name]) || !empty(array_diff($plan, static::$plans[$name]))) {
            static::$plans[$name] = $plan;
        }
    }

    public function buildPrice(array $data): void
    {
        $this->prices[] = $data;
    }

    public function performBilling(): void
    {
        $this->makeAsReseller('client-perform-billing', ['client' => $this->customer]);
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
            $price['quantity'] = $price['quantity'] ?? 0;
            $price['currency'] = strtolower($price['currency']);
            $price['object'] = $price['target'] ?? null;
            if (isset($price['sums'])) {
                $price['sums'] = array_map(fn($price) => (int)((float)$price * 100), $price['sums']);
                $price['price'] = 0;
                $price['class'] = 'CertificatePrice';
            }
            $this->makeAsReseller('price-create', $price);
        }
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

        return $plan;
    }

    public function assignPlan(string $name)
    {
        $this->makeAsReseller('client-set-tariffs', [
            'client' => $this->customer,
            'tariff_ids' => [static::$plans[$name]['id']],
        ]);
    }

    public function buildSale(string $id, string $target, string $plan, string $time): void
    {
        $planExist = $this->makeAsReseller('plans-search', ['select' => 'column', 'name' => $plan, 'limit' => 1]);
        if (!empty($planExist)) {
            $plan = reset($planExist);
            if (isset($plan['id'])) {
                $this->sale = $this->makeAsReseller(
                    'client-set-tariffs',
                    ['client' => $this->customer, 'tariff_ids' => [$plan['id']]]
                );
            }
        }
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
            ],
        ]);
        $res = [];
        foreach ($rows as $row) {
            $res[] = $this->factory->get('bill', $row);
        }

        return $res;
    }

    public function setConsumption(string $type, int $amount, string $unit, string $target, string $time): void
    {
        $convertMap = [
            'GB' => Unit::byte(),
        ];
        if (isset($convertMap[$unit])) {
            $amount = Quantity::create(Unit::create($unit), $amount);
            if ($amount->isConvertible($convertMap[$unit])) {
                $amount = $amount->convert($convertMap[$unit])->getQuantity();
            }
        }
        $this->makeAsManager('use-set', [
            'object' => $target,
            'type' => $type,
            'time' => $time,
            'amount' => $amount,
        ]);
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
