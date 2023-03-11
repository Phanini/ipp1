<?php
    # Path warnings and errors
    ini_set('display_errors', 'STDERR');

    $function_list = array(
        'MOVE'          => array('var', 'symb'),
        'CREATEFRAME'   => array(),
        'PUSHFRAME'     => array(),
        'POPFRAME'      => array(),
        'DEFVAR'        => array('var'),
        'CALL'          => array('label'),
        'RETURN'        => array(),
        'PUSHS'         => array('symb'),
        'POPS'          => array('var'),
        'ADD'           => array('var', 'symb', 'symb'),
        'SUB'           => array('var', 'symb', 'symb'),
        'MUL'           => array('var', 'symb', 'symb'),
        'IDIV'          => array('var', 'symb', 'symb'),
        'LT'            => array('var', 'symb', 'symb'),
        'GT'            => array('var', 'symb', 'symb'),
        'EQ'            => array('var', 'symb', 'symb'),
        'AND'           => array('var', 'symb', 'symb'),
        'OR'            => array('var', 'symb', 'symb'),
        'NOT'           => array('var', 'symb'),
        'INT2CHAR'      => array('var', 'symb'),
        'STRI2INT'      => array('var', 'symb', 'symb'),
        'READ'          => array('var', 'type'),
        'WRITE'         => array('symb'),
        'CONCAT'        => array('var', 'symb', 'symb'),
        'STRLEN'        => array('var', 'symb'),
        'GETCHAR'       => array('var', 'symb', 'symb'),
        'SETCHAR'       => array('var', 'symb', 'symb'),
        'TYPE'          => array('var', 'symb'),
        'LABEL'         => array('label'),
        'JUMP'          => array('label'),
        'JUMPIFEQ'      => array('label', 'symb', 'symb'),
        'JUMPIFNEQ'     => array('label', 'symb', 'symb'),
        'EXIT'          => array('symb'),
        'DPRINT'        => array('symb'),
        'BREAK'         => array(),
    );
        

    function check_parameters() {
        global $argc, $argv;
        if ($argc > 1) {
            if ($argv[1] == "--help") {
                if ($argc == 2) {
                    echo "Use: parse.php [options] < input\n";
                    exit;
                }
                echo "Error 10: can't combine --help with other parameters\n";
                exit(10);
            }
            # TODO other help lines AND conflicts
        }
    }

    function check_header() {
        # Iterates through all blank lines
        while (($line=fgets(STDIN)) && preg_match('/^\s*$/', $line)) {}
        
        $header = explode(" ", trim($line, " \n"));
        if ($header[0] !== ".IPPcode23") {
            echo "Error 21: Missing header or code before header\n";
            exit(21);
        }
    }

    $fnc_counter=0;
    check_parameters();
    check_header();

    while ($line = fgets(STDIN)) {
        # Skip empty lines
        if (!empty($line)) {
            $fnc_counter++;
            # Remove comments from line
            preg_replace('/#.*$/', '', $line);
            echo $line."\n";

            # Divide line by whitespace and trim newlines
            $segments = explode(" ", trim($line, " \n"));

            $function = strtoupper($segments[0]);

            # Check if function is valid and exists
            if (!key_exists($function, $function_list)) {
                echo "Error 22: Unknown function\n";
                exit(22);
            }

            # Check if function has correct number of parameters
            if (count($segments)-1 !== count($function_list[$function])) {
                echo "Error 23: Wrong number of parameters given to function ". $function . "\n";
                exit(23);
            }
        }
    }
?>