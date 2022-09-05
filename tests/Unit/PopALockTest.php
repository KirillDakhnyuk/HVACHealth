<?php

use HvacHealth\Facades\PopALock;
use HvacHealth\Monitors\PopALockOrderMonitor;
use HvacHealth\Monitors\Status;

it('has a green status when order has been placed within the last hour', function () {
    PopALock::shouldReceive('getLatestOrderCreatedAt')->andReturn(now()->toDateTimeString());

    $res = resolve(PopALockOrderMonitor::class)->run();

    expect($res)->toMatchObject([
        'status' => Status::ok(),
        'notificationMessage' => trans('pop-a-lock-order.green'),
    ]);
});

it('has a yellow status when order has not been placed within the last hour', function () {
    PopALock::shouldReceive('getLatestOrderCreatedAt')->andReturn(now()->subHours(2)->toDateTimeString());

    $res = resolve(PopALockOrderMonitor::class)->run();

    expect($res)->toMatchObject([
        'status' => Status::warning(),
        'notificationMessage' => trans('pop-a-lock-order.yellow'),
    ]);
});

it('has a red status when order has not been placed in the last 6 hours', function () {
    PopALock::shouldReceive('getLatestOrderCreatedAt')->andReturn(now()->subHours(10)->toDateTimeString());

    $res = resolve(PopALockOrderMonitor::class)->run();

    expect($res)->toMatchObject([
        'status' => Status::failed(),
        'notificationMessage' => trans('pop-a-lock-order.red'),
    ]);
});
