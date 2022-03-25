<!DOCTYPE html>
<html>
<body>

<form action="{{ route('teste.store') }}" method="post">
    {{ csrf_field() }}
    <input type="text" name="carro" style="width: 300px; height: 50px; font-size: 25px" placeholder="Digite o modelo do carro"/>
    <button style="height: 50px; width: 100px" type="submit">BUSCAR</button>
</form>

</body>
</html>
