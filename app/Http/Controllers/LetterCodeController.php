<?php

namespace App\Http\Controllers;

use App\Models\LetterCode;
use Illuminate\Support\Facades\DB;

class LetterCodeController extends Controller
{
    public function getLetterCodeList()
    {
        $letterCodes = LetterCode::getLetterCodes()->select(
            'id',
            DB::raw("CONCAT(letter_codes.code,' - ',letter_codes.title) as code_title"),
            'code'
        )->get();

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil diambil.',
            'data' => $letterCodes
        ], 200);
    }

    public function getSubLetterCodeList($letter_code)
    {
        $letterCode = LetterCode::find($letter_code);
        $subLetterCodes = $letterCode->sub_letter_codes()
            ->select('id', DB::raw("CONCAT(letter_codes.code,' - ',letter_codes.title) as code_title"), 'code')
            ->get();

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil diambil.',
            'data' => $subLetterCodes
        ], 200);
    }

    public function get($id)
    {
        $letteCode = LetterCode::find($id);

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil diambil',
            'data' => $letteCode
        ], 200);
    }

    public function getLetterCodeName($id)
    {
        $code = LetterCode::find($id);
        $name = $code->code . '. ' . $code->title;

        if ($code->letter_code)
            $name = $code->letter_code->code . '.' . $code->code . ' ' . $code->title;

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $name
        ], 200);
    }

    public function store(Request $request)
    {
        $code = new LetterCode();
        $code->code = $request->code;
        $code->title = $request->title;

        if ($code->save()) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil menambahkan data.',
                'data' => $code
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menambahkan data.',
            'data' => ''
        ], 417);
    }

    public function update($id, Request $request)
    {
        $code = LetterCode::findOrFail($id);
        $code->code = $request->code;
        $code->title = $request->title;

        if ($code->update()) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil mengubah data.',
                'data' => $code
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal mengubah data.',
            'data' => ''
        ], 417);
    }

    public function delete($id)
    {
        $code = LetterCode::findOrFail($id);
        $code->delete();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil menghapus data.',
        ], 200);
    }

    public function addSub($id, Request $request)
    {
        $code = LetterCode::findOrFail($id);
        $subCode = new LetterCode([
            'code' => $request->code,
            'title' => $request->title,
        ]);

        if ($code->sub_letter_codes()->save($subCode)) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil menambah data.',
                'data' => $subCode
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menambah data.',
            'data' => ''
        ], 417);
    }

    public function deleteSub($id)
    {
        $code = LetterCode::findOrFail($id);
        $code->delete();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil menghapus data.',
        ], 200);
    }
}
