<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    // recuperation de tous les blogs
    public function index()
    {
        $blogs = Blog::all();
        return response()->json([
            "error" => false,
            "blogs" => $blogs,
        ]);
    }

    public function blogEnable(){
        $blogs =DB::table('blogs')->where('status',true)
        ->get();
        return response()->json([
            "error" => false,
            "blogs" => $blogs,
        ]);
    }

    // création des blogs
    public function store(Request $request)
    {
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->subject = $request->subject;
        $blog->photo = $request->photo;
        if($request->file('photo')){
            $pathTofile = $request->file('photo');
            $file_name_image= "images/".time().'_'.$pathTofile->getClientOriginalName();
            $pathTofile-> move(public_path('images'), $file_name_image);
            $blog->photo = $file_name_image;
        }
        return $blog->save();
        return response()->json('Publication ajouté avec success', 200);
    }

    // afficher une blog avec son id
    public function show($id){
        $blog = Blog::find($id);
        return response()->json([
            "error" => false,
            "blog" => $blog,
        ]);

    }

    // modifier une blog
    public function destroy($id){
        $blog = Blog::find($id);
        $valider = $blog->delete();
        if($valider){
            return response()->json(['message'=>"Publication supprimée avec success"]);
        }else{
            return response()->json(['message'=>'Erreur lors de la suppression ']);
        }
    }

    // changer une blog
    public function changeStatut($id){
        $blog = Blog::find($id);
        if($blog->status){
            $blog->status = false;
        }else{
            $blog->status = true;
        }
        $blog->save();
        return response()->json(['message'=>'Statut changé avec success']);
    }

    // supprimer une blog
    public function update($id, Request $request){
        $blog = Blog::find($id);
        $blog->title = $request->title;
        $blog->subject = $request->subject;
        $blog->photo = $request->photo;
        if($request->file('photo')){
            $pathTofile = $request->file('photo');
            $file_name_image= "images".time().'_'.$pathTofile->getClientOriginalName();
            $pathTofile-> move(public_path('images'), $file_name_image);
            $blog->photo = $file_name_image;
        }
        if($blog->update())
            return response()->json('blog a été modifier avec success', 200);
        return response()->json('Erreur de mise a jour', 403);
    }

}
