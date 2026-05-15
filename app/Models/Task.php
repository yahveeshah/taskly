<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title','description','priority','status','deadline','user_id'];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
