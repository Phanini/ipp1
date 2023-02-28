<?php
    # Path warnings and errors
    ini_set('display_errors', 'STDERR');
    $header = false;

    # Console parameters handling
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

    $counter=0;
    while ($line = fgets(STDIN)) {

        # Seperate by whitespaces and trim \n
        $segments = explode(" ", trim($line, " \n"));

        # Check header and add XML header
        if ($header == false) {
            if ($segments[0] == ".IPPcode23") {
                echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
                echo "<program language=\"IPPcode23\">\n";
                $header = true;
            }
        }

        # Switch case for operations
        switch (strtoupper($segments[0])) {
            # Functions without arguments
            case 'CREATEFRAME':
            case 'PUSHFRAME':
            case 'POPFRAME':
            case 'RETURN':
            case 'BREAK':
                instruction($segments[0], $counter);
                echo "    </instruction>\n";
                break;
            #Functions with 1 argument
            case 'DEFVAR':
            case 'CALL':
            case 'PUSHS':
            case 'POPS':
            case 'WRITE':
            case 'LABEL':
            case 'JUMP':
            case 'EXIT':
            case 'DPRINT':
                break;
            default:
                $counter=0;
                break;
        }
        $counter++;
    }
    echo "</program>";

    # Function printing instruction XML code
    function instruction($opcode, $counter) {
        echo "    <instruction order=\"" . $counter . "\" opcode=\"" . $opcode . "\">\n"; 
    }
?>