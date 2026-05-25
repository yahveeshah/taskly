<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title','description','priority','status','deadline','user_id','is_personal'];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'is_personal' => 'boolean',
        ];
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
