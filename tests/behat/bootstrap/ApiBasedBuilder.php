<?php

namespace hiqdev\billing\hiapi\tests\behat\bootstrap;

use Dotenv\Dotenv;
use hiqdev\hiart\guzzle\Request;
use hipanel\hiart\Connection;
use hiqdev\hiart\RequestInterface;
use Yii;
use yii\di\Container;
use yii\web\Application;
use hiqdev\php\billing\tests\support\tools\SimpleFactory;
use hiqdev\php\billing\tests\behat\bootstrap\BuilderInterface;

class ApiBasedBuilder implements BuilderInterface
{
    private Connection $connection;

    private Application $application;

    protected string $reseller;

    protected string $customer;

    protected string $manager;

    protected string $admin;

    protected string $root;

    protected array $prices = [];

    protected string $plan;

    protected static array $plans = [];

    protected array $sale;

    protected SimpleFactory $factory;

    public function __construct()
    {
        $this->bootstrap();
        $application = $this->application ?? $this->mockApplication();
        $this->connection = new Connection($application);
        $this->connection->requestClass = Request::class;
        $this->connection->baseUri = $_ENV['HIART_BASEURI'];
        $this->factory = new SimpleFactory();
    }

    private function bootstrap(): void
    {
        $dir = dirname(__DIR__, 6);
        $pathToYii = $dir . '/vendor/yiisoft/yii2/Yii.php';
        require_once $pathToYii;
        (new Dotenv($dir))->load();
        if (empty($_ENV['HIART_BASEURI'])) {
            throw new \Exception('HIART_BASEURI must be set in environment');
        }
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
        $this->makeAsManager('use-set', [
            'object' => $target,
            'type'   => $type,
            'time'   => $time,
            'amount' => $amount,
            'unit'   => $unit,
        ]);
    }

    protected function makeAsReseller(string $command, array $payload = []): array
    {
        return $this->make($command, $payload, $this->reseller);
    }

    protected function makeAsManager(string $command, array $payload = []): array
    {
        return $this->make($command, $payload, $this->manager);
    }

    protected function makeAsCustomer(string $command, array $payload = []): array
    {
        return $this->make($command, $payload, $this->customer);
    }

    protected function makeAsAdmin(string $command, array $payload = []): array
    {
        return $this->make($command, $payload, $this->admin);
    }

    protected function makeAsRoot(string $command, array $payload = []): array
    {
        return $this->make($command, $payload, $this->root);
    }

    protected function make(string $command, array $payload, string $performer): array
    {
        $res = $this->buildRequest($command, $payload, $performer ?? $this->reseller)->send()->getData();
        if (!is_array($res)) {
            throw new \Exception('API return not array: ' . $res);
        }
        if (!empty($res['_error'])) {
            // var_dump(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__, $command, $payload, $performer, $res);
            throw new \Exception('API return error: ' . $res['_error']);
        }

        return $res;
    }

    protected function isAssigned(int $planId, string $login): bool
    {
        $assignments = $this->makeAsManager('client-get-tariffs', ['client' => $login]);
        $tariffIds = $assignments['tariff_ids'] ? explode(',', $assignments['tariff_ids']) : [];

        return in_array($planId, $tariffIds, true);
    }

    private function buildRequest(string $command, array $body = [], ?string $performer = null): RequestInterface
    {
        $auth_login = $performer;
        $auth_password = 'random';

        return $this->connection->callWithDisabledAuth(
            function () use ($command, $body, $auth_login, $auth_password) {
                $request = $this->connection
                    ->createCommand()
                    ->db
                    ->getQueryBuilder()
                    ->perform(
                        $command,
                        null,
                        array_merge($body, compact('auth_login', 'auth_password'))
                    );
                $request->build();

                return $request;
            }
        );
    }

    private function mockApplication(): Application
    {
        Yii::$container = new Container();
        return new Application(
            [
                'id'         => 'behat-test-application',
                'basePath'   => __DIR__,
                'vendorPath' => __DIR__ . '../../../../../',
            ]
        );
    }
}
