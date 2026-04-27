<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiPollController extends Controller
{
    /**
     * Display a listing of the authenticated user's polls.
     */
    public function index(Request $request)
    {
        // Auth::loginUsingId(1);
        $polls = $request->user()->polls()->orderBy('created_at', 'desc')->get();

        return $polls;
    }

    /**
     * Display the specified poll by its secret token.
     */
    public function show(string $token)
    {
        // Auth::loginUsingId(1);
        $poll = Poll::with(['options' => function ($query) {
            $query->withCount('votes');
        }])->where('secret_token', $token)->first();

        if (!$poll) {
            return response()->json(['message' => 'Poll not found.'], 404);
        }

        return $poll;
    }

    public function destroy(Request $request, Poll $poll)
        {
            if ($poll->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Non autorisé: ' . $poll->user_id . " " . $request->user()->id], 403);
            }

            $poll->delete();
            return response()->json(['message' => 'Success'], 200);
        }
    
}
