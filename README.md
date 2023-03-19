# Documentation
First IPP project

author: Jakub Phan, xphanj00

# Introduction
Script parse.php recieves IPPcode23 from standard input and makes a syntactical and lexical check. Errorless code is then parsed into a XML representation and flushed into standard output.

# Usage
`parse.php [options] < input`

# Instructions
In `$instruction_list` instructions and correct argument types are stored in arrays behaving like dictionaries, giving us option to search them by keys:

`"MOVE" => array("var", "symb"),`

# Data types
In `$arg_types` all argument types are listed with all their possible data types:

`"symb" => array("int", "string", "bool", "nil", "var")`


# Patterns
Regular expressions are used with `PCRE` functions to check correct data types:

`"type" => "/^(int|bool|string|nil)$/"`

# Input/Output
Input is loaded line by line with `fgets()` and after successful lexical and syntactical check is writen using `XMLWriter()` class. 