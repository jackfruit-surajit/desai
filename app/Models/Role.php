<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'permissions'];

    protected $casts = [
        'permissions' => 'array', // Automatically cast permissions to an array
    ];
    

    /**
     * Check if the role has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        
        return in_array($permission, $this->permissions ?? false);
    }

    /**
     * Set multiple permissions at once.
     */
    public function setPermissions(array $permissions)
    {
        $this->permissions = $permissions;
        $this->save();

        return $this;
    }
    
}
