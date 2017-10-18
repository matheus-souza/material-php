<form method="post">
    <input type="text" name="busca">
    <button type="submit">Enviar</button>
</form>

<?php

if (isset($_POST['busca'])) {
    echo strip_tags($_POST['busca']);
    echo '<br/>';
    echo htmlentities($_POST['busca']);
}



?>