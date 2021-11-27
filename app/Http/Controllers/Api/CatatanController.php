<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Catatan;
use DB;
class CatatanController extends Controller
{
    //
    public function index(){
        $catatan = Catatan::all();
        if(count($catatan)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $catatan
            ],200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id){
        $catatan = Catatan::find($id);
        if(!is_null($catatan)){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $catatan
            ],200);
        }
        return response([
            'message' => 'Catatan not found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'judul' => 'required',
            'user_id'=>'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()],400);
        }

        DB::table('catatans')->insert([
            'judul'=>$storeData['judul'],
            'user_id'=>$storeData['user_id']
        ]);
        return response([
            'message' => 'Add Catatan Success',
        ], 200);
    }

    public function destroy($id){
        $catatan = Catatan::find($id);

        if(is_null($catatan)){
            return response([
                'message' => 'Catatan Not Found',
                'data' => null
            ], 404);
        }

        if($catatan->delete()){
            return response([
                'message' => 'Delete Catatan Success',
                'data' => $catatan
            ], 200);
        }
        return response([
            'message' => 'Delete Catatan Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id){
        $catatan = Catatan::find($id);
        if(is_null($catatan)){
            return response([
                'message' => 'Catatan Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'judul' => 'required',
            'isi' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()],400);
        }

        $catatan->isi= $updateData['isi'];
        $catatan->judul = $updateData['judul'];;

        if($catatan->save()){
            return response([
                'message' => 'Update Catatan Success',
                'data' => $catatan
            ], 200);
        }
        return response([
            'message' => 'Update Catatan Failed',
            'data' => null
        ],400);
    }
}
