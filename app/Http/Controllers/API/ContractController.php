<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Contract;
use App\Models\MandatoryRetribution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contracts = Contract::all();
        return response()->json(['data' => $contracts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        try {
            $request->validate([
                'no_surat' => 'required',
                'tanggal_mulai' => '',
                'tanggal_mulai' => 'required',
                'tanggal_selesai' => 'required',
                'status' => 'required',
                'file_pdf' => 'required',
                'wajib_retribusi_id' => 'required',
                'unit_id' => 'required',
                'pengaturan_id' => 'required',
            ]); 

            $contract = new Contract;
        
            if ($request->hasFile('file_pdf')) {
        
                // save the new file
                $pdf = $request->file('file_pdf');
                $pdfName = time().'.'.$pdf->extension();
                $pdf->move(public_path('contract'), $pdfName);
        
                // update the deposit data with the new file name
                $contract->file_pdf = $pdfName;
            }
        
            $contract->no_surat = $request->input('no_surat');
            $contract->tanggal_kontrak = $request->input('tanggal_kontrak');
            $contract->tanggal_mulai = $request->input('tanggal_mulai');
            $contract->tanggal_selesai = $request->input('tanggal_selesai');
            $contract->status = $request->input('status');
            $contract->wajib_retribusi_id = $request->input('wajib_retribusi_id');
            $contract->unit_id = $request->input('unit_id');
            $contract->pengaturan_id = $request->input('pengaturan_id');
            $contract->save();

            // get the image URL
            $pdfUrl = asset('contract/' . $contract->file_pdf);
        
            return response()->json([
                'message' => 'Data contract berhasil ditambahkan',
                'pdf_url' => $pdfUrl,
            ]);

        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }       
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contract = Contract::findOrFail($id);

        return response()->json(['data' => $contract]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {

            $request->validate([
                'no_surat' => 'required',
                'tanggal_kontrak' => '',
                'tanggal_mulai' => 'required',
                'tanggal_selesai' => 'required',
                'status' => 'required',
                'file_pdf' => 'required',
                'feedback' => 'string',
                'wajib_retribusi_id' => 'required',
                'unit_id' => 'required',
                'pengaturan_id' => 'required'
            ]);
        
            $contract = Contract::findOrFail($id);
        
            if ($request->hasFile('file_pdf')) {
                // delete the old file
                if (\File::exists(public_path('contract/' . $contract->file_pdf))) {
                    \File::delete(public_path('contract/' . $contract->file_pdf));
                }
        
                // save the new file
                $file = $request->file('file_pdf');
                $fileName = time().'.'.$file->extension();
                $file->move(public_path('contract'), $fileName);
        
                // update the contract data with the new file name
                $contract->file_pdf = $fileName;
            }
        
            $contract->no_surat = $request->input('no_surat');
            $contract->tanggal_mulai = $request->input('tanggal_mulai');
            $contract->tanggal_selesai = $request->input('tanggal_selesai');
            $contract->status = $request->input('status');
            $contract->wajib_retribusi_id = $request->input('wajib_retribusi_id');
            $contract->unit_id = $request->input('unit_id');
            $contract->pengaturan_id = $request->input('pengaturan_id');
            $contract->feedback = $request->input('feedback');

            $harga = DB::table('units')
                    ->join('unit_types', 'units.jenis_unit_id', '=', 'unit_types.id')
                    ->join('retribution_fees', 'unit_types.id', '=', 'retribution_fees.jenis_unit_id')
                    ->select('retribution_fees.harga')
                    ->where('units.id', $contract->unit_id)
                    ->first();

            if($contract->status == 'benar') {
                $contract->tanggal_kontrak = now();
            }

            $contract->save();

            if($contract->status == 'benar') {
                $jatuhTempo = Carbon::parse($contract->tanggal_mulai)->lastOfMonth();
                $tanggalSelesai = Carbon::parse($contract->tanggal_selesai);
                $randomNumber = mt_rand(10000, 99999);

                while ($jatuhTempo <= $tanggalSelesai) {

                    $mandatory_retribution = new MandatoryRetribution();
                    $mandatory_retribution->no_tagihan = mt_rand(10000, 99999).$contract->id;
                    $mandatory_retribution->no_tagihan_ref = null;
                    $mandatory_retribution->biaya_retribusi = $harga->harga;
                    $mandatory_retribution->total_retribusi = $harga->harga;
                    $mandatory_retribution->tanggal_pembayaran = null;
                    $mandatory_retribution->jatuh_tempo = $jatuhTempo;
                    $mandatory_retribution->metode_pembayaran = null;
                    $mandatory_retribution->status_pembayaran = 'belum_dibayar';
                    $mandatory_retribution->url_pembayaran_va = null;
                    $mandatory_retribution->url_pembayaran_qris = null;
                    $mandatory_retribution->petugas_id = $request->input('petugas_id');
                    $mandatory_retribution->contract_id = $contract->id;
                    $mandatory_retribution->save();

                    $jatuhTempo->addMonthNoOverflow();
                    $jatuhTempo->lastOfMonth();

                }

            }

            // get the image URL
            $fileUrl = asset('contract/' . $contract->file_pdf);
        
            return response()->json([
                'message' => 'Data contracts berhasil diupdate',
                'image_url' => $fileUrl,
            ]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function getContractByUser($user_id) {
        $data = DB::table('contracts')
                ->join('units', 'contracts.unit_id', '=', 'units.id')
                ->join('obligation_retributions', 'contracts.wajib_retribusi_id', '=', 'obligation_retributions.id')
                ->join('users', 'obligation_retributions.users_id', '=', 'users.id')
                ->select('units.no_unit', 'users.nama', 'contracts.*')
                ->where('users.id', $user_id)
                ->get();

        return response()->json(['data' => $data]);
    }

    public function finalContract(Request $request, $id) {

    }
}
