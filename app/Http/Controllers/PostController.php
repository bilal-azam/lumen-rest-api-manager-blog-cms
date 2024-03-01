<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class PostController extends Controller
{
    public function index()
    {
        $getPost = Post::OrderBy("id", "DESC")->paginate(10);

        $out = [
            "message" => "list_post",
            "results" => $getPost
        ];

        return response()->json($out, 200);
    }

    public function store(Request $request)
    {

        if ($request->isMethod('post')) {

            $this->validate($request, [
                'title' => 'required',
                'body' => 'required'
            ]);

            $title = $request->input('title');
            $body = $request->input('body');

            $data = [
                'slug'  => Str::slug($title, "-"),
                'title' => $title,
                'body' => $body
            ];

            $insert = Post::create($data);

            if ($insert) {
                $out  = [
                    "message" => "success_insert_data",
                    "results" => $data,
                    "code"  => 200
                ];
            } else {
                $out  = [
                    "message" => "vailed_insert_data",
                    "results" => $data,
                    "code"   => 404,
                ];
            }

            return response()->json($out, $out['code']);
        }
    }

    public function update(Request $request)
    {

        if ($request->isMethod('patch')) {

            $this->validate($request, [
                'title' => 'required',
                'body'  => 'required',
                'id'    => 'required'
            ]);
            $id = $request->input('id');
            $title = $request->input('title');
            $body = $request->input('body');

            $post = Post::find($id);

            $data = [
                'slug'  => Str::slug($title, "-"),
                'title' => $title,
                'body' => $body
            ];

            $update = $post->update($data);

            if ($update) {
                $out  = [
                    "message" => "success_update_data",
                    "results" => $data,
                    "code"  => 200
                ];
            } else {
                $out  = [
                    "message" => "vailed_update_data",
                    "results" => $data,
                    "code"   => 404,
                ];
            }

            return response()->json($out, $out['code']);
        }
    }

    public function destroy ($id)
    {
        $posts = Post::find($id);

        if (!$posts) {
            $data = [
                "message" => "id not found",
            ];
        } else  {
            $posts->delete();
            $data = [
                "message" => "succes deleted"
            ];
        }
        return response()->json($data, 200);
    }
}