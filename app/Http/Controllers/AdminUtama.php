<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class AdminUtama extends Controller
{
    public function indexfaq(Request $request)
    {


        $data = DB::table('faqs')
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(5);


        if ($request->wantsJson()) {
            if ($data->isEmpty()) {
                $responseData = [
                    'message' => 'No data found.',
                    'data' => [],
                ];
                return response()->json($responseData, Response::HTTP_OK);
            } else {
                $responseData = [
                    'message' => 'Data retrieved successfully.',
                    'data' => $data,
                ];
                return response()->json($responseData, Response::HTTP_OK);
            }
        }

        return view('adminutama.faq', compact('data'));
    }

    public function updatefaq(Request $request, $id)
    {
        $user = Auth::user();

        DB::table('faqs')
            ->where('id', $id)
            ->update([
                'pertanyaan' => $request->input('pertanyaanfaq'),
                'jawaban' => $request->input('jawabanfaq')

            ]);

        return back()->with('updateMessage', 'FAQ berhasil diperbarui');
    }

    public function storefaq(Request $request)
    {

        $now = now();

        // Jika validasi berhasil, simpan data kelompok pasar baru
        DB::table('faqs')->insert([
            'pertanyaan' => $request->input('pertanyaanfaq'),
            'jawaban' => $request->input('jawabanfaq'),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        return back()->with('storeMessage', 'FAQ berhasil ditambahkan');
    }

    public function destroyfaq($id)
    {
        DB::table('faqs')->where('id', $id)->delete();

        return back()->with('deleteMessage', 'FAQ Berhasil Dihapus');
    }


    public function indexadv(Request $request)
    {


        $data = DB::table('advances')
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(5);


        if ($request->wantsJson()) {
            if ($data->isEmpty()) {
                $responseData = [
                    'message' => 'No data found.',
                    'data' => [],
                ];
                return response()->json($responseData, Response::HTTP_OK);
            } else {
                $responseData = [
                    'message' => 'Data retrieved successfully.',
                    'data' => $data,
                ];
                return response()->json($responseData, Response::HTTP_OK);
            }
        }

        return view('adminutama.keuntungan  ', compact('data'));
    }

    public function updateadv(Request $request, $id)
    {
        $user = Auth::user();

        DB::table('advances')
            ->where('id', $id)
            ->update([
                'judul' => $request->input('judulK'),
                'deskripsi' => $request->input('deskripsiK')

            ]);

        return back()->with('updateMessage', 'keuntungan berhasil diperbarui');
    }

    public function storeadv(Request $request)
    {

        $now = now();

        // Jika validasi berhasil, simpan data kelompok pasar baru
        DB::table('advances')->insert([
            'judul' => $request->input('judulK'),
            'deskripsi' => $request->input('deskripsiK'),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        return back()->with('storeMessage', 'Keuntungan berhasil ditambahkan');
    }

    public function indexdat(Request $request)
    {


        $data = DB::table('decs_peronans')
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(5);


        if ($request->wantsJson()) {
            if ($data->isEmpty()) {
                $responseData = [
                    'message' => 'No data found.',
                    'data' => [],
                ];
                return response()->json($responseData, Response::HTTP_OK);
            } else {
                $responseData = [
                    'message' => 'Data retrieved successfully.',
                    'data' => $data,
                ];
                return response()->json($responseData, Response::HTTP_OK);
            }
        }

        return view('adminutama.dataperonan  ', compact('data'));
    }

    public function updatedat(Request $request, $id)
    {
        $user = Auth::user();

        DB::table('decs_peronans')
            ->where('id', $id)
            ->update([
                'email' => $request->input('emailD'),
                'alamat' => $request->input('alamatD'),
                'notelepon' => $request->input('noteleponD')

            ]);

        return back()->with('updateMessage', 'Data PErOnan berhasil diperbarui');
    }

    public function indexppt()
    {
        return view('view-ppsx');
    }
    public function indexapp(Request $request)
    {


        $data = DB::table('regencies')
            ->where('regencies.status', 'nonaktif')
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(5);


        if ($request->wantsJson()) {
            if ($data->isEmpty()) {
                $responseData = [
                    'message' => 'No data found.',
                    'data' => [],
                ];
                return response()->json($responseData, Response::HTTP_OK);
            } else {
                $responseData = [
                    'message' => 'Data retrieved successfully.',
                    'data' => $data,
                ];
                return response()->json($responseData, Response::HTTP_OK);
            }
        }

        return view('adminutama.setujuidinas  ', compact('data'));
    }

    public function updatestatus(Request $request, $id)
    {
        $status = $request->input('status');
        // Pastikan status valid sesuai dengan ENUM yang ada di tabel "regencies"

        // Update status di database
        DB::table('regencies')
            ->where('id', $id)
            ->update(['status' => $status]);

        // Kirim respons berhasil
        return response()->json(['message' => 'Status updated successfully.'], Response::HTTP_OK);
    }

}