<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use PDF;
  
class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $data = [
            'title' => 'Testing PDF',
            'date' => date('m/d/Y')
        ];
        
        $pdf = PDF::loadView('pdfreports.mypdf', $data);
        
        return $pdf->download('dummy.pdf');
        
    }
}