<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Post extends Model
{
    use HasFactory;

    public $title;
    public $excerpt;
    public $date;
    public $body;
    public $slug;


    public function __construct($title, $excerpt, $date, $body, $slug)
    {
        $this->title = $title;
        $this->excerpt = $excerpt;
        $this->date = $date;
        $this->body = $body;
        $this->slug = $slug;
    }

    public static function fileList()
    {
        $files = File::files(resource_path("posts/"));
        $posts = collect($files)
        ->map(
            function ($file) {
                $document = YamlFrontMatter::parseFile($file);

                return new Post(
                    $document->title,
                    $document->excerpt,
                    $document->date,
                    $document->body(),
                    $document->slug
                );
            }
        );
        return $posts;
    }

    public static function find($slug)
    {
        return static::fileList()->firstWhere('slug', $slug);
    }
}
