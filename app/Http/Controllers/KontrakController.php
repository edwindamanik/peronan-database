<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Contract;
use DNS2D;
use App\Models\MandatoryRetribution;
use App\Models\LetterSetting;
use Illuminate\Support\Carbon;

class KontrakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        // $data = DB::table('contracts')
        //     ->join('unit_types', 'contracts.unit_id', '=', 'unit_types.id')
        //     ->join('obligation_retributions', 'contracts.wajib_retribusi_id', '=', 'obligation_retributions.id')
        //     ->join('users', 'contracts.wajib_retribusi_id', '=', 'users.id')
        //     ->join('letter_settings', 'contracts.pengaturan_id', '=', 'letter_settings.id')
        //     ->where('letter_settings.kabupaten_id', $kabupatenId)
        //     ->whereIn('unit_types.jenis_pembayaran', ['bulanan', 'tahunan'])
        //     ->select('contracts.*', 'users.nama', 'unit_types.kode', 'unit_types.jenis_unit', 'unit_types.panjang', 'unit_types.lebar', 'unit_types.jenis_pembayaran')
        //     ->paginate();

             $data = DB::table('contracts')
            ->join('units', 'contracts.unit_id', '=', 'units.id')
            ->join('unit_types', 'units.jenis_unit_id', '=', 'unit_types.id')
            ->join('obligation_retributions', 'contracts.wajib_retribusi_id', '=', 'obligation_retributions.id')
            ->join('users', 'obligation_retributions.users_id', '=', 'users.id')
            ->join('letter_settings', 'contracts.pengaturan_id', '=', 'letter_settings.id')
            ->join('regencies', 'letter_settings.kabupaten_id', '=', 'regencies.id')
            // ->where('contracts.id', $id) 
            ->where('letter_settings.kabupaten_id', $kabupatenId)
            ->select('contracts.*', 'users.nama', 'unit_types.kode', 'unit_types.jenis_unit', 'unit_types.panjang', 'unit_types.lebar', 'unit_types.jenis_pembayaran')
            ->paginate();


        $wajib_retribusi = DB::table('obligation_retributions')
            ->join('users', 'obligation_retributions.users_id', '=', 'users.id')
            ->where('kabupaten_id', $kabupatenId)
            ->where('users.role', 'wajib_retribusi')
            ->select('obligation_retributions.*', 'users.nama')
            ->get();

        $unit = DB::table('units')
            ->join('unit_types', 'units.jenis_unit_id', '=', 'unit_types.id')
            ->where('kabupaten_id', $kabupatenId)
            ->select('units.*')
            ->get();

        $pengaturan = DB::table('letter_settings')
            ->where('kabupaten_id', $kabupatenId)
            ->get();
   
        // dd($data);
        return view('admin.kontrak', compact('data', 'wajib_retribusi', 'unit', 'pengaturan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function setuju($id)
    {
        $contract = Contract::find($id); // Ganti sesuai model dan data Anda

        if (!$contract) {
            return redirect()->route('contracts.index')->with('danger', 'Kontrak tidak ditemukan.');
        }

        if ($contract->status != 'benar') {
            $contract->status = 'benar';
            $contract->save();

            return redirect()->route('contracts.index')->with('success', 'Status kontrak berhasil diubah .');
        }

        return redirect()->route('contracts.index')->with('danger', 'Kontrak sudah dalam status Setuju.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $contract = new Contract;

            if ($request->hasFile('file_pdf')) {

                // save the new file
                $pdf = $request->file('file_pdf');
                $pdfName = time() . '.' . $pdf->extension();
                $pdf->move(public_path('contract'), $pdfName);

                // update the deposit data with the new file name
                $contract->file_pdf = $pdfName;
            }

            $contract->no_surat = $request->input('noSurat');
            $contract->tanggal_kontrak = null;
            $contract->tanggal_mulai = $request->input('tanggalMulai');
            $contract->tanggal_selesai = $request->input('tanggalSelesai');
            $contract->status = $request->input('status');
            $contract->wajib_retribusi_id = $request->input('wajibRetribusi');
            $contract->unit_id = $request->input('unitId');
            $contract->pengaturan_id = $request->input('pengaturanSurat');
            $contract->save();

            return back()->with('storeMessage', 'Kontrak berhasil ditambahkan');
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $contract = Contract::findOrFail($id);

            if ($request->hasFile('file_pdf')) {
                // delete the old file
                if (\File::exists(public_path('contract/' . $contract->file_pdf))) {
                    \File::delete(public_path('contract/' . $contract->file_pdf));
                }

                // save the new file
                $file = $request->file('file_pdf');
                $fileName = time() . '.' . $file->extension();
                $file->move(public_path('contract'), $fileName);

                // update the contract data with the new file name
                $contract->file_pdf = $fileName;
            }

            $contract->no_surat = $request->input('noSurat');
            $contract->tanggal_mulai = $request->input('tanggalMulai');
            $contract->tanggal_selesai = $request->input('tanggalSelesai');
            $contract->status = $request->input('status');
            $contract->wajib_retribusi_id = $request->input('wajibRetribusi');
            $contract->unit_id = $request->input('unitId');
            $contract->pengaturan_id = $request->input('pengaturanSurat');

            $harga = DB::table('units')
                ->join('unit_types', 'units.jenis_unit_id', '=', 'unit_types.id')
                ->join('retribution_fees', 'unit_types.id', '=', 'retribution_fees.jenis_unit_id')
                ->select('retribution_fees.harga')
                ->where('units.id', $contract->unit_id)
                ->first();

            if ($contract->status == 'benar') {
                $contract->tanggal_kontrak = now();
            }

            $contract->save();

            if ($contract->status == 'benar') {
                $jatuhTempo = Carbon::parse($contract->tanggal_mulai)->lastOfMonth();
                $tanggalSelesai = Carbon::parse($contract->tanggal_selesai);

                while ($jatuhTempo <= $tanggalSelesai) {

                    $mandatory_retribution = new MandatoryRetribution();
                    $mandatory_retribution->no_tagihan = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
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

            return back()->with('updateMessage', 'Kontrak berhasil diperbarui');
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    public function preview($id)
    {
        $batal = Contract::find($id);
        $surat = LetterSetting::first();

        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
        $data = DB::table('contracts')
            ->join('units', 'contracts.unit_id', '=', 'units.id')
            ->join('unit_types', 'units.jenis_unit_id', '=', 'unit_types.id')
            ->join('obligation_retributions', 'contracts.wajib_retribusi_id', '=', 'obligation_retributions.id')
            ->join('users', 'obligation_retributions.users_id', '=', 'users.id')
            ->join('letter_settings', 'contracts.pengaturan_id', '=', 'letter_settings.id')
            ->join('regencies', 'letter_settings.kabupaten_id', '=', 'regencies.id')
            ->where('contracts.id', $id) 
            ->where('letter_settings.kabupaten_id', $kabupatenId)
            ->select('contracts.*', 'users.nama', 'letter_settings.*', 'regencies.*', 'units.*', 'unit_types.*')
            ->get();
            


        $wajib_retribusi = DB::table('obligation_retributions')
            ->join('users', 'obligation_retributions.users_id', '=', 'users.id')
            ->where('kabupaten_id', $kabupatenId)
            ->where('users.role', 'wajib_retribusi')
            ->select('obligation_retributions.*', 'users.nama')
            ->get();

        $unit = DB::table('units')
            ->join('unit_types', 'units.jenis_unit_id', '=', 'unit_types.id')
            ->where('kabupaten_id', $kabupatenId)
            ->select('units.*')
            ->get();

        $pengaturan = DB::table('letter_settings')
            ->where('kabupaten_id', $kabupatenId)
            ->get();

        // dd($data);

        

        return view('admin.kontrakview', compact('data', 'wajib_retribusi', 'unit', 'pengaturan'));

        // return $pdf->download('surat-kontrak.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('contracts')->where('id', $id)->delete();

        return back()->with('deleteMessage', 'Kontrak Berhasil Dihapus');
    }
}