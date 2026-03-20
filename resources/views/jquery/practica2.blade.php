<html>
    <head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
    <script type="text/javascript">
        $(document).ready(function(){
                $("#ide").click(function() {
    $("#mostrardatos").load('{{url('datos')}}'+'?ide='+this.options[this.selectedIndex].value) ;
    });
        });

    </script>
        <form>
            selecciona empleado<select name = 'ide' id="ide">
                @foreach ($empleados as $e)
                <option value="{{ $e->idemp }}">{{ $e->nombre }}</option>
                @endforeach
            </select>
            <br>
            <div id="mostrardatos">
            RFC <input type="text" name="rfc" id="rfc">
            </div>
        </form>
    </body>
</html>