<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{

    protected $fillable = ['message', 'user_id', 'to', 'room_id'];
    protected $casts = ['id' => 'integer'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function scopeAvailableMessages($query, Request $request)
    {
        $query->with('author')
            ->where('room_id', $request->get('room'))
            ->where(function($query){
                $query->where('to', Auth::user()->id)
                    ->orWhere('user_id', Auth::user()->id)
                    ->orWhere('to', 0);
            })
           ->latest();
    }

    public function scopeAllMessages($query, Request $request)
    {
        $query->with('author')->where('room_id', $request->get('room'))->latest();
    }


    public function scopeLatest($query)
    {
        $query->orderBy('id','DESC')->limit(50);
    }

    public function scopeSeen($query, Request $request)
    {
        $seenby = $request->input('seenby');

        $curr  = date("Y-m-d", strtotime("+1 day"));
        $today  = date("Y-m-d", strtotime("now"));
        $thisweek = date("Y-m-d", strtotime("this week"));
        $lastweek = date("Y-m-d", strtotime("last week"));
        $lastmonth = date("Y-m-d", strtotime("first day of last month"));

        switch ($seenby) {
            case "today": $begin = $today; break;
            case "thisweek": $begin = $thisweek; break;
            case "lastweek": $begin = $lastweek; break;
            case "thismonth": $begin = $lastmonth; break;
            default: return response()->json("something wrong!");
        }

          return $query->with('author')
                ->where(function($query) use ($begin, $curr){
                    $query->where('to', Auth::user()->id)
                        ->orWhere('user_id', Auth::user()->id)
                        ->orWhere('to', 0)
                        ->whereBetween('created_at', [$begin, $curr]);
                })
                ->latest();
    }

}
