<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Payment</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
     <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            padding: 20px;
        }
        .alert-dismissible .btn-close {
            position: absolute;
            right: 1rem;
            top: 1rem;
        }
        #product-details {
            margin-top: 20px;
        }
        .card-element {
            margin-bottom: 10px;
        }
        .loader {
            display: none;
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
            margin-left: 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>