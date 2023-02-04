<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;

class Post extends Model
{
    use HasFactory;

    public static function fileList()
    {
        $files = File::files(resource_path("posts/"));
        return array_map(fn ($file) => $file->getContents(), $files);
    }

    public static function find($slug)
    {
        base_path();
        if (!file_exists($path = resource_path("posts/{$slug}.html"))) {
            throw new ModelNotFoundException();
            // return redirect('/');
            // abort(404);
        }

        return cache()->remember("posts.{$slug}", 5, fn () => file_get_contents($path));
    }
}
