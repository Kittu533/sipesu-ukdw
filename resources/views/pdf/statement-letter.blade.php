<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement Letter</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .header p {
            margin: 2px 0;
            font-size: 11pt;
        }
        
        .divider {
            border-bottom: 2px solid black;
            margin: 20px 0;
        }
        
        .title {
            text-align: center;
            margin: 30px 0;
        }
        
        .title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0;
        }
        
        .content {
            text-align: justify;
            margin: 20px 0;
        }
        
        .opening {
            margin: 20px 0;
            font-weight: bold;
        }
        
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border: 1px solid black;
        }
        
        .student-table th,
        .student-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        
        .student-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 30%;
        }
        
        .statement {
            margin: 20px 0;
            text-align: justify;
        }
        
        .signature {
            margin-top: 50px;
            text-align: right;
            width: 350px;
            float: right;
        }
        
        .signature-space {
            height: 80px;
            margin: 20px 0;
        }
        
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h1>UNIVERSITAS KRISTEN DUTA WACANA</h1>
        <h1>BIRO ADMINISTRASI AKADEMIK</h1>
        <h1>(Academic Administration Bureau)</h1>
        <p>Jl. Dr. Wahidin Sudirohusodo No. 5-25, Yogyakarta 55224</p>
        <p>Phone: 0274-563929 (Ext. 111 & 144) | Email: biro1@staff.ukdw.ac.id</p>
    </div>
    
    <div class="divider"></div>
    
    <!-- TITLE -->
    <div class="title">
        <h2>STATEMENT LETTER</h2>
        <p>Number: {{ $document_number }}</p>
    </div>
    
    <!-- CONTENT -->
    <div class="content">
        <div class="opening">
            This is to certify that:
        </div>
        
        <!-- Student Information Table -->
        <table class="student-table">
            <tr>
                <th>Full Name</th>
                <td>{{ $student['full_name'] }}</td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td>{{ $student['date_of_birth'] }}</td>
            </tr>
            <tr>
                <th>Student ID</th>
                <td>{{ $student['student_id'] }}</td>
            </tr>
            <tr>
                <th>Institution</th>
                <td>{{ $student['institution'] }}</td>
            </tr>
            <tr>
                <th>Faculty</th>
                <td>{{ $student['faculty'] }}</td>
            </tr>
            <tr>
                <th>Department</th>
                <td>{{ $student['department'] }}</td>
            </tr>
            <tr>
                <th>Period of Study</th>
                <td>{{ $student['study_period'] }}</td>
            </tr>
            <tr>
                <th>Degree Awarded</th>
                <td>{{ $student['degree_awarded'] }}</td>
            </tr>
        </table>
        
        <!-- Statement of Completion -->
        <div class="statement">
            <p>Has successfully completed all academic and administrative requirements for the degree program as stated above. The degree <strong>{{ $student['degree_awarded'] }}</strong> was officially conferred by the Faculty of {{ $student['faculty'] }} in {{ $student['graduation_date'] }}.</p>
            
            <p>This statement is issued to be used as necessary.</p>
        </div>
    </div>
    
    <!-- SIGNATURE -->
    <div class="signature">
        <p>Yogyakarta, {{ $issue_date }}</p>
        <p>{{ $signatory['position'] }}</p>
        <p>Universitas Kristen Duta Wacana</p>
        <div class="signature-space">
            <p style="font-style: italic; color: #666; margin-top: 30px;">(Digital Signature & Official Seal)</p>
        </div>
        <p><strong>{{ $signatory['name'] }}</strong></p>
    </div>
    
    <div class="clear"></div>
</body>
</html>
