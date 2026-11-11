<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Notification;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('user_id', auth()->id())
            ->with('food', 'order')
            ->latest()
            ->paginate(20);

        return view('customer.reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $existingReview = Review::where('user_id', auth()->id())
            ->where('food_id', $request->food_id)
            ->whereNull('parent_id')
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this item.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'food_id' => $request->food_id,
            'order_id' => null,
            'parent_id' => null,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'active',
        ]);

        $food = \App\Models\Food::find($request->food_id);
        $foodName = $food->name ?? 'a food';
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'New Food Review',
                'message' => auth()->user()->name . ' reviewed ' . $foodName . ' (' . $request->rating . '/5 stars).',
                'type' => 'review',
                'url' => route('admin.reviews.index'),
            ]);
        }

        return back()->with('success', 'Review submitted successfully.');
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        if ($review->isReply()) {
            return back()->with('error', 'You cannot reply to a reply.');
        }

        $reply = Review::create([
            'user_id' => auth()->id(),
            'food_id' => $review->food_id,
            'order_id' => null,
            'parent_id' => $review->id,
            'rating' => null,
            'comment' => $request->comment,
            'status' => 'active',
        ]);

        if ($review->user_id && $review->user_id !== auth()->id()) {
            $foodName = $review->food->name ?? 'a food';
            Notification::create([
                'user_id' => $review->user_id,
                'title' => 'New Reply to Your Review',
                'message' => auth()->user()->name . ' replied to your review for ' . $foodName . '.',
                'type' => 'review',
                'url' => route('foods.show', $review->food_id),
            ]);
        }

        return back()->with('success', 'Reply submitted successfully.');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'active',
        ]);

        return back()->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
