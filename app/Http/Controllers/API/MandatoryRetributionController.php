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
        $data = DB::table('mandatory_retributions')
                ->join('contracts', 'mandatory_retributions.contract_id', '=', 'contracts.id')
                ->join('obligation_retributions', 'contracts.wajib_retribusi_id', '=', 'obligation_retributions.id')
                ->join('users', 'obligation_retributions.users_id', '=', 'users.id')
                ->where('mandatory_retributions.metode_pembayaran', 'cash')
                ->where('contracts.status', 'benar')
                ->get();

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

    public function getBulanan($pasar_id) 
    {
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
                ->where('units.pasar_id', $pasar_id)
                ->whereYear('mandatory_retributions.jatuh_tempo', '=', date('Y'))
                ->whereMonth('mandatory_retributions.jatuh_tempo', '<', date('m'))
                ->groupBy('mandatory_retributions.contract_id')
                ->get();

        $bulan_setelahnya = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.contract_id')
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->selectRaw('COUNT(mandatory_retributions.biaya_retribusi) as jumlah_periode')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('mandatory_retributions.petugas_id', $petugas_id)
                ->where('units.pasar_id', $pasar_id)
                ->where(function ($query) {
                    $query->whereYear('mandatory_retributions.jatuh_tempo', '=', date('Y'))
                          ->whereMonth('mandatory_retributions.jatuh_tempo', '>', date('m'))
                          ->orWhereYear('mandatory_retributions.jatuh_tempo', '>', date('Y'));
                })
                ->groupBy('mandatory_retributions.contract_id')
                ->count();

        $data = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->join('units', 'units.id', '=', 'contracts.unit_id')
                ->select('mandatory_retributions.id', 'mandatory_retributions.no_tagihan' ,'users.nama', 'mandatory_retributions.no_tagihan', 'mandatory_retributions.biaya_retribusi', 'mandatory_retributions.status_pembayaran', 'mandatory_retributions.jatuh_tempo', 'mandatory_retributions.contract_id', 'units.no_unit', 'users.nama', 'obligation_retributions.nik')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                // ->where('mandatory_retributions.petugas_id', $petugas_id)
                ->where('units.pasar_id', $pasar_id)
                ->whereYear('mandatory_retributions.jatuh_tempo', '=', date('Y'))
                ->whereMonth('mandatory_retributions.jatuh_tempo', '=', date('m'))
                ->get()
                ->map(function ($item) use ($bulan_sebelumnya, $bulan_setelahnya) {
                    $total_biaya = $bulan_sebelumnya->firstWhere('contract_id', $item->contract_id)->total_biaya ?? 0;
                    $item->total_retribusi = $item->biaya_retribusi + $total_biaya;
                    $item->tunggakan = intval($total_biaya);
                    $item->total_bulan_setelahnya = $bulan_setelahnya;
                    $item->jumlah_periode = $bulan_sebelumnya->firstWhere('contract_id', $item->contract_id)->jumlah_periode+1 ?? 0;
                    return $item;
                });

        return response()->json(['data' => $data]);
    }

    public function updateStatusPembayaran(Request $request, $id) {
        try {
            $validateMandatoryRetribution = $request->validate([
                'no_tagihan' => 'required',
                'no_tagihan_ref' => '',
                'biaya_retribusi' => 'required',
                'total_retribusi' => 'required',
                'tanggal_pembayaran' => 'required',
                'jatuh_tempo' => 'required',
                'metode_pembayaran' => 'required',
                'status_pembayaran' => 'required',
                'url_pembayaran_va' => '',
                'url_pembayaran_qris' => '',
                'petugas_id' => 'required',
                'contract_id' => 'required',
            ]);
            
            $mandatory_retribution = MandatoryRetribution::findOrFail($id);
        
            // Cek apakah jatuh tempo saat ini adalah bulan ini dan status pembayaran adalah sudah_dibayar
            $now = Carbon::now();
            $jatuhTempo = Carbon::createFromFormat('Y-m-d', $validateMandatoryRetribution['jatuh_tempo']);
            if ($now->month === $jatuhTempo->month && $validateMandatoryRetribution['status_pembayaran'] === 'sudah_dibayar') {
                // Ambil semua data dengan jatuh tempo kurang dari bulan saat ini dan contract_id yang sama
                $mandatoryRetributions = MandatoryRetribution::where('jatuh_tempo', '<', $now->toDateString())
                    ->where('contract_id', $validateMandatoryRetribution['contract_id'])
                    ->get();
        
                // Update status_pembayaran dari setiap data yang ditemukan
                foreach ($mandatoryRetributions as $mandatoryRetribution) {
                    $mandatoryRetribution->status_pembayaran = 'sudah_dibayar';
                    $mandatoryRetribution->save();
                }
            }

            $mandatory_retribution->update($validateMandatoryRetribution);

            // Ubah status_pembayaran pada data dengan jatuh tempo lebih besar dari bulan sekarang dengan rentang waktu sebanyak count bulan
            $count = $request->input('count');
            $endDate = Carbon::now()->addMonths($count+1); // Hitung tanggal akhir rentang waktu
            $mandatoryRetributions = MandatoryRetribution::where('jatuh_tempo', '>', $now->toDateString())
                ->where('jatuh_tempo', '<=', $endDate->toDateString())
                ->where('contract_id', $validateMandatoryRetribution['contract_id'])
                ->get();

            // Update status_pembayaran dari setiap data yang ditemukan
            foreach ($mandatoryRetributions as $mandatoryRetribution) {
                $mandatoryRetribution->status_pembayaran = 'sudah_dibayar';
                $mandatoryRetribution->save();
            }

            return response()->json(['data' => $mandatory_retribution]);
        
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function cashlessPayment(Request $request)
    {
        try {
            $clientId = env("DOKU_CLIENT_ID");
            $requestId = "fdb69f47-96da-499d-acec-7cdc318ab2fb";
            $requestDate = gmdate('Y-m-d\TH:i:s\Z');
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
                ->select('mandatory_retributions.*', 'units.no_unit', 'users.nama', 'obligation_retributions.nik')
                ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
                ->where('users.id', $user_id)
                ->whereYear('mandatory_retributions.jatuh_tempo', '=', date('Y'))
                ->whereMonth('mandatory_retributions.jatuh_tempo', '=', date('m'))
                ->get()
                ->map(function ($item) use ($bulan_sebelumnya) {
                    $total_biaya = $bulan_sebelumnya->firstWhere('contract_id', $item->contract_id)->total_biaya ?? 0;
                    $item->total_retribusi = $item->biaya_retribusi + $total_biaya;
                    $item->tunggakan = intval($total_biaya);
                    $item->jumlah_periode = $bulan_sebelumnya->firstWhere('contract_id', $item->contract_id)->jumlah_periode+1 ?? 0;
                    return $item;
                });

        return response()->json([
            'data' => $data,
        ]);
    }

    public function notifications(Request $request) {
        $notificationHeader = getallheaders();
        $notificationBody = file_get_contents('php://input');
        $notificationPath = '/payments/notifications'; // Adjust according to your notification path
        $secretKey = 'SK-e8acCt3iB1a1A0Jodfad'; // Adjust according to your secret key
    
        $digest = base64_encode(hash('sha256', $notificationBody, true));
        $rawSignature = "Client-Id:" . $notificationHeader['Client-Id'] . "\n"
            . "Request-Id:" . $notificationHeader['Request-Id'] . "\n"
            . "Request-Timestamp:" . $notificationHeader['Request-Timestamp'] . "\n"
            . "Request-Target:" . $notificationPath . "\n"
            . "Digest:" . $digest;
    
        $signature = base64_encode(hash_hmac('sha256', $rawSignature, $secretKey, true));
        $finalSignature = 'HMACSHA256=' . $signature;
    
        if ($finalSignature == $headers['Signature']) {
            // TODO: Process if Signature is Valid
            return response('OK', 200)->header('Content-Type', 'text/plain');
    
            // TODO: Do update the transaction status based on the `transaction.status`
        } else {
            // TODO: Response with 400 errors for Invalid Signature
            return response('Invalid Signature', 400)->header('Content-Type', 'text/plain');
        }
    }
}
