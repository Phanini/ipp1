<?php
    
    ini_set('display_errors', 'STDERR');    #path warnings and errors to stderr
    $header = false;
    /* Zpracovani parametru skriptu */
    if ($argc > 1) {
        if ($argv[1] == "--help") {
            if ($argc == 2) {
                echo "Use: parse.php [options] < input\n";
                exit;
            }
            echo "Error 10: can't combine --help with other parameters\n";
            exit(10);
        }
    }
    while ($line = fgets(STDIN)) {

        $segments = explode(" ", trim($line, "\n")); # seperate by whitespaces and trim \n
        $counter = count($segments);
        # echo $segments[0] . " header=" . (int)$header . "\n";
        
        if ($header == false) {
            if ($segments[0] == ".IPPcode23") {
                $header = true;
            }
        }

        switch (strtoupper($segments[0])) {
            case 'CREATEFRAME':
            case 'PUSHFRAME':
            case 'POPFRAME':
            case 'RETURN':
            case 'BREAK':
                break;
        }
    }
?>