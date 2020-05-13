<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\target;

use hiqdev\billing\hiapi\target\device\ServerTarget;
use hiqdev\billing\hiapi\target\RemoteTargetCreationDto;
use hiqdev\billing\hiapi\target\TargetFactory;
use hiqdev\billing\hiapi\target\Remote\AnycastCdnTarget;
use hiqdev\billing\hiapi\target\domain\DomainTarget;

/**
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class TargetFactoryTest extends \PHPUnit\Framework\TestCase
{
    protected $dataServer = [
        'id'        => 'server-id',
        'type'      => 'server',
        'name'      => 'server-name',
    ];

    protected $dataDomain = [
        'id'        => 'domain-id',
        'type'      => 'domain',
        'name'      => 'domain-name',
    ];

    protected $dataAnycast = [
        'id'        => 'anycastcdn-id',
        'type'      => 'anycastcdn',
        'name'      => 'anycastcdn-name',
        'remoteid'  => 'anycastcdn-remoteid',
    ];

    public function setUp(): void
    {
        $this->factory = new TargetFactory();
    }

    public function testCreateServer()
    {
        $dto = $this->createDto($this->dataServer);
        $obj = $this->factory->create($dto);
        $this->assertInstanceOf(ServerTarget::class, $obj);
        $this->assertSame($this->dataServer['id'], $obj->getId());
        $this->assertSame($this->dataServer['type'], $obj->getType());
        $this->assertSame($this->dataServer['name'], $obj->getName());
    }

    public function testCreateDomain()
    {
        $dto = $this->createDto($this->dataDomain);
        $obj = $this->factory->create($dto);
        $this->assertInstanceOf(DomainTarget::class, $obj);
        $this->assertSame($this->dataDomain['id'], $obj->getId());
        $this->assertSame($this->dataDomain['type'], $obj->getType());
        $this->assertSame($this->dataDomain['name'], $obj->getName());
    }

    public function testCreateAnycast()
    {
        $dto = $this->createDto($this->dataAnycast);
        $obj = $this->factory->create($dto);
        $this->assertInstanceOf(AnycastCdnTarget::class, $obj);
        $this->assertSame($this->dataAnycast['id'], $obj->getId());
        $this->assertSame($this->dataAnycast['type'], $obj->getType());
        $this->assertSame($this->dataAnycast['name'], $obj->getName());
        $this->assertSame($this->dataAnycast['remoteid'], $obj->getRemoteId());
    }

    private function createDto(array $data)
    {
        $dto = new RemoteTargetCreationDto;
        foreach ($data as $key => $value) {
            $dto->{$key} = $value;
        }

        return $dto;
    }
}
