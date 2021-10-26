<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Course;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    //method utk tampil semua
    public function index() {
        $courses = Course::All(); //mengambil semua
        
        if(count($courses) > 0) {
            return response([
                'message' => 'Retrieve all success!',
                'data' => $courses
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);

    }

    //method utk tampil 1
    public function show($id) {
        $course = Course::find($id); //mengambil 1
        
        if(!is_null($course)    ) {
            return response([
                'message' => 'Retrieve Course success!',
                'data' => $course
            ], 200); //return success
        }

        return response([
            'message' => 'Course not found',
            'data' => null
        ], 400); //return tidak ketemu

    }

    //method utk tambah 1 data
    public function store(Request $request) {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_kelas' => 'required|max:60|unique:courses',
            'kode' => 'required',
            'biaya_pendaftaran' => 'required|numeric',
            'kapasitas' => 'required|numeric'
        ]); //membuat rule validasi input

        if($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400); //return error invalid input
        }

        $course = Course::create($storeData);
        return response([
            'message' => 'Add course success!',
            'data' => $course
        ], 200); //membuat course baru
    }

    //method utk menghapus 1
    public function destroy($id) {
        $course = Course::find($id); //mengambil 1
        
        if(is_null($course)) {
            return response([
                'message' => 'Course not found',
                'data' => null
            ], 404); //return tidak ditemukan
        }
        
        if($course->delete()) {
            return response([
                'message' => 'Delete course success!',
                'data' => $course
            ], 200); //return tidak ditemukan
        }

        return response([
            'message' => 'Delete course failed',
            'data' => null
        ],400); //return gagal hapus
    }

    //method utk update
    public function update(Request $request, $id) {
        $course = Course::find($id);
        
        if(is_null($course)) {
            return response([
                'message' => 'Course not found',
                'data' => null
            ], 404); //return tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_kelas' => ['max:60', 'required', Rule::unique('courses')->ignore($course)],
            'kode' => 'required',
            'biaya_pendaftaran' => 'required|numeric',
            'kapasitas' => 'required|numeric'
        ]); //membuat validasi

        if($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400); //return error invalid input
        }
        
        $course->nama_kelas = $updateData['nama_kelas'];
        $course->kode = $updateData['kode'];
        $course->biaya_pendaftaran = $updateData['biaya_pendaftaran'];
        $course->kapasitas = $updateData['kapasitas'];

        if($course->save()) {
            return response([
                'message' => 'Update Course Success!',
                'data' => $course
            ], 200);
        } //return data yang diedit dlm json

        return response([
            'message' => 'Update Course Failed',
            'data' => null
        ], 400);
        
    }
}
