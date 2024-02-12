import sys
import xml.etree.ElementTree as ET

# Direct errors to standard error
sys.stderr = sys.stdout

# Define a dictionary of functions and their expected argument types
function_list = {
    "MOVE": ["var", "symb"],
    "CREATEFRAME": [],
    "PUSHFRAME": [],
    "POPFRAME": [],
    "DEFVAR": ["var"],
    "CALL": ["label"],
    "RETURN": [],
    "PUSHS": ["symb"],
    "POPS": ["var"],
    "ADD": ["var", "symb", "symb"],
    "SUB": ["var", "symb", "symb"],
    "MUL": ["var", "symb", "symb"],
    "IDIV": ["var", "symb", "symb"],
    "LT": ["var", "symb", "symb"],
    "GT": ["var", "symb", "symb"],
    "EQ": ["var", "symb", "symb"],
    "AND": ["var", "symb", "symb"],
    "OR": ["var", "symb", "symb"],
    "NOT": ["var", "symb", "symb"],
    "INT2CHAR":["var", "symb"],
    "STRI2INT": ["var", "symb", "symb"],
    "READ": ["var", "type"],
    "WRITE": ["symb"],
    "CONCAT": ["var", "symb", "symb"],
    "STRLEN": ["var", "symb"],
    "GETCHAR": ["var", "symb", "symb"],
    "SETCHAR": ["var", "symb", "symb"],
    "TYPE": ["var", "symb"],
    "LABEL": ["label"],
    "JUMP": ["label"],
    "JUMPIFEQ": ["label", "symb", "symb"],
    "JUMPIFNEQ": ["label", "symb", "symb"],
    "EXIT": ["symb"],
    "DPRINT": ["symb"],
    "BREAK": []
}

# Initialize XML structure
root = ET.Element("program")
root.set('language', 'IPPcode24')

# Placeholder for order numbering
order = 1

# Read input lines from STDIN
for line in sys.stdin:
    # Remove comments and strip leading/trailing whitespace
    line = line.split('#')[0].strip()
    if line == "":
        continue  # Skip empty lines
    
    # Split line into words to analyze instruction and arguments
    parts = line.split()
    instruction_name = parts[0].upper()  # Normalize instruction name
    args = parts[1:]
    
    # Check if instruction is valid
    if instruction_name not in function_list:
        print(f"Error: Unknown instruction '{instruction_name}'", file=sys.stderr)
        sys.exit(22)  # Example error code for unknown instruction
    
    # Create XML element for instruction
    instruction_element = ET.SubElement(root, "instruction", order=str(order), opcode=instruction_name)
    order += 1  # Increment order for next instruction
    
    # Validate and add arguments to instruction element...
    # This part needs to be implemented based on the full function list and argument types
    
# Finalize XML and print to STDOUT
tree = ET.ElementTree(root)
tree.write(sys.stdout, encoding='unicode', method='xml')
