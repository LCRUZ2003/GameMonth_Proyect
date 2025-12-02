<!doctype html>
<html lang="en">
    <head>
        <?php
            include '../controladores/home_controlador.php';
            session_start();

            if (!isset($_SESSION['verificado'])) {
                echo '<script>
                        alert("Debes iniciar sesión para acceder a esta página.");
                        window.location = "vistas/login.php";
                      </script>';
                exit();
            }
        ?>
        <link rel="icon" type="image/x-icon" href="../img/WhatsApp_Image_2025-11-29_at_00.05.29-removebg-preview.ico">
        <title>Colmado Gamer</title>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="css/colores_globales.css">
    </head>

    <body>
        <?php
        include 'header.php';
        ?>
        <!-- <header>
            Nav tabs
            <ul
                class="nav nav-tabs"
                id="navId"
                role="tablist"
            >
                <li class="nav-item">
                    <a
                        href="#tab1Id"
                        class="nav-link active"
                        data-bs-toggle="tab"
                        aria-current="page"
                        >Active</a
                    >
                </li>
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle"
                        data-bs-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        >Dropdown</a
                    >
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#tab2Id">Action</a>
                        <a class="dropdown-item" href="#tab3Id">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#tab4Id">Action</a>
                    </div>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tab5Id" class="nav-link" data-bs-toggle="tab"
                        >Another link</a
                    >
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#" class="nav-link disabled" data-bs-toggle="tab"
                        >Disabled</a
                    >
                </li>
            </ul>

            Cuadros
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab1Id" role="tabpanel">
                    
                </div>
                <div class="tab-pane fade" id="tab2Id" role="tabpanel"></div>
                <div class="tab-pane fade" id="tab3Id" role="tabpanel"></div>
                <div class="tab-pane fade" id="tab4Id" role="tabpanel"></div>
                <div class="tab-pane fade" id="tab5Id" role="tabpanel"></div>
            </div>
            
            (Optional) - Place this js code after initializing bootstrap.min.js or bootstrap.bundle.min.js
            <script>
                var triggerEl = document.querySelector("#navId a");
                bootstrap.Tab.getInstance(triggerEl).show(); // Select tab by name
            </script>
        </header> -->


        <main>            
            <div class="container-fluid py-5">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 justify-content-center">
                        <?php
                        $control = new HomeControlador();
                        $juegos = $control->mostrarDatos();
                        foreach ($juegos as $juego) {
                            $id = intval($juego['id']);
                            $portada = htmlspecialchars($juego['portada'], ENT_QUOTES, 'UTF-8');
                            $portada = '../img/' . $portada;  // Agregar ruta de imagen
                            $titulo = htmlspecialchars($juego['titulo'], ENT_QUOTES, 'UTF-8');
                            $cal = intval($juego['calificacion']);
                            $stars = str_repeat('★', $cal) . str_repeat('☆', max(0, 5 - $cal));

                            echo '<div class="col">
                                <a href="viewgame.php?id=' . $id . '" class="d-block text-decoration-none text-reset h-100">
                                    <div class="game-card h-100">
                                        <div class="game-card-image">
                                            <img src="' . $portada . '" alt="' . $titulo . '">
                                        </div>
                                        <div class="game-card-body">
                                            <h5 class="game-title">' . $titulo . '</h5>
                                            <div class="game-rating">' . $stars . '</div>
                                            <div class="game-footer">
                                                <span class="game-price">$' . $juego["estado"] . '</span>
                                                <img class="cart-icon" width="24" height="24" src="https://img.icons8.com/sf-regular-filled/48/add-shopping-cart.png" alt="add-shopping-cart" />
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>';
                        }   
                        ?>
                        </div>
                    </div>
                </div>
            </div>

        </main>


        <footer>
        </footer>

        <style>
        .game-card {
            background: linear-gradient(135deg, #3a4a6e 0%, #2d3e5f 100%);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            border: 1px solid rgba(231, 169, 35, 0.3);
            display: flex;
            flex-direction: column;
        }

        .game-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .game-card-image {
            width: 100%;
            height: 150px;
            overflow: hidden;
            border-radius: 15px 15px 0 0;
        }

        .game-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .game-card:hover .game-card-image img {
            transform: scale(1.05);
        }

        .game-card-body {
            padding: 12px 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .game-title {
            font-size: 13px;
            font-weight: bold;
            color: #ffffff;
            margin: 0 0 8px 0;
            line-height: 1.3;
            white-space: normal;
        }

        .game-rating {
            font-size: 12px;
            color: #e7a923;
            margin-bottom: 10px;
        }

        .game-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px solid rgba(231, 169, 35, 0.2);
        }

        .game-price {
            font-weight: bold;
            color: #fe952d;
            font-size: 14px;
        }

        .cart-icon {
            filter: invert(45%) sepia(75%) saturate(2500%) hue-rotate(4deg) brightness(105%) contrast(110%);
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .cart-icon:hover {
            transform: scale(1.15);
        }

        @media (max-width: 576px) {
            .game-card-image {
                height: 120px;
            }
            .game-title {
                font-size: 12px;
            }
        }
        </style>


        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
