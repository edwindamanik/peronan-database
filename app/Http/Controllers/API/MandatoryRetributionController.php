<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MandatoryRetribution;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MandatoryRetributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = Carbon::now();
        $tanggalTerakhir = $now->endOfMonth()->format('Y-m-d');

        $bulan_sebelumnya = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.contract_id')
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->selectRaw('COUNT(mandatory_retributions.biaya_retribusi) as jumlah_periode')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('units.pasar_id', $pasar_id)
                ->where('mandatory_retributions.jatuh_tempo', '<', $tanggalTerakhir)
                ->groupBy('mandatory_retributions.contract_id')
                ->get();

        // $hasil_bulan_sebelumnya = $bulan_sebelumnya->first();

        $bulan_sekarang = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.contract_id')
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->selectRaw('COUNT(mandatory_retributions.biaya_retribusi) as jumlah_periode')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('units.pasar_id', $pasar_id)
                ->where('mandatory_retributions.jatuh_tempo', '=', $tanggalTerakhir)
                ->groupBy('mandatory_retributions.contract_id')
                ->get();

        // $hasil_bulan_sekarang = $bulan_sekarang->first();

        $bulan_setelahnya = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.contract_id')
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->selectRaw('COUNT(mandatory_retributions.biaya_retribusi) as jumlah_periode')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('units.pasar_id', $pasar_id)
                ->where('mandatory_retributions.jatuh_tempo', '>', $tanggalTerakhir)
                ->groupBy('mandatory_retributions.contract_id')
                ->get();

        $data = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.id', 'mandatory_retributions.no_tagihan' ,'users.nama', 'mandatory_retributions.no_tagihan', 'mandatory_retributions.biaya_retribusi', 'mandatory_retributions.status_pembayaran', 'mandatory_retributions.jatuh_tempo', 'mandatory_retributions.contract_id', 'units.no_unit', 'users.nama', 'obligation_retributions.nik', 'units.pasar_id')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('mandatory_retributions.petugas_id', $petugas_id)
                // ->where('units.pasar_id', $pasar_id)
                ->whereYear('mandatory_retributions.jatuh_tempo', '=', date('Y'))
                ->whereMonth('mandatory_retributions.jatuh_tempo', '=', date('m'))
                ->get();

        if (!empty($bulan_sebelumnya)) {
            foreach ($data as $index => $item) {
                if (isset($bulan_sebelumnya[$index])) {
                    $item->total_retribusi = intval($bulan_sebelumnya[$index]->total_biaya) + intval($bulan_sekarang[$index]->total_biaya);
                    $item->tunggakan = intval($bulan_sebelumnya[$index]->total_biaya);
                    $item->total_bulan_setelahnya = $bulan_setelahnya[$index]->jumlah_periode;
                    $item->jumlah_periode = $bulan_sebelumnya[$index]->jumlah_periode + $bulan_sekarang[$index]->jumlah_periode;
                } else {
                    // Handle ketika data $bulan_sebelumnya tidak ada
                    $item->total_retribusi = intval($bulan_sekarang[$index]->total_biaya);
                    $item->tunggakan = 0;
                    $item->total_bulan_setelahnya = $bulan_setelahnya[$index]->jumlah_periode;
                    $item->jumlah_periode = $bulan_sekarang[$index]->jumlah_periode;
                }
            }
        } else {
            // Handle ketika data $bulan_sebelumnya tidak ada untuk semua elemen $data
            foreach ($data as $item) {
                $item->total_retribusi = intval($bulan_sekarang[0]->total_biaya);
                $item->tunggakan = 0;
                $item->total_bulan_setelahnya = $bulan_setelahnya;
                $item->jumlah_periode = $bulan_sekarang[0]->jumlah_periode;
            }
        }

        return response()->json(['data' => $data]);
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
        $mandatory_retribution = MandatoryRetribution::create([
            'no_tagihan' => $request->input('no_tagihan'),
            'durasi_pembayaran' => $request->input('durasi_pembayaran'),
            'biaya_retribusi' => $request->input('biaya_retribusi'),
            'total_retribusi' => $request->input('total_retribusi'),
            'tanggal_pembayaran' => $request->input('tanggal_pembayaran'),
            'jatuh_tempo' => $request->input('jatuh_tempo'),
            'metode_pembayaran' => $request->input('metode_pembayaran'),
            'status_pembayaran' => $request->input('status_pembayaran'),
            'url_pembayaran_va' => $request->input('url_pembayaran_va'),
            'url_pembayaran_qris' => $request->input('url_pembayaran_qris'),
            'petugas_id' => $request->input('petugas_id'),
            'contract_id' => $request->input('contract_id'),
        ]);

        return response()->json(['data' => $mandatory_retribution], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mandatory_retribution = MandatoryRetribution::findOrFail($id);

        return response()->json(['data' => $mandatory_retribution]);
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
        $validateMandatoryRetribution = $request->validate([
            'no_tagihan' => 'required',
            'durasi_pembayaran' => 'required',
            'biaya_retribusi' => 'required',
            'total_retribusi' => 'required',
            'tanggal_pembayaran' => 'required',
            'jatuh_tempo' => 'required',
            'metode_pembayaran' => 'required',
            'status_pembayaran' => 'required',
            'url_pembayaran_va' => 'required',
            'url_pembayaran_qris' => 'required',
            'petugas_id' => 'required',
            'contract_id' => 'required',
        ]);

        $mandatory_retribution = MandatoryRetribution::findOrFail($id);
        $mandatory_retribution->update($validateMandatoryRetribution);

        return response()->json(['data' => $mandatory_retribution]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mandatory_retribution = MandatoryRetribution::findOrFail($id);
        $mandatory_retribution->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function getBulanan() 
    {
        $now = Carbon::now();
        $tanggalTerakhir = $now->endOfMonth()->format('Y-m-d');

        $bulan_sebelumnya = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.contract_id')
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->selectRaw('COUNT(mandatory_retributions.biaya_retribusi) as jumlah_periode')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('units.pasar_id', $pasar_id)
                ->where('mandatory_retributions.jatuh_tempo', '<', $tanggalTerakhir)
                ->groupBy('mandatory_retributions.contract_id')
                ->get();

        // $hasil_bulan_sebelumnya = $bulan_sebelumnya->first();

        $bulan_sekarang = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.contract_id')
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->selectRaw('COUNT(mandatory_retributions.biaya_retribusi) as jumlah_periode')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('units.pasar_id', $pasar_id)
                ->where('mandatory_retributions.jatuh_tempo', '=', $tanggalTerakhir)
                ->groupBy('mandatory_retributions.contract_id')
                ->get();

        // $hasil_bulan_sekarang = $bulan_sekarang->first();

        $bulan_setelahnya = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.contract_id')
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->selectRaw('COUNT(mandatory_retributions.biaya_retribusi) as jumlah_periode')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('units.pasar_id', $pasar_id)
                ->where('mandatory_retributions.jatuh_tempo', '>', $tanggalTerakhir)
                ->groupBy('mandatory_retributions.contract_id')
                ->get();

        $data = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->join('unit_types', 'unit_types.id', '=', 'units.jenis_unit_id')
                ->select('mandatory_retributions.id', 'unit_types.jenis_unit', 'mandatory_retributions.no_tagihan' ,'users.nama', 'mandatory_retributions.no_tagihan', 'mandatory_retributions.biaya_retribusi', 'mandatory_retributions.status_pembayaran', 'mandatory_retributions.jatuh_tempo', 'mandatory_retributions.contract_id', 'units.no_unit', 'users.nama', 'obligation_retributions.nik', 'units.pasar_id')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('mandatory_retributions.petugas_id', $petugas_id)
                // ->where('units.pasar_id', $pasar_id)
                ->whereYear('mandatory_retributions.jatuh_tempo', '=', date('Y'))
                ->whereMonth('mandatory_retributions.jatuh_tempo', '=', date('m'))
                ->get();

        if (!empty($bulan_sebelumnya)) {
            foreach ($data as $index => $item) {
                if (isset($bulan_sebelumnya[$index])) {
                    $item->total_retribusi = intval($bulan_sebelumnya[$index]->total_biaya) + intval($bulan_sekarang[$index]->total_biaya);
                    $item->tunggakan = intval($bulan_sebelumnya[$index]->total_biaya);
                    $item->total_bulan_setelahnya = $bulan_setelahnya[$index]->jumlah_periode;
                    $item->jumlah_periode = $bulan_sebelumnya[$index]->jumlah_periode + $bulan_sekarang[$index]->jumlah_periode;
                } else {
                    // Handle ketika data $bulan_sebelumnya tidak ada
                    $item->total_retribusi = intval($bulan_sekarang[$index]->total_biaya);
                    $item->tunggakan = 0;
                    $item->total_bulan_setelahnya = $bulan_setelahnya[$index]->jumlah_periode;
                    $item->jumlah_periode = $bulan_sekarang[$index]->jumlah_periode;
                }
            }
        } else {
            // Handle ketika data $bulan_sebelumnya tidak ada untuk semua elemen $data
            foreach ($data as $item) {
                $item->total_retribusi = intval($bulan_sekarang[0]->total_biaya);
                $item->tunggakan = 0;
                $item->total_bulan_setelahnya = $bulan_setelahnya;
                $item->jumlah_periode = $bulan_sekarang[0]->jumlah_periode;
            }
        }

        return response()->json(['data' => $data]);
    }

    public function updateStatusPembayaran(Request $request) {
        try {
            
            $dataToUpdate = $request->input('data');
            foreach($dataToUpdate as $data) {
                $id = $data['id'];
                unset($data['id']);

                $mandatory_retribution = MandatoryRetribution::findOrFail($id);

                $bulan_sebelumnya = MandatoryRetribution::where('jatuh_tempo', '<=', $data['jatuh_tempo'])
                            ->where('contract_id', $data['contract_id'])
                            ->get();

                foreach($bulan_sebelumnya as $pembayaran_nonHarian) {
                    $pembayaran_nonHarian->status_pembayaran = 'sudah_dibayar';
                    $pembayaran_nonHarian->metode_pembayaran = 'CASH';
                    $pembayaran_nonHarian->tanggal_pembayaran = Carbon::now();
                    $pembayaran_nonHarian->total_retribusi = $data['total_retribusi'];
                    $pembayaran_nonHarian->petugas_id = $data['petugas_id'];
                    $pembayaran_nonHarian->save();
                }

                // $mandatory_retribution->update($data);

                $count = $data['count'];
                if($count > 0) {
                    $bulan_setelahnya = MandatoryRetribution::where('jatuh_tempo', '>', $data['jatuh_tempo'])
                                ->where('contract_id', $data['contract_id'])
                                ->orderBy('jatuh_tempo', 'asc') 
                                ->take($count) 
                                ->get();

                    foreach($bulan_setelahnya as $pembayaran_nonHarian) {
                        $pembayaran_nonHarian->status_pembayaran = 'sudah_dibayar';
                        $pembayaran_nonHarian->metode_pembayaran = 'CASH';
                        $pembayaran_nonHarian->tanggal_pembayaran = Carbon::now();
                        $pembayaran_nonHarian->total_retribusi = $data['total_retribusi'];
                        $pembayaran_nonHarian->petugas_id = $data['petugas_id'];
                        $pembayaran_nonHarian->save();
                    }
                }
            }
        
            return response()->json(['data' => 'Berhasil Melakukan Pembayaran']);
        
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function cashlessPayment(Request $request)
    {
        try {
            $randomString = Str::uuid()->toString();
            $formattedString = substr($randomString, 0, 13) . 'l';

            $clientId = env("DOKU_CLIENT_ID");
            $requestId = $formattedString;
            $requestDate = gmdate('Y-m-d\TH:i:s\Z');
            // $targetPath = "/checkout/v1/payment"; // For merchant request to Jokul, use Jokul path here. For HTTP Notification, use merchant path here
            $targetPath = "/mandiri-virtual-account/v2/payment-code"; // For merchant request to Jokul, use Jokul path here. For HTTP Notification, use merchant path here
            $secretKey = env("DOKU_SECRET_KEY");
            $requestBody = $request->json()->all();

            // Generate Digest
            $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));

            // Prepare Signature Component
            $componentSignature = "Client-Id:" . $clientId . "\n" .
                "Request-Id:" . $requestId . "\n" .
                "Request-Timestamp:" . $requestDate . "\n" .
                "Request-Target:" . $targetPath . "\n" .
                "Digest:" . $digestValue;

            // Calculate HMAC-SHA256 base64 from all the components above
            $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

            // Sample of Usage
            $headerSignature =  "Client-Id:" . $clientId . "\n" .
                "Request-Id:" . $requestId . "\n" .
                "Request-Timestamp:" . $requestDate . "\n" .
                // Prepend encoded result with algorithm info HMACSHA256=
                "Signature:" . "HMACSHA256=" . $signature;

            // Send POST request to API endpoint
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Client-Id' => $clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requestDate,
                'Signature' => 'HMACSHA256=' . $signature,
            // ])->post("https://api-sandbox.doku.com/checkout/v1/payment", $requestBody);
            ])->post("https://api-sandbox.doku.com/mandiri-virtual-account/v2/payment-code", $requestBody);

            $responseJson = json_decode($response->body(), true);
            $httpCode = $response->status();

            return response()->json([
                'data' => $responseJson,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function cashlessPaymentQris(Request $request)
    {
        try {
            $randomString = Str::uuid()->toString();
            $formattedString = substr($randomString, 0, 13) . 'l';

            $clientId = env("DOKU_CLIENT_ID");
            $requestId = $formattedString;
            $requestDate = gmdate('Y-m-d\TH:i:s\Z');
            $targetPath = "/checkout/v1/payment"; // For merchant request to Jokul, use Jokul path here. For HTTP Notification, use merchant path here
            // $targetPath = "/mandiri-virtual-account/v2/payment-code"; // For merchant request to Jokul, use Jokul path here. For HTTP Notification, use merchant path here
            $secretKey = env("DOKU_SECRET_KEY");
            $requestBody = $request->json()->all();

            // Generate Digest
            $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));

            // Prepare Signature Component
            $componentSignature = "Client-Id:" . $clientId . "\n" .
                "Request-Id:" . $requestId . "\n" .
                "Request-Timestamp:" . $requestDate . "\n" .
                "Request-Target:" . $targetPath . "\n" .
                "Digest:" . $digestValue;

            // Calculate HMAC-SHA256 base64 from all the components above
            $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

            // Sample of Usage
            $headerSignature =  "Client-Id:" . $clientId . "\n" .
                "Request-Id:" . $requestId . "\n" .
                "Request-Timestamp:" . $requestDate . "\n" .
                // Prepend encoded result with algorithm info HMACSHA256=
                "Signature:" . "HMACSHA256=" . $signature;

            // Send POST request to API endpoint
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Client-Id' => $clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requestDate,
                'Signature' => 'HMACSHA256=' . $signature,
            ])->post("https://api-sandbox.doku.com/checkout/v1/payment", $requestBody);
            // ])->post("https://api-sandbox.doku.com/mandiri-virtual-account/v2/payment-code", $requestBody);

            $responseJson = json_decode($response->body(), true);
            $httpCode = $response->status();

            return response()->json([
                'data' => $responseJson,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRetribusiWr($user_id) {
        $bulan_sebelumnya = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.contract_id')
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->selectRaw('COUNT(mandatory_retributions.biaya_retribusi) as jumlah_periode')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('mandatory_retributions.petugas_id', $petugas_id)
                ->where('users.id', $user_id)
                ->whereYear('mandatory_retributions.jatuh_tempo', '=', date('Y'))
                ->whereMonth('mandatory_retributions.jatuh_tempo', '<', date('m'))
                ->groupBy('mandatory_retributions.contract_id')
                ->get();

        $data = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.*', 'units.no_unit', 'users.nama', 'obligation_retributions.nik', 'users.id AS wr_id', 'units.pasar_id')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                ->where('users.id', $user_id)
                ->whereYear('mandatory_retributions.jatuh_tempo', '=', date('Y'))
                ->whereMonth('mandatory_retributions.jatuh_tempo', '=', date('m'))
                ->get()
                ->map(function ($item) use ($bulan_sebelumnya) {
                    $retribusiData = $bulan_sebelumnya->firstWhere('contract_id', $item->contract_id);
        
                    if ($retribusiData) {
                        $total_biaya = $retribusiData->total_biaya;
                        $jumlah_periode = $retribusiData->jumlah_periode + 1;
                    } else {
                        $total_biaya = 0;
                        $jumlah_periode = 0;
                    }
        
                    $item->total_retribusi = $item->biaya_retribusi + $total_biaya;
                    $item->tunggakan = intval($total_biaya);
                    $item->jumlah_periode = $jumlah_periode;
                    return $item;
                });

        return response()->json([
            'data' => $data,
        ]);
    }

    public function notifications(Request $request) {
        try {
            $notificationHeader = getallheaders();
            $notificationBody = file_get_contents('php://input');
            $notificationPath = '/api/after-payments'; // Adjust according to your notification path
            $secretKey = env("DOKU_SECRET_KEY"); // Adjust according to your secret key
        
            $digest = base64_encode(hash('sha256', $notificationBody, true));
            $rawSignature = "Client-Id:" . $notificationHeader['Client-Id'] . "\n"
                . "Request-Id:" . $notificationHeader['Request-Id'] . "\n"
                . "Request-Timestamp:" . $notificationHeader['Request-Timestamp'] . "\n"
                . "Request-Target:" . $notificationPath . "\n"
                . "Digest:" . $digest;
        
            $signature = base64_encode(hash_hmac('sha256', $rawSignature, $secretKey, true));
            $finalSignature = 'HMACSHA256=' . $signature;

            // return response()->json(['data' => $finalSignature]);
        
            if ($finalSignature == $notificationHeader['Signature']) {
                // TODO: Process if Signature is Valid

                if ($transactionStatus = $request->input('transaction.status')) {
        
                    if ($transactionStatus === 'SUCCESS') {
                        $currentMonth = Carbon::now()->format('m');
                        $currentDate = Carbon::now()->format('Y-m-d');

                        $invoiceNumber = $request->input('order.invoice_number');
                        $parts = explode('-', $invoiceNumber);

                        // Get the numbers from the exploded array
                        $pasar_id = intval($parts[count($parts) - 3]);
                        $wr_id = intval($parts[count($parts) - 2]);
                        $contract_id = intval($parts[count($parts) - 1]);

                        // $nextInvoiceNumber = explode('-', $invoiceNumber);
                        // $finalNumber = end($nextInvoiceNumber);
                        // $finalNumber = (int)$finalNumber;

                        $depositId = DB::table('deposits')->insertGetId([
                            'jumlah_setoran' => $request->input('order.amount'),
                            'penyetoran_melalui' => 'VA',
                            'tanggal_penyetoran' => now(),
                            'tanggal_disetor' => null,
                            'bukti_setoran' => null,
                            'status' => 'menunggu_konfirmasi',
                            'alasan_tidak_setor' => null,
                            'users_id' => $wr_id,
                            'pasar_id' => $pasar_id
                        ]);

                        DB::table('mandatory_retributions')
                            ->where('status_pembayaran', 'belum_dibayar')
                            ->where('contract_id', $contract_id)
                            ->where(function ($query) use ($currentMonth, $currentDate) {
                                $query->where(function ($query) use ($currentMonth, $currentDate) {
                                    $query->whereMonth('jatuh_tempo', '<', $currentMonth)
                                        ->orWhere(function ($query) use ($currentMonth) {
                                            $query->whereMonth('jatuh_tempo', $currentMonth)
                                                ->where('jatuh_tempo', '<=', Carbon::now()->format('Y-m-d'));
                                        });
                                })
                                ->orWhereMonth('jatuh_tempo', $currentMonth); // Menambahkan kondisi ini
                            })
                            ->update(['status_pembayaran' => 'sudah_dibayar', 'total_retribusi' => $request->input('order.amount'), 'metode_pembayaran' => $request->input('service.id'), 'tanggal_pembayaran' => $currentDate, 'deposit_id' => $depositId]);

                        if($request->input('service.id') == 'VIRTUAL_ACCOUNT') {
                            DB::table('va_payments')->insert([
                                'reference_number' => $request->input('virtual_account_payment.reference_number'),
                                'date' => Carbon::createFromFormat('YmdHis', $request->input('virtual_account_payment.date'))->format('Y-m-d H:i:s'),
                                'original_request_id' => $request->input('transaction.original_request_id'),
                                'transaksi_id' => $request->input('virtual_account_payment.identifier.0.value'),
                                'channel_id' => $request->input('virtual_account_payment.identifier.1.value'),
                                'invoice_number' => $request->input('order.invoice_number'),
                                'amount' => $request->input('order.amount'),
                                'pasar_id' => $pasar_id,
                                'virtual_account_number' => $request->input('virtual_account_info.virtual_account_number'),
                                'status' => 'menunggu'
                            ]);
                        }

                    }
                }

                return response('OK', 200)->header('Content-Type', 'text/plain');
                // return response()->json([
                //     'data' => $finalNumber,
                // ]);
        
                // TODO: Do update the transaction status based on the `transaction.status`
            } else {
                // TODO: Response with 400 errors for Invalid Signature
                return response('Invalid Signature', 400)->header('Content-Type', 'text/plain');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function riwayatPembayaran($user_id) {
        $data = DB::table('mandatory_retributions')
                    ->join('contracts', 'mandatory_retributions.contract_id', '=', 'contracts.id')
                    ->join('obligation_retributions', 'contracts.wajib_retribusi_id', '=', 'obligation_retributions.id')
                    // ->join('users', 'obligation_retributions.users_id', '=', 'users.id')
                    ->select('mandatory_retributions.total_retribusi', 'mandatory_retributions.tanggal_pembayaran')
                    ->distinct('mandatory_retributions.tanggal_pembayaran')
                    ->whereNotNull('mandatory_retributions.tanggal_pembayaran')
                    ->whereIn('mandatory_retributions.metode_pembayaran', ['VIRTUAL_ACCOUNT', 'QRIS'])
                    ->where('obligation_retributions.users_id', $user_id)
                    ->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
