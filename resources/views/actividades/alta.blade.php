@extends('principal')

@section('contenido')
<script type="text/javascript">
$(document).ready(function(){
    $("#idemp").click(function() {
    $("#infoempleado").load('{{url('datosemp')}}'+'?idemp='+this.options[this.selectedIndex].value) ;
    });
    $("#idta").click(function(){
        $("#idt").load('{{url('datosacti')}}'+'?idta='+this.options[this.selectedIndex].value) ;
            $("#infocorrectivo").load('{{url('infocorrectivo')}}'+'?idta='+this.options[this.selectedIndex].value) ;

    });
    $("#idt").click(function(){
        $("#infotarea").load('{{url('infotar')}}'+'?idta='+this.options[this.selectedIndex].value) ;
    });
    $("#agregar").click(function() {
       $("#carrito").load('{{url('cargacarrito')}}' + '?' + $(this).closest('form').serialize()) ;	 
	});
    $("#horast").keyup(function(){
        var costo= $ ("#costoxh").val();
        var ht = $ ("#horast").val();
        var total = costo*ht;
        $("#total").val(total);
    });
});    
</script>

<h1>Alta de actividades</h1>
<br>
<form>
<table border=1>
    <tr><td>Numero de actividad</td>
        <td><input type='text' id = 'ida' name = 'ida'value= '{{$sigue}}' readonly></td></tr>
    <tr><td>Fecha</td>
        <td><input type='date' id = 'fecha' name = 'fecha'></td></tr>
    <tr><td>Registra:</td>
        <td>
        <input type='hidden' id = 'idu' name = 'idu' value = '{{$idu}}'>
        <input type= 'text' id= 'gerente' name= 'gerente' value = '{{$nombregerente}}'>
    <tr><td>Selecciona empleado:</td>
        <td><select name = 'idemp' id="idemp">
            @foreach ($empleados as $e)
            <option value= '{{$e->idemp}}'>{{$e->nombre}}</option>
            @endforeach
            </select>
        </td></tr>
    <tr><td colspan=2>
        <div id= 'infoempleado'></div>
    </td></tr>    
    <tr><td>Tipo de actividad</td>
        <td><select name = 'idta' id= 'idta'>
            <option value= '1'>Preventivo</option>
            <option value= '2'>Correctivo</option>
</select></td></tr>
<tr><td>Seleccione Actividad</td>
    <td><select name = 'idt' id = 'idt'>
</select></td></tr>
<tr><td colsap=2><div id='infotarea'></div></td></tr>
<tr><td colsap=2><div id='infocorrectivos'></div></td></tr>

  <tr><td>Horas trabajadas</td>
        <td><input type = 'text' id= 'horast' name = 'horast' ></td></tr>
    <tr><td>Detalle</td>
        <td><textarea id= 'detalle' name = 'detalle'></textarea></td></tr> 
    <tr><td>Total</td>
        <td><input type = 'text' id= 'total' name = 'total' readonly></td></tr>
<tr><td colspan=2>
    <input type = 'button' value= 'Agregar' id= 'agregar'>
</table>
</form>
<div id= 'carrito'></div>
@stop