<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\LaporanFoto;
use App\Models\JenisLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $laporanFotos = LaporanFoto::where('status', 'pending')->get();

        $uniqueLaporanFotos = $laporanFotos->unique('foto_id');

        $fotoReportCounts = $laporanFotos->groupBy('foto_id')->map(function ($reports) {
            return $reports->pluck('user_id')->unique()->count();
        });

         // Loop through the reports and update status if count equals 5
        foreach ($fotoReportCounts as $fotoId => $reportCount) {
            if ($reportCount >= 5) {
                LaporanFoto::where('foto_id', $fotoId)->update(['status' => 'approved']);
            }
        }

        // dd($fotoReportCounts);

        return view('admin.index', compact('uniqueLaporanFotos', 'fotoReportCounts'));
    }

    public function detail($id)
    {
        // Fetch the detail data based on the provided photo_id
        $detailData = LaporanFoto::where('foto_id', $id)->with('user', 'jenisLaporan')->get();

        // Return the detail data as JSON response
        return response()->json($detailData);
    }


    public function approvePhoto(Request $request)
    {
        $request->validate([
            'foto_id' => 'required|exists:laporan_fotos,foto_id',
        ]);
    
        $fotoId = $request->input('foto_id');
    
        // Start a transaction to ensure atomicity
        DB::beginTransaction();
    
        try {
            // Update the status of the photo to 'approved' in the LaporanFotos table
            LaporanFoto::where('foto_id', $fotoId)->update(['status' => 'approved']);
    
            // Soft delete the photo (if needed)
            $foto = Foto::where('id', $fotoId)->first();
            if ($foto) {
                $foto->delete();
            }
    
            // Commit the transaction
            DB::commit();
    
            return redirect()->back()->with('success', 'Photo approved successfully');
        } catch (\Exception $e) {
            // If an error occurs, rollback the transaction
            DB::rollback();
            // Handle the error as needed
            return redirect()->back()->with('error', 'Failed to approve photo');
        }
    }

    
    
    
    // public function getDetailData($foto_id)
    
    // {
    //     $laporanFoto = LaporanFoto::findOrFail($foto_id);
    //     $user_name = $laporanFoto->user->name;
    //     $jenis_laporan_name = $laporanFoto->jenisLaporan->jenis_laporan;

    //     return response()->json([
    //         'user_name' => $user_name,
    //         'jenis_laporan_name' => $jenis_laporan_name,
    //     ]);
    // }
}
