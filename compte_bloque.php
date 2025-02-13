<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Bloqué - BookMe</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f4f4f4;
        }

        .blocked-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .blocked-message {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .blocked-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }

        h1 {
            color: #333;
            margin-bottom: 1rem;
        }

        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .logout-btn {
            background: #9d56a1;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background: rgb(141, 56, 146);
        }

        @media (max-width: 480px) {
            .blocked-message {
                padding: 1.5rem;
            }

            .blocked-icon {
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>
    <div class="blocked-container">
        <div class="blocked-message">
            <i class="fas fa-ban blocked-icon"></i>
            <h1>Compte Bloqué</h1>
            <p>Votre compte a été bloqué par l'administrateur. Pour plus d'informations ou pour réactiver votre compte, veuillez contacter l'administrateur.</p>
            <a href="deconnexion.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Se déconnecter
            </a>
        </div>
    </div>
</body>
</html>