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

    $pattern_regex = array(
        "var"       => "/^(LF|GF|TF)@[\_\-\$\&\%\*\!\?a-zA-Z][\_\-\$\&\%\*\!\?a-zA-Z0-9]*$/",
        "int"       => "/^int@[+-]?\d*(\.\d*)?$/",
        "bool"      => "/^bool@(true|false)$/",
        "string"    => "/^string@([\\\\]\d{3}|[^\\\\\#\s])*$/",
        "nil"       => "/^nil@nil$/",
        "label"     => "/^[\_\$\-\*\?\!\%a-zA-Z][\_\$\-\*\?\!\%a-zA-Z0-9]*$/",
        "type"      => "/^(int|bool|string|nil)$/"
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

    # Checks if first code in file is header
    function check_header() {
        # Iterates through all blank lines
        while (($line=fgets(STDIN)) && preg_match('/^\s*$/', $line)) {}
        
        $header = explode(" ", trim($line, " \n"));
        if ($header[0] !== ".IPPcode23") {
            echo "Error 21: Missing header or code before header\n";
            exit(21);
        }
    }

    # Function that checks if symb is correct
    function check_symb_argument($checked_segment) {
        global $arg_types, $pattern_regex,$type_of_symb;
        foreach ($arg_types["symb"] as $type_of_symb) {
            if (preg_match($pattern_regex[$type_of_symb], $checked_segment)) {
                #echo "SUCCESS: ".$type_of_symb."\n";
                $type=$type_of_symb;
                return true;
            }
        }
        exit(22);
    }

    # Initialize xml document
    function init_xml() {
        global $xw;
        $xw = new XMLWriter();
        $xw->openMemory();
        $xw->startDocument("1.0", "UTF-8");
        $xw->setIndent(1);
        $xw->startElement("program");
        $xw->writeAttribute("language", "IPPCode23");
    }

    function end_xml() {
        global $xw;
        $xw->endElement();
        $xw->endDocument();
        echo $xw->flush();
    }

    $fnc_counter=0;
    $symb_type;
    check_parameters();
    check_header();
    init_xml();

    # Read file line by line
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

            # 
            if ($arg_num == count($function_list[$function])) {
            
                if ($arg_num == 0){
                    $xw->startElement("instruction");
                    $xw->writeAttribute("order", $fnc_counter);
                    $xw->writeAttribute("opcode", $function);
                }
                else {
                    $xw->startElement("instruction");
                    $xw->writeAttribute("order", $fnc_counter);
                    $xw->writeAttribute("opcode", $function);
                    # iterate parameters and check if correct type
                    $cnt=0;
                    foreach ($function_list[$function] as $fnc_args) {
                        $cnt++;
                        #echo $cnt . ": ".$fnc_args. " \"$segments[$cnt]\""."\n";
                        switch ($fnc_args) {
                            case "symb":
                                if (!check_symb_argument($segments[$cnt])) {
                                    echo "Error:\n";
                                }
                                $xw->startElement("arg" . $cnt);
                                $xw->writeAttribute("type", $type_of_symb);
                                $value = explode('@', $segments[$cnt])[1];
                                $xw->text($value);
                                $xw->endElement();
                                break;
                            case "var":
                            case "label":
                            case "type":
                                if (!preg_match($pattern_regex[$fnc_args], $segments[$cnt])) {
                                    echo "Error:\n";
                                }
                                $xw->startElement("arg" . $cnt);
                                $xw->writeAttribute("type", $fnc_args);
                                $xw->text($segments[$cnt]);
                                $xw->endElement();
                                break;
                        }
                    }
                    
                }
            }
            else {
                echo "Error 23: Wrong number of parameters given to function ". $function . "\n";
                exit(23);
            }
            $xw->endElement();    
        }
    }
    end_xml();
?>