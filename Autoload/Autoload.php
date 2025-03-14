<?php
spl_autoload_register(function ($ClassSuperete) {
    $rutes = [
        __DIR__ . '/../Controllers/' . $ClassSuperete . '.php',
        __DIR__ . '/../Models/' . $ClassSuperete . '.php'
    ];
    foreach ($rutes as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});



