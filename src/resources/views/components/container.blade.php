<?php
    $path = resource_path('views'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'container'.DIRECTORY_SEPARATOR."*.blade.php");
?>
@foreach(glob($path) as $file)
    <?php
        $arquivo = str_replace(
            DIRECTORY_SEPARATOR,'.', str_replace(
                '.blade.php','', str_replace(
                    resource_path('views'.DIRECTORY_SEPARATOR),'',$file
                )
            )
        );
    ?>
    @include($arquivo)
@endforeach
