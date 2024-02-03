<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Game;
use Carbon\Carbon;


class GameController extends Controller
{
    public function startGame(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'username' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email',
        ]);

        // Check if the user already exists with the provided email
        $user = User::where('email', $validatedData['email'])->first();

        if (!$user) {
            // If the user doesn't exist, create a new user
            $user = User::create([
                'username' => $validatedData['username'],
                'surname' => $validatedData['surname'],
                'email' => $validatedData['email'],
            ]);
        }

        // Get or create a new game for the user
        $game = Game::create([
            'user_id' => $user->id,
            'score' => 0, // You can set the initial score as needed
        ]);

        // Return a response with the user and game information
        return response()->json([
            'user' => $user,
            'game' => $game,
        ], 201);
    }

    public function endGame(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'gameId' => 'required|exists:games,id',
            'userId' => 'required|exists:users,id',
            'userScore' => 'required|integer|min:0|max:1000',
        ]);

        // Check if the user exists
        $user = User::find($validatedData['userId']);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if the game exists
        $game = Game::find($validatedData['gameId']);
        if (!$game) {
            return response()->json(['error' => 'Game not found'], 404);
        }

        // Update the game score
        $game->update([
            'score' => $validatedData['userScore'],
        ]);

        // Return a success response
        return response()->json(['message' => 'Game score updated successfully'], 200);
    }

    public function topTen()
    {
        // Get the current date
        $currentDate = Carbon::now()->toDateString();

        // Retrieve the top 10 games ordered by score for the current day
        $topGames = Game::whereDate('created_at', $currentDate)
            ->orderByDesc('score')
            ->take(10)
            ->get();

        // Retrieve user information for each game
        foreach ($topGames as $game) {
            $user = User::find($game->user_id);
            $game->user = $user;
        }

        // Return the top 10 games with user information
        return response()->json(['topGames' => $topGames], 200);
    }
}