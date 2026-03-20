<html>
    <head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#calcula").click(function(){
                $("#mostrar").load('{{url('calculo')}}' + '?' + $(this).closest('form').serialize()) ;
            });
        });
    </script>
    
        <form>
            Base <input type = 'text' name="base" id = 'base'>
            <br>
            Altura <input type = 'text' name= 'altura' id = 'altura'>
            <br>
            <input type = 'button' id="calcula">
        </form>
        <div id="mostrar">  </div>
    </body> 
</html>