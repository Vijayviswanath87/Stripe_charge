<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>E-Shopping -Stripe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/"><b>Stripe Payment</b></a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <?php
                // Determine the current URL path
                $url = parse_url($_SERVER['REQUEST_URI']);
                $path = $url['path'];
                // Display navigation menu items based on URL path
                if (strpos($path, '/product/') !== false) {
                    echo '<li class="nav-item "><a class="nav-link" href="/" style="text-decoration: underline;">Home</li> / <li class="nav-item active">Payment</a>';
                } else {
                    echo '<li class="nav-item active"><a class="nav-link" href="/" style="text-decoration: underline;">Home</a></li>';
                }
                ?>
            </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>


    @yield('scripts')
</body>
</html>
