<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reason extends Model
{
    use HasFactory, DefaultDatetimeFormat;
    protected $fillable = [
        'content'
    ];

    public static function getOptions()
    {
        $values = DB::table('reasons')->select('id', 'content')->get();
        $selectOption = [];
        foreach ($values as $option) {
            $selectOption[$option->id] = $option->content;
        }
        return $selectOption;
    }

    public static function getReason($id)
    {
        return Reason::where('id', $id)->value('content');
    }
}
