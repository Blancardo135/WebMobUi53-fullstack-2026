<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function show(string $token)
    {
        return view('polls.vote', [
            'token' => $token,
        ]);
    }
}
