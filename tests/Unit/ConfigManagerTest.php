<?php

declare(strict_types=1);

/**
 * This file is part of the guanguans/ai-commit.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use App\ConfigManager;
use App\Exceptions\InvalidJsonFileException;
use App\Exceptions\UnsupportedConfigFileTypeException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

it('can create ConfigManager', function () {
    expect(ConfigManager::create())->toBeInstanceOf(ConfigManager::class)
        ->and(ConfigManager::create([]))->toBeInstanceOf(ConfigManager::class);
})->group(__DIR__, __FILE__);

it('can get local path', function () {
    $this->getFunctionMock('App', 'getcwd')
        ->expects($this->once())
        ->willReturn(false);
    expect(ConfigManager::localPath())->toBeString();
})->group(__DIR__, __FILE__)->skip();

it('can to local config file', function () {
    /** @noinspection PhpVoidFunctionResultUsedInspection */
    expect(ConfigManager::create())->toLocal()->toBeNull();
})->group(__DIR__, __FILE__);

/**
 * @psalm-suppress UndefinedMagicMethod
 */
it('can to jsonSerialize', function () {
    /** @noinspection ReplaceLegacyMockeryInspection */
    $manager = ConfigManager::create([
        'jsonSerialize' => \Mockery::spy(JsonSerializable::class),
        'Jsonable' => \Mockery::spy(Jsonable::class)->shouldReceive('toJson')->andReturn(json_encode([])),
        'toArray' => \Mockery::spy(Arrayable::class),
    ]);
    expect($manager)->jsonSerialize()->toBeArray();
})->group(__DIR__, __FILE__);

it('can to array', function () {
    expect(ConfigManager::create())->toArray()->toBeArray();
})->group(__DIR__, __FILE__);

it('can to string', function () {
    expect(ConfigManager::create())->__toString()->toBeString();
})->group(__DIR__, __FILE__);

it('will throw InvalidJsonFileException when read from config file', function () {
    ConfigManager::readFrom(__DIR__.'/../Fixtures/ai-commit.json');
})->group(__DIR__, __FILE__)->throws(InvalidJsonFileException::class);

it('will throw UnsupportedConfigFileTypeException when read from config file', function () {
    ConfigManager::readFrom(__DIR__.'/../Fixtures/ai-commit.yml');
})->group(__DIR__, __FILE__)->throws(UnsupportedConfigFileTypeException::class);
