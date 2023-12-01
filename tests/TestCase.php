<?php

namespace Tests;

use App\Models\Client;
use App\Models\Request;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Client::truncate();
        Request::truncate();
    }
}
