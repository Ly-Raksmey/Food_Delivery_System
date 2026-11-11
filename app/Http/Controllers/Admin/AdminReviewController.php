<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Notification;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user', 'food')->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggle(Review $review)
    {
        $review->update(['status' => $review->status === 'active' ? 'hidden' : 'active']);
        return back()->with('success', 'Review status updated.');
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:2000',
        ]);

        $review->update([
            'admin_reply' => $request->admin_reply,
            'admin_reply_at' => now(),
        ]);

        if ($review->user_id) {
            $foodName = $review->food->name ?? 'a food';
            Notification::create([
                'user_id' => $review->user_id,
                'title' => 'Admin Reply to Your Review',
                'message' => "Admin has replied to your review for {$foodName}.",
                'type' => 'review',
                'url' => route('foods.show', $review->food_id),
            ]);
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully.');
    }
}
