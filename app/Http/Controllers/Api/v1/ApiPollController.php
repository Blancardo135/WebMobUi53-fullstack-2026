<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Support\Str;

class ApiPollController extends Controller
{
    /**
     * Display a listing of the authenticated user's polls.
     */
    public function index(Request $request)
    {
        // Auth::loginUsingId(1);
        // $polls = $request->user()->polls()->orderBy('created_at', 'desc')->get();
        $polls = $request->user()->polls()->with('options')->orderBy('created_at', 'desc')->get();

        return $polls;
    }

    /**
     * Display the specified poll by its secret token.
     */
    public function show(Request $request, string $token)
    {
        // Auth::loginUsingId(1);
        $poll = Poll::with(['options' => function ($query) {
            $query->withCount('votes');
        }])->where('secret_token', $token)->first();

        if (!$poll) {
            return response()->json(['message' => 'Poll not found.'], 404);
        }

        //pr gérer le cas où je suis le créateur mais résultats nn public
        $poll->is_owner = $request->user() && $request->user()->id === $poll->user_id;

        return $poll;
    }

    public function store (Request $request)
    {
        $validated = $request->validate([
        'title'                  => 'nullable|string|max:255',
        'question'               => 'required|string|max:255',
        'is_draft'               => 'boolean',
        'allow_multiple_choices' => 'boolean',
        'allow_vote_change'      => 'boolean',
        'results_public'         => 'boolean',
        'duration'               => 'nullable|integer|min:1',
        'options'                => 'required|array|min:2',
        //pr appliquer a tt les labels, npo le *
        'options.*.label'        => 'required|string|max:255',
    ]);

    $user = $request->user();
    $poll = new Poll();

    $poll->title = $validated['title'] ?? null;
    $poll->question = $validated['question'];
    $poll->secret_token = Str::random(32);
    $poll->is_draft = $validated['is_draft'] ?? true;
    $poll->allow_multiple_choices = $validated['allow_multiple_choices'] ?? false;
    $poll->allow_vote_change = $validated['allow_vote_change'] ?? false;
    $poll->results_public = $validated['results_public'] ?? false;
    $poll->duration = $validated['duration'] ? $validated['duration'] * 86400 : null;
    $poll->ends_at = $poll->duration ? now()->addSeconds($poll->duration) : null;
    $poll->started_at = $poll->is_draft ? null : now();
    $poll->user()->associate($user);
    $poll->save();
    

    foreach ($validated['options'] as $optionData) {
        $option = new PollOption();
        //pr assigner chaque valeur au label qui corresp.
        $option->label = $optionData['label'];
        $option->poll()->associate($poll);
        $option->save();
    }
    //pr que vue aille les réponses
    return $poll->load('options');
}

    public function destroy(Request $request, Poll $poll)
        {
            if ($poll->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Non autorisé: ' . $poll->user_id . " " . $request->user()->id], 403);
            }

            $poll->delete();
            return response()->json(['message' => 'Success'], 200);
        }

    public function update(Request $request, Poll $poll)
    {
        if ($poll->user_id !== $request->user()->id) {
        return response()->json(['message' => 'Non autorisé.'], 403);
    }
    //vérif pr gérer la modale de brouillon/publié
        if (!$poll->is_draft) {
            return response()->json(['message' => 'Ce sondage est lancé et ne peut plus être modifié.'], 403);
    }

    $validated = $request->validate([
        'title'                  => 'nullable|string|max:255',
        'question'               => 'required|string|max:255',
        'is_draft'               => 'boolean',
        'allow_multiple_choices' => 'boolean',
        'allow_vote_change'      => 'boolean',
        'results_public'         => 'boolean',
        'duration'               => 'nullable|integer|min:1',
        'options'                => 'required|array|min:2',
        'options.*.label'        => 'required|string|max:255',
    ]);

    $poll->title = $validated['title'] ?? null;
    $poll->question = $validated['question'];
    $poll->is_draft = $validated['is_draft'] ?? $poll->is_draft;
    if (!$poll->is_draft && !$poll->started_at) {
    $poll->started_at = now();}
    $poll->allow_multiple_choices = $validated['allow_multiple_choices'] ?? $poll->allow_multiple_choices;
    $poll->allow_vote_change = $validated['allow_vote_change'] ?? $poll->allow_vote_change;
    $poll->results_public = $validated['results_public'] ?? $poll->results_public;
    $poll->duration = $validated['duration'] ? $validated['duration'] * 86400 : null;
    $poll->ends_at = $poll->duration ? now()->addSeconds($poll->duration) : null;
    $poll->save();

    $poll->options()->delete(); //pr moi: + simple pour etre sur que les nouvelles données sont bien celles mises à jour
    foreach ($validated['options'] as $optionData) {
        $option = new PollOption();
        $option->label = $optionData['label'];
        $option->poll()->associate($poll);
        $option->save();
    }

    return $poll->load('options');

    }

    public function results(Request $request, string $token)
    {
        $poll = Poll::with(['options' => function ($query) {
        $query->withCount('votes');
        }])->where('secret_token', $token)->first();
        
        if (!$poll) {
        return response()->json(['message' => 'Poll not found.'], 404);
        }

        if (!$poll->results_public) {
            if (!$request->user()) {
                return response()->json(['message' => 'Résultats non publics.'], 403);
                }   
        if ($request->user()->id !== $poll->user_id) {
            return response()->json(['message' => 'Résultats non publics.'], 403);
        }
}

        $totalVotes = $poll->options->sum('votes_count');
        
        //me permet de retourner options et total des votes
        return response()->json([
            'options' => $poll->options->map(fn($option) => [
            'id'          => $option->id,
            'label'       => $option->label,
            'votes_count' => $option->votes_count,
            ]),
            'total_votes' => $totalVotes,
            ]);
    }
}
