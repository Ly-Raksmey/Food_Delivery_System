@extends('layouts.customer')

@section('title', 'Gallery')

@section('content')
<section class="sire-page">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sire-eyebrow">Our Gallery</span>
            <h1 class="sire-section-title">A Taste in <em>Pictures</em></h1>
            <p class="sire-section-sub mx-auto" style="max-width: 540px;">A glimpse into the plates, ingredients, and moments that make Sire special.</p>
        </div>

        @if(($foods ?? collect())->count() > 0)
            <div class="row g-3 sire-gallery">
                @foreach($foods as $i => $food)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a class="sire-gallery-item" href="{{ route('foods.show', $food->id) }}">
                            <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" />
                            <div class="sire-gallery-overlay">
                                <span>{{ $food->name }}</span>
                                <small>${{ number_format($food->price, 2) }}</small>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="sire-empty sire-empty-lg">
                <i class="bi bi-images"></i>
                <h5>No gallery photos yet</h5>
                <p>Once you add dishes with photos in the admin panel, they will appear here.</p>
            </div>
        @endif
    </div>
</section>
@endsection