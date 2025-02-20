<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'My Shop'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            display: none;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
            background-color: #2196F3;
            color: white;
            animation: fadeIn 0.5s ease-in-out, fadeOut 0.5s ease-in-out 2.5s;
            z-index: 9999;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="?page=home">My Shop</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="?page=shop">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="?page=cart">Cart</a></li>
                <li class="nav-item"><a class="nav-link" href="?page=my-orders">Order History</a></li>
            </ul>
        </div>
    </nav>

  
    <div class="container mt-4">
        <?php echo $content; ?>
    </div>

    <footer class="bg-dark text-white text-center p-3 mt-4">
        &copy; 2025 My Shop. All Rights Reserved.
    </footer>

</body>
<script>
    function showNotification() {
        let notification = document.getElementById("payment-notification");
        notification.style.display = "block";

        setTimeout(() => {
            notification.style.display = "none";
        }, 3000);
    }
    window.onload = showNotification;
</script>
</html>

