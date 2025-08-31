<?php

namespace App\Models;

use App\Scopes\AdminScope;
use App\Models\User;

class Admin extends User
{
    protected $table = 'users';
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AdminScope);
    }

    /**
     * **************************************************************
     * ADD EARNINGS
     * **************************************************************
     * */
    public function updateAdminEarnings($earning)
    {
        $this->total_earnings += $earning;
        return $this->save();
    }
}