<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfFile extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
