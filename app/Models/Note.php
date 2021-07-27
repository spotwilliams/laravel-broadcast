<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Note extends Model
{
    use HasFactory;
    /**
     * Fields that can not be mass assigned
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Return the column we want to use for route model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function editors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'note_user_editor');
    }
}
