@foreach ($tareas as $t)
<option value = '{{ $t->idta }}'> {{ $t->nombre }}</option>
@endforeach
