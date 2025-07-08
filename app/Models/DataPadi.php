<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPadi extends Model
{
    use HasFactory;
    //
    protected $table = 'data_padi';
    protected $fillable = ['nama', 'user_id', 'jumlah_padi', 'jenis_padi', 'latitude', 'longitude', 'foto_padi'];
    // protected $with = ['author'];

    public function petani(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
