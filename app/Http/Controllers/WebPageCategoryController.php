<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Song;
use Illuminate\Http\Request;

class WebPageCategoryController extends Controller
{
    //
    public $page;
    public $url;
    public function __construct(Request $request)
    {
        $this->page = $request->get('page');
        $this->url = "?page=";
    }

    public function loadView($songs, $title, $ogTitle, $ogDes){
        return view("webpage.categories.index",
            ["songs" => $songs, "page" => $this->page, "url" => $this->url,
                "og_title" => $ogTitle, "og_des" => $ogDes, "title" => $title]);
    }

    public function newestSongs()
    {
        $songs = Song::orderBy("id", "desc")->where("display", 1)->paginate(14);
        return $this->loadView($songs,
            "Toques Novos",
            "Toques Novos | Baixe mais toques gratuitos para celular",
            "Baixe toques populares - A coleção de toques mais populares do mundo - Baixe 100% grátis para celulares em MP3 e M4R - ". env("WEB_NAME"));
    }
    public function popularSongs()
    {
        $songs = Song::orderBy("listeners", "desc")->where("display", 1)->paginate(14);
        return $this->loadView($songs,
            "Toques Populares",
            "Toques Populares - ". env("WEB_NAME"),
            "Os toques de celular mais populares são atualizados no site ". env("WEB_NAME")). " - Acesso para download gratuito para dispositivos Android e iOS";
    }

    public function categorySongs($slug){
        // Slug Solve //
        $category = Category::where("category_slug", $slug)->where("display",1)->first();
        $song = Song::where("slug", $slug)->where("display",1)->first();
        $post = Post::where("slug", $slug)->where("display",1)->first();

        if ($category != null){ // has category

            $songs = Song::where("category_id", $category->id)->where("display", 1)->paginate(10);
            $title = "Download Ringtone Nada Dering $category->category_name Gratis";
            $metaDes = "Download Ringtone Nada Dering Telepon $category->category_name Gratis, Download Ringtone iPhone iphone, android mp3 m4r free ";
            return $this->loadView($songs, $title, $title, $metaDes);

            // return view
        } elseif ($song!= null){ // has Song

            $similarSongs = Song::where("category_id", $song->category_id)
                ->where("display", 1)
                ->where("id", "!=", $song->id)
                ->limit(12)->get();
            $currentListener = $song->listeners;
            Song::where("id", $song->id)->update(["listeners" => $currentListener+1]);
            return view("webpage.song.index",
                ["song" => $song, "similarSongs" => $similarSongs, "og_title" => $song->meta_title,
                    "og_des" => $song->meta_description]);

        } elseif ($post != null){ // has Post

            return view("webpage.post.index", ["post" => $post]);
        }
        else {
            abort("404");
        }
    }

    public function losMejores(){
        $songs  = Song::orderBy("downloads", "desc")->where("display", 1)->paginate(14);
        return $this->loadView($songs,
            "Melhores toques",
            "Melhores toques - ". env("WEB_NAME"),
            "Downloads 100% gratuitos - Muitos dos melhores toques são altamente avaliados pelos usuários do nosso site. Baixar nos formatos MP3 e M4R - ". env("WEB_NAME"));
    }
    public function search(Request $request, $search){
        $songs = Song::where('title', 'LIKE', "%$search%")->paginate(14);
        return $this->loadView($songs, "Search Results: $search", "You searched for $search",
            "");
    }
}
