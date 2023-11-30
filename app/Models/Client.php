<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use MongoDB\Laravel\Eloquent\Model;

class Client extends Model
{
    use HasUuid;

    protected $connection = 'mongodb';
    protected $collection = 'clients';

    protected $fillable = [
        'uuid',
        'name',
        'ip',
        'description',
        'max_workers',
        'state_code',
    ];

    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'ip' => 'string',
        'description' => 'string',
        'max_workers' => 'integer',
        'state_code' => 'string',
    ];
}
