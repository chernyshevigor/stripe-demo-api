<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

/**
 * @property integer $id
 * @property integer $app_user_id
 * @property string $stripe_id
 * @property string $pm_type
 * @property string $pm_last_four
 * @property \Illuminate\Support\Carbon $trial_ends_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class User extends Model
{
    use Billable;

    /**
     * @var string
     */
    protected $table = 'users';
    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'app_user_id', // '...'
    ];
}
