<?php
session_start();

// Permitir acceso si el usuario está autenticado o pendiente de pago
if (
    !isset($_SESSION['verificado']) &&
    !isset($_SESSION['usuario_pendiente'])
) {
    header("Location: login.php"); 
    exit;
}

// Si el usuario está pendiente, usar ese ID
if (isset($_SESSION['usuario_pendiente'])) {
    $userId = $_SESSION['usuario_pendiente'];
} elseif (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    die("Error: No hay sesión de usuario activa");
}

// Validar que userId es un número válido
if (!$userId || !is_numeric($userId) || $userId <= 0) {
    die("Error: ID de usuario inválido: " . htmlspecialchars($userId));
}

$precio = 10.00;
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/colores_globales.css">
    <link rel="icon" type="image/x-icon" href="../img/WhatsApp_Image_2025-11-29_at_00.05.29-removebg-preview.ico">
    <title>Suscripción Premium - Colmado Gamer</title>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #192443 0%, #2a3a5e 100%);
            padding: 20px;
        }

        .suscripcion-container {
            max-width: 500px;
            width: 100%;
        }

        .suscripcion-card {
            background: linear-gradient(to bottom, #2a3a5e, #1a2a4d);
            border: 3px solid #e7a923;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            padding: 40px;
            text-align: center;
            animation: fadeIn 0.6s ease-in;
        }

        .plan-icon {
            font-size: 50px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .plan-title {
            color: #e7a923;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .plan-description {
            color: #999;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .plan-price {
            font-size: 48px;
            font-weight: 700;
            color: #bb1818;
            margin: 20px 0;
        }

        .plan-price-info {
            color: #999;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .benefits-list {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
            text-align: left;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
        }

        .benefits-list li {
            color: #e7a923;
            margin: 10px 0;
            font-size: 14px;
        }

        .benefits-list li:before {
            content: "✓ ";
            font-weight: bold;
            margin-right: 8px;
            color: #fe952d;
        }

        #paypal-button-container {
            margin: 30px 0;
        }

        .paypal-info {
            background-color: rgba(230, 169, 35, 0.1);
            border-left: 4px solid #e7a923;
            padding: 15px;
            border-radius: 8px;
            font-size: 13px;
            color: #ddd;
            margin-top: 20px;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading.active {
            display: block;
        }

        .loading-spinner {
            border: 3px solid rgba(230, 169, 35, 0.3);
            border-top: 3px solid #e7a923;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #e7a923;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            color: #fe952d;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .suscripcion-card {
                padding: 30px 20px;
            }

            .plan-price {
                font-size: 36px;
            }

            .plan-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="suscripcion-container">
        <div class="suscripcion-card">
            <div class="plan-icon">🎮</div>
            
            <h1 class="plan-title">Plan Premium</h1>
            <p class="plan-description">Acceso ilimitado a toda nuestra biblioteca de videojuegos</p>

            <div class="plan-price">$<?= number_format($precio, 2) ?></div>
            <div class="plan-price-info">por mes</div>

            <ul class="benefits-list">
                <li>Acceso a todos los juegos</li>
                <li>Descargas ilimitadas</li>
                <li>Actualizaciones automáticas</li>
                <li>Soporte prioritario</li>
                <li>Cancelación en cualquier momento</li>
            </ul>

            <div class="loading" id="loading">
                <div class="loading-spinner"></div>
                <p>Procesando tu pago...</p>
            </div>

            <div id="paypal-button-container"></div>

            <div class="paypal-info">
                <strong>Información importante:</strong><br>
                Se te redirigirá a PayPal de forma segura para completar tu pago. Tu información personal está protegida.
            </div>

            <div class="back-link">
                <a href="home.php">← Volver a la tienda</a>
            </div>
        </div>
    </div>

    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=AdEwrrrKqCwI0E_Pb9mKbwJkH5VELvUatThFVmkfyKx1loqvEwSLYOMEcaQixS7gXpH09uUT7I600G6X&currency=USD"></script>

    <script>
        const loading = document.getElementById('loading');

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{ 
                        amount: { 
                            value: "<?= $precio ?>" 
                        } 
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    loading.classList.add('active');

                    const payload = {
                        orderID: data.orderID,
                        transactionID: details.id,
                        userID: <?= intval($userId) ?>,
                        monto: <?= floatval($precio) ?>
                    };
                    
                    console.log("Enviando pago:", payload);
                    
                    return fetch("../controladores/paypal_controlador.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(payload)
                    })
                    .then(r => r.json())
                    .then(resp => {
                        console.log("Respuesta del servidor:", resp);
                        loading.classList.remove('active');
                        
                        if (resp.status === "ok") {
                            alert("✅ ¡Suscripción completada exitosamente!");
                            window.location = "home.php";
                        } else {
                            alert("❌ Error en la suscripción:\n" + (resp.message || "Error desconocido") + 
                                  (resp.debug ? "\n\nDebug: " + JSON.stringify(resp.debug) : ""));
                        }
                    })
                    .catch(err => {
                        console.error("Error fetch:", err);
                        loading.classList.remove('active');
                        alert("❌ Error al conectar con el servidor: " + err.message);
                    });
                });
            },
            onError: function(err) { 
                console.error("Error PayPal:", err); 
                alert("❌ Error con PayPal: " + err.message);
                loading.classList.remove('active');
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
