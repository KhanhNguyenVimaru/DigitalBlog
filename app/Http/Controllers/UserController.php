<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class UserController extends Controller
{
    public function content(): Content
    {
        return new Content(
            view: 'mail.verify.signUpAccount',
            text: 'mail.verify.signUpAccount'
        );
    }
    public function verifySignUp(): Envelope
    {
        return new Envelope(
            from: new Address('khanhnd05@gmail.com', 'Digital Blog administrator'),
            subject: 'Verify your account',
        );
    }

    public function updateUserData(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->description = $request->description;
        $user->privacy = $request->privacy;
        $user->save();
        return redirect('/page_account');
    }

    public function deleteUserAccount(Request $request)
    {
        try {
            $user = Auth::user();
            $user->tokens->each->revoke();
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting account: ' . $e->getMessage()
            ], 500);
        }
    }
}
