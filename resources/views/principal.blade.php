<html>
    <head>
        <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet" />
        <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>

        <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" _msttexthash="381446" _msthash="72">Barra de navegación</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" _msthidden="A" _mstaria-label="320099" _msthash="73">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                <a class="nav-link active" href="#"><font _mstmutation="1" _msttexthash="59059" _msthash="74">Hogar </font><span class="visually-hidden" _msttexthash="97968" _msthash="75">(actual)</span>
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#" _msttexthash="136006" _msthash="76">Funciones</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#" _msttexthash="95589" _msthash="77">Precios</a>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" _msttexthash="300144" _msthash="79">Catálogos</a>
                <div class="dropdown-menu" _msthidden="4">
                    <a class="dropdown-item" href="{{route('altaempleado')}}" _msttexthash="76466" _msthidden="1" _msthash="80">Alta</a>
                    <a class="dropdown-item" href="#" _msttexthash="232752" _msthidden="1" _msthash="81">Nuevo</a>
                    <a class="dropdown-item" href="{{route('reporteempleados')}}" _msttexthash="349791" _msthidden="1" _msthash="82">Reporte</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" _msttexthash="230529" _msthidden="1" _msthash="83">Otro</a>
                </div>
                </li>


                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" _msttexthash="300144" _msthash="79">Actividades</a>
                <div class="dropdown-menu" _msthidden="4">
                    <a class="dropdown-item" href="{{route('altaactividad')}}" _msttexthash="76466" _msthidden="1" _msthash="80">Alta</a>
                                        <div class="dropdown-divider"></div>

                </div>
                </li>


                <li class="nav-item">
                <a class="nav-link" href="{{ route('cerrarsesion') }}" _msttexthash="107419" _msthash="78">Cerrar sesión</a>
                </li>
            </ul>
            
            </div>
        </div>
        </nav>
        @yield('contenido')
</body>
</html>