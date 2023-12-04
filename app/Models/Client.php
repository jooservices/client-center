<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    use HasUuid;

    protected $connection = 'mongodb';
    protected $collection = 'clients';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'queues', // 'queues' => [ 'name' => 'string', 'workers' => 'integer' ]
        'ip',
        'state_code',
    ];

    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'description' => 'string',
        'queues' => 'array',
        'ip' => 'string',
        'state_code' => 'string',
    ];
}
