<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['candidate_number', 'full_name', 'vision', 'mission', 'photo_url', 'vote_count'];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
