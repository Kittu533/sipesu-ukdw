<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class StatementLetterController extends Controller
{
    public function generatePDF(Request $request)
    {
        // Data mahasiswa lulus
        $studentData = [
            'full_name' => $request->input('full_name', 'SAMUEL RICKY SAPUTRO'),
            'date_of_birth' => $request->input('date_of_birth', 'March 15, 1998'),
            'student_id' => $request->input('student_id', '12345678'),
            'institution' => $request->input('institution', 'Universitas Kristen Duta Wacana'),
            'faculty' => $request->input('faculty', 'Information Technology'),
            'department' => $request->input('department', 'Informatics'),
            'study_period' => $request->input('study_period', 'August 2016 - August 2020'),
            'degree_awarded' => $request->input('degree_awarded', 'Bachelor of Informatics / B.Inf.'),
            'graduation_date' => $request->input('graduation_date', 'August 2020'),
        ];

        // Data penandatangan
        $signatoryData = [
            'name' => 'Drs. Wimmie Handiwidjojo, MIT',
            'position' => 'Head of Academic Administration Bureau',
        ];

        $documentNumber = $this->generateDocumentNumber();

        $data = [
            'student' => $studentData,
            'signatory' => $signatoryData,
            'document_number' => $documentNumber,
            'issue_date' => Carbon::now()->format('F d, Y'),
        ];

        $pdf = Pdf::loadView('pdf.statement-letter', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('statement-letter-' . $studentData['student_id'] . '.pdf');
    }

    private function generateDocumentNumber()
    {
        $year = date('Y');
        $counter = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return "{$counter}/C.12/BAA/UKDW/{$year}";
    }
}
