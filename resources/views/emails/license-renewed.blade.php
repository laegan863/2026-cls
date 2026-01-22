<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Renewed</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header .emoji {
            font-size: 48px;
            display: block;
            margin-bottom: 15px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #28a745;
            font-size: 16px;
        }
        .info-box ul {
            margin: 0;
            padding-left: 20px;
        }
        .info-box li {
            margin: 8px 0;
        }
        .info-box strong {
            color: #555;
        }
        .document-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 2px dashed #2196f3;
            padding: 20px;
            margin: 25px 0;
            border-radius: 10px;
            text-align: center;
        }
        .document-box h3 {
            margin: 0 0 10px 0;
            color: #1976d2;
        }
        .document-box p {
            margin: 0 0 15px 0;
            color: #555;
        }
        .btn {
            display: inline-block;
            padding: 14px 30px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3);
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-download {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 4px 6px rgba(40, 167, 69, 0.3);
        }
        .note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
            font-size: 14px;
        }
        .note strong {
            color: #856404;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="emoji">🎉</span>
            <h1>Your License Has Been Renewed!</h1>
        </div>
        
        <div class="content">
            <p class="greeting">Hello,</p>
            
            <p>Great news! Your license has been <strong>successfully renewed and processed</strong>. Your application is now complete!</p>
            
            <div class="info-box">
                <h3>📋 License Details</h3>
                <ul>
                    <li><strong>Transaction ID:</strong> {{ $license->transaction_id }}</li>
                    <li><strong>Legal Name:</strong> {{ $license->legal_name }}</li>
                    <li><strong>New Expiration Date:</strong> {{ $newExpirationDate }}</li>
                </ul>
            </div>
            
            @if($evidenceFile)
            <div class="document-box">
                <h3>📎 Renewal Evidence Document Available</h3>
                <p>A renewal certificate/evidence document has been uploaded for your records.<br>Click the button below to view and download your document.</p>
                <a href="{{ $licenseUrl }}" class="btn btn-download">📥 View License & Download Document</a>
            </div>
            
            <div class="note">
                <strong>📌 Important:</strong> Please save this document for your records as proof of your license renewal.
            </div>
            @else
            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ $licenseUrl }}" class="btn">View License Details</a>
            </div>
            @endif
            
            <p>Thank you for your continued trust in our services!</p>
        </div>
        
        <div class="footer">
            <p><strong>CLS Team</strong></p>
            <p>This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
