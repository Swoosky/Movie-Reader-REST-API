<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    //method utk tampil semua
    public function index() {
        $articles = Article::All(); //mengambil semua
        
        if(count($articles) > 0) {
            return response([
                'message' => 'Retrieve all success!',
                'data' => $articles
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);

    }
    
    //method utk tampil semua untuk satu user
    public function indexOneUser($name) {
        $articles = Article::where('author', $name)->get(); //mengambil yang dari author name
        
        if(count($articles) > 0) {
            return response([
                'message' => 'Retrieve all success!',
                'data' => $articles
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);

    }

    //method utk tampil 1
    public function show($id) {
        $article = Article::find($id); //mengambil 1
        
        if(!is_null($article)    ) {
            return response([
                'message' => 'Retrieve Article success!',
                'data' => $article
            ], 200); //return success
        }

        return response([
            'message' => 'Article not found',
            'data' => null
        ], 400); //return tidak ketemu

    }

    //method utk tambah 1 data
    public function store(Request $request) {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'img_url' => 'required|url',
            'title' => 'required',
            'body' => 'required',
            'author' => 'required'
        ]); //membuat rule validasi input

        if($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400); //return error invalid input
        }

        $article = Article::create($storeData);
        return response([
            'message' => 'Add course success!',
            'data' => $article
        ], 200); //membuat course baru
    }

    //method utk menghapus 1
    public function destroy($id) {
        $article = Article::find($id); //mengambil 1
        
        if(is_null($article)) {
            return response([
                'message' => 'Article not found',
                'data' => null
            ], 404); //return tidak ditemukan
        }
        
        if($article->delete()) {
            return response([
                'message' => 'Delete course success!',
                'data' => $article
            ], 200); //return tidak ditemukan
        }

        return response([
            'message' => 'Delete course failed',
            'data' => null
        ],400); //return gagal hapus
    }

    //method utk update
    public function update(Request $request, $id) {
        $article = Article::find($id);
        
        if(is_null($article)) {
            return response([
                'message' => 'Article not found',
                'data' => null
            ], 404); //return tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'img_url' => 'required|url',
            'title' => 'required',
            'body' => 'required',
            'author' => 'required'
        ]); //membuat validasi

        if($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400); //return error invalid input
        }
        
        $article->img_url = $updateData['img_url'];
        $article->title = $updateData['title'];
        $article->body = $updateData['body'];
        $article->author = $updateData['author'];

        if($article->save()) {
            return response([
                'message' => 'Update Article Success!',
                'data' => $article
            ], 200);
        } //return data yang diedit dlm json

        return response([
            'message' => 'Update Article Failed',
            'data' => null
        ], 400);
        
    }
}
