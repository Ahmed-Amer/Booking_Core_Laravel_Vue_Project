<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class SavedReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Report::all();
        return datatables($users)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:2',
            'description' => 'required|min:5',
            'file' => 'required|max:10000|mimes:pdf',
        ]);

        $file = $request->file('file');
        $pdfName = $file->getClientOriginalName();
        $updatedpdfName =  'HotelBnb' . '_' . $pdfName;
        $file->move('reports' , $updatedpdfName);

        $report = new Report;
        $report->title = $request->title;
        $report->description = $request->description;
        $report->file_name = $updatedpdfName;
        $report->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $report =  Report::findOrFail($id);

        $filePath = public_path() . "/reports/" . $report->file_name;

        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        $fileName =  'HotelBnb' . time().'.pdf';

        return response()->download($filePath, $fileName, $headers);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $report =  Report::findOrFail($id);
        $filePath = public_path() . "/reports/" . $report->file_name;
        unlink($filePath);
        $report->delete();

        $reports =  Report::all();
        return datatables($reports)->toJson();
    }
}
