<?php
function redirect($url) {
    // Verifica se o cabeçalho já foi enviado
    if (!headers_sent()) {
        // Se o cabeçalho não foi enviado, use a função de redirecionamento do PHP
        header("Location: " . $url);
        exit();
    } else {
        // Se o cabeçalho já foi enviado, use a função de redirecionamento do JavaScript
        echo '<script type="text/javascript">';
        echo 'window.location.href="' . $url . '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
        echo '</noscript>';
        exit();
    }
}
?>
