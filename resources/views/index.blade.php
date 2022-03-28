<!DOCTYPE html>
<html>
<body>

<input type="text" name="carro" id="carro" style="width: 300px; height: 50px; font-size: 25px" placeholder="Digite o modelo do carro"/>
<button style="height: 50px; width: 100px" id="btnSubmit" type="button" onclick="clickButton()">BUSCAR</button>

<script>
    var input = document.getElementById("carro");
    input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("btnSubmit").click();
        }
    });
    function clickButton() {
        var carro = document.getElementById('carro').value;
        window.location.href = "/teste/"+carro;
    }
</script>
</body>
</html>
