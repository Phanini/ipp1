<?php
    # Path warnings and errors
    ini_set('display_errors', 'STDERR');
    global $xw;

    $function_list = array(
        'MOVE'          => array("var", "symb"),
        'CREATEFRAME'   => array(),
        'PUSHFRAME'     => array(),
        'POPFRAME'      => array(),
        'DEFVAR'        => array("var"),
        'CALL'          => array("label"),
        'RETURN'        => array(),
        'PUSHS'         => array("symb"),
        'POPS'          => array("var"),
        'ADD'           => array("var", "symb", "symb"),
        'SUB'           => array("var", "symb", "symb"),
        'MUL'           => array("var", "symb", "symb"),
        'IDIV'          => array("var", "symb", "symb"),
        'LT'            => array("var", "symb", "symb"),
        'GT'            => array("var", "symb", "symb"),
        'EQ'            => array("var", "symb", "symb"),
        'AND'           => array("var", "symb", "symb"),
        'OR'            => array("var", "symb", "symb"),
        'NOT'           => array("var", "symb"),
        'INT2CHAR'      => array("var", "symb"),
        'STRI2INT'      => array("var", "symb", "symb"),
        'READ'          => array("var", "type"),
        'WRITE'         => array("symb"),
        'CONCAT'        => array("var", "symb", "symb"),
        'STRLEN'        => array("var", "symb"),
        'GETCHAR'       => array("var", "symb", "symb"),
        'SETCHAR'       => array("var", "symb", "symb"),
        "type"          => array("var", "symb"),
        "label"         => array("label"),
        'JUMP'          => array("label"),
        'JUMPIFEQ'      => array("label", "symb", "symb"),
        'JUMPIFNEQ'     => array("label", "symb", "symb"),
        'EXIT'          => array("symb"),
        'DPRINT'        => array("symb"),
        'BREAK'         => array(),
    );

    $arg_types = array(
        "var"       => array("var"),
        "symb"      => array("int", "string", "bool", "nil", "var"),
        "label"     => array("label"),
        "type"      => array("type"),
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

    function init_xml() {
        global $xw;
        $xw = xmlwriter_open_memory();
        xmlwriter_set_indent($xw, 1);
        $res = xmlwriter_set_indent_string($xw, ' ');
        
        xmlwriter_start_document($xw, '1.0', 'UTF-8');

        xmlwriter_start_element($xw, 'program');
        xmlwriter_write_attribute($xw, 'language', 'IPPcode23');
    }

    function end_xml() {
        global $xw;
        xmlwriter_end_attribute($xw);
        xmlwriter_end_document($xw);
        echo xmlwriter_output_memory($xw);
    }

    $fnc_counter=0;
    
    check_parameters();
    check_header();
    init_xml();

    while ($line = fgets(STDIN)) {
        # Skip empty lines
        if (!empty($line)) {
            global $xw;
            $fnc_counter++;
            # Remove comments from line
            $line = preg_replace('/#.*/', '', $line);

            # Divide line by whitespace and trim newlines
            $segments = explode(" ", trim($line, " \n"));
            $function = strtoupper($segments[0]);
            $arg_num =  count($segments)-1;

            # Check if function is valid and exists
            if (!key_exists($function, $function_list)) {
                echo "Error 22: Unknown function\n";
                exit(22);
            }

            # Check if function has correct number of parameters
            if ($arg_num == 1){
                if ($arg_num!== count($function_list[$function])) {
                    echo "Error 23: Wrong number of parameters given to function ". $function . "\n";
                    exit(23);
                }
                xmlwriter_start_element($xw, "instruction");
                xmlwriter_write_attribute($xw, "order", $fnc_counter);
                xmlwriter_write_attribute($xw, "opcode", $function);    
            }
            else {
                xmlwriter_start_element($xw, "instruction");
                xmlwriter_write_attribute($xw, "order", $fnc_counter);
                xmlwriter_write_attribute($xw, "opcode", $function);
                # iterate parameters and check if correct type
                for ($i=1; $i <= $arg_num; $i++) {
                    /*if preg_match($regex[$function_list[$function[i]]], $segments[$i]) { # dodelat regularni vyrazy

                    }*/
                }
            }
            

                         
        }
    }
    end_xml();
?>