<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Permission extends Model
{
    
    public function scopeGetRaw($query, $role)
    {
    	
    	return $query = DB::table('permissions')
    	            ->select('*')
    				->leftJoin('role_has_permissions', 'id', 'role_has_permissions.permission_id')
    				 ->from('permissions')->get();
    }
}
