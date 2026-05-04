<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\Request;

class ApiVoteController extends Controller
{
    public function store(Request $request, Poll $poll)
    {
        $user = $request->user();

        if ($poll->is_draft) {
            return response()->json(['message' => 'Ce sondage est un brouillon.'], 400);
        }

        if ($poll->ends_at && now() > $poll->ends_at) {
            return response()->json(['message' => 'Ce sondage est terminé.'], 400);
        }

        $selectedOptionIds = $request->input('option_ids', []);

        if (empty($selectedOptionIds)) {
            return response()->json(['message' => 'Aucune option sélectionnée.'], 400);
        }

        $validOptionIds = $poll->options()->pluck('id')->toArray();
        foreach ($selectedOptionIds as $optionId) {
            if (!in_array($optionId, $validOptionIds)) {
                return response()->json(['message' => 'Option invalide.'], 400);
            }
        }

        $existingVote = PollVote::where('poll_id', $poll->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($existingVote && !$poll->allow_vote_change) {
            return response()->json(['message' => 'Changement de vote non autorisé.'], 400);
        }

        if ($existingVote) {
            PollVote::where('poll_id', $poll->id)
                ->where('user_id', $user->id)
                ->delete();
        }

        foreach ($selectedOptionIds as $optionId) {
            PollVote::create([
                'poll_id'        => $poll->id,
                'user_id'        => $user->id,
                'poll_option_id' => $optionId,
            ]);
        }

        return response()->json(['message' => 'Vote enregistré !'], 200);
    }
}