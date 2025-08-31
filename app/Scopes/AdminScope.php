<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class AdminScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('user_type', User::ADMIN_USER_TYPE);
    }
}