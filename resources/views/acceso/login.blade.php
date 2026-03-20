<html>
    <body>
        <center><h1>Iniciar Sesion </h1>
        <br>
        <form action ="{{route('validar')}}" method="post">
            {{csrf_field()}}
        <table border= 1>
        <tr><td>
            Teclea correo
        </td>
        <td>
            <input type = 'text' name='correo'></td>
        </tr>
        <tr>
            <td>
                Teclea password
            </td>
            <td>
                <input type ='text' name=" password">
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <input type = "submit" value = 'Iniciar'> 
            </td>
        </tr></table></form>
    @if (Session::has('mensaje'))
          <div>
        <div class="alert alert-dismissible alert-success">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
         {{ Session::get('mensaje') }}
        </div>
     </div>
@endif
    
    </center>
    </body>
</html>