<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property int $sp_user_id
 * @property string $sp_client_id
 * @property string $sp_client_secret
 * @property integer $integration_id
 * @property string $webhook_token
 * @property string $created_at
 * @property string $updated_at
 * @method static getModel()
 */
class User extends Authenticatable
{
    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'sp_user_id',
        'sp_client_id',
        'sp_client_secret',
        'integration_id',
        'webhook_token'
    ];
}
