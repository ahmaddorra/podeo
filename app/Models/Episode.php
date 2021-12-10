<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    protected $appends = ['duration'];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    public function getDurationAttribute(): string
    {
        return $this->calculateFileSize(('./public/storage/'.$this->audio));
    }

    function calculateFileSize($file): string
    {
        if(is_file($file)){
            $ratio = 16000; //bytesPerSec
            $file_size = filesize($file);
            $duration = ($file_size / $ratio);
            $minutes = floor($duration / 60);
            $seconds = $duration - ($minutes * 60);
            $seconds = round($seconds);
            return "$minutes:$seconds minutes";
        } else{
            return "";
        }


    }

}
