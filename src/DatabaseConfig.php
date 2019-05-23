<?php

namespace Koomai\LaravelConfig;

use Illuminate\Database\Eloquent\Model;

class DatabaseConfig extends Model
{
    /** @var string */
    protected $primaryKey = 'name';
    /** @var bool */
    public $incrementing = false;
    /** @var array */
    protected $guarded = [];
    /** @var array */
    protected $casts = [
        'value' => 'array',
    ];

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('database-config.table');
    }
}
