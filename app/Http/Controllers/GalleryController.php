<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $photos = Photo::latest()->paginate(12);
        return view('gallery.index', compact('photos'));
    }

    public function show(Photo $photo)
    {
        $allPhotos = Photo::latest()->get();
        $currentIndex = $allPhotos->search(function($item) use ($photo) {
            return $item->id === $photo->id;
        });
        
        $prevPhoto = $allPhotos[$currentIndex - 1] ?? $allPhotos->last();
        $nextPhoto = $allPhotos[$currentIndex + 1] ?? $allPhotos->first();

        return view('gallery.show', compact('photo', 'prevPhoto', 'nextPhoto'));
    }
}