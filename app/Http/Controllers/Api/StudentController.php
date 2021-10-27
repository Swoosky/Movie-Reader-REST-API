<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    //method utk tampil semua
    public function index() {
        $students = Student::All(); //mengambil semua
        
        if(count($students) > 0) {
            return response([
                'message' => 'Retrieve all success!',
                'data' => $students
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);

    }

    //method utk tampil 1
    public function show($id) {
        $student = Student::find($id); //mengambil 1
        
        if(!is_null($student)) {
            return response([
                'message' => 'Retrieve Student success!',
                'data' => $student
            ], 200); //return success
        }

        return response([
            'message' => 'Student not found',
            'data' => null
        ], 400); //return tidak ketemu

    }

    //method utk tambah 1 data
    public function store(Request $request) {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_murid' => 'required|regex:/(^[A-Za-z ]+$)+/',
            'npm' => 'required|numeric',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'no_telp' => 'required|regex:/(^08\d)+/|digits_between:0,13'
        ]); //membuat rule validasi input

        if($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400); //return error invalid input
        }

        $student = Student::create($storeData);
        return response([
            'message' => 'Add student success!',
            'data' => $student
        ], 200); //membuat course baru
    }

    //method utk menghapus 1
    public function destroy($id) {
        $student = Student::find($id); //mengambil 1
        
        if(is_null($student)) {
            return response([
                'message' => 'Student not found',
                'data' => null
            ], 404); //return tidak ditemukan
        }
        
        if($student->delete()) {
            return response([
                'message' => 'Delete student success!',
                'data' => $student
            ], 200); //return tidak ditemukan
        }

        return response([
            'message' => 'Delete student failed',
            'data' => null
        ],400); //return gagal hapus
    }

    //method utk update
    public function update(Request $request, $id) {
        $student = Student::find($id);
        
        if(is_null($student)) {
            return response([
                'message' => 'Student not found',
                'data' => null
            ], 404); //return tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_murid' => 'required|regex:/(^[A-Za-z ]+$)+/',
            'npm' => 'required|numeric',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'no_telp' => 'required|regex:/(^08\d)+/|digits_between:0,13'
        ]); //membuat validasi

        if($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400); //return error invalid input
        }
        
        $student->nama_murid = $updateData['nama_murid'];
        $student->npm = $updateData['npm'];
        $student->tanggal_lahir = $updateData['tanggal_lahir'];
        $student->no_telp = $updateData['no_telp'];

        if($student->save()) {
            return response([
                'message' => 'Update Student Success!',
                'data' => $student
            ], 200);
        } //return data yang diedit dlm json

        return response([
            'message' => 'Update Student Failed',
            'data' => null
        ], 400);
        
    }
}
