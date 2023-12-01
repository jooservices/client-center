<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Request extends Model
{
    use HasUuid;
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'requests';

    protected $fillable = [
        'uuid',
        'url',
        'requestOptions',
        'options',
        'response',
        'state_code',
        'responded_at',
    ];

    protected $casts = [
        'uuid' => 'string',
        'url' => 'string',
        'requestOptions' => 'array',
        'options' => 'array',
        'response' => 'string',
        'state_code' => 'string',
        'responded_at' => 'datetime',
    ];
}
