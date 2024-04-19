<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Foto extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(LikeFoto::class);
    }

    public function comments()
    {
        return $this->hasMany(KomentarFoto::class);
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public static function getAllPhotosGroupedByPath()
    {
        return static::all()->groupBy('lokasi_file');
    }

}
