<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CourseStat extends Model
{
    use HasFactory;

    // public function findAverageResponse($stats)
    // {
    //     $average_array = [];
    //     foreach ($stats as $stat) {
    //         $created_at = Carbon::parse($stat->created_at);
    //         $accepted_at = $stat->accepted_at;

    //         $average_array[] = $accepted_at->diffInHours($created_at);
    //     }

    //     return $average_array;
    // }
}
