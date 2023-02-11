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
            "Baixar Toques Novos de graça no celular – ToquesDeCelular",
            "Coleção dos toques mais recentes que você pode baixar totalmente grátis para o seu celular. Últimos toques para Android e iPhone em alta qualidade.");
    }
    public function popularSongs()
    {
        $songs = Song::orderBy("listeners", "desc")->where("display", 1)->paginate(14);
        return $this->loadView($songs,
            "Toques Populares",
            "Baixar Toques Populares Mp3 Gratis - Top Top Ringtones",
            "Coleção de toques populares mais baixados. Os melhores toques para celular que você não deve perder em ToquesDeCelular.Com");
    }

    public function categorySongs($slug){
        // Slug Solve //
        $category = Category::where("category_slug", $slug)->where("display",1)->first();
        $song = Song::where("slug", $slug)->where("display",1)->first();
        $post = Post::where("slug", $slug)->where("display",1)->first();

        if ($category != null){ // has category

            $songs = Song::where("category_id", $category->id)->where("display", 1)->paginate(10);
            $title = "Baixar Toques Para Celular $category->category_name Grátis";
            $metaDes = "Baixar Toques Para Celular $category->category_name Grátis, Download Ringtone iPhone iphone, android mp3 m4r free ";
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
            "Baixar Melhores toques gratis - Best Ringtone Collection",
            "Com a melhor coleção de ringtones atualizada todos os dias, você pode baixar seus ringtones favoritos totalmente grátis no ToquesDeCelular.Com");
    }
    public function search(Request $request, $search){
        $songs = Song::where('title', 'LIKE', "%$search%")->paginate(14);
        return $this->loadView($songs, "Search Results: $search", "You searched for $search",
            "");
    }
}
