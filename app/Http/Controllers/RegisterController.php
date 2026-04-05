<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $openRegister = Register::where('user_id', $user->id)
            ->where('status', 'open')
            ->orderByDesc('opened_at')
            ->first();

        $recent = Register::where('user_id', $user->id)
            ->orderByDesc('opened_at')
            ->limit(10)
            ->get();

        return view('pos.register', compact('openRegister', 'recent'));
    }

    public function open(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Prevent multiple open registers for the same user
        $existing = Register::where('user_id', $user->id)->where('status', 'open')->first();
        if ($existing) {
            return back()->with('status', 'You already have an open register.');
        }

        $data = $request->validate([
            'opening_amount' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string','max:1000'],
        ]);

        Register::create([
            'user_id' => $user->id,
            'opened_at' => now(),
            'opening_amount' => (float) ($data['opening_amount'] ?? 0),
            'status' => 'open',
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('pos.register.index')->with('status', 'Register opened.');
    }

    public function close(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $reg = Register::where('user_id', $user->id)->where('status', 'open')->orderByDesc('opened_at')->first();
        if (! $reg) {
            return back()->with('status', 'No open register to close.');
        }

        $data = $request->validate([
            'closing_amount' => ['required','numeric','min:0'],
            'notes' => ['nullable','string','max:1000'],
        ]);

        $reg->update([
            'closed_at' => now(),
            'closing_amount' => (float) $data['closing_amount'],
            'status' => 'closed',
            'notes' => $data['notes'] ?? $reg->notes,
        ]);

        return redirect()->route('pos.register.index')->with('status', 'Register closed.');
    }
}
