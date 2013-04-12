from Tkinter import Tk
from tkFileDialog import askopenfilename
from lhires_fits_to_db import insert_lhires_into_db

Tk().withdraw() # suppress GUI
filename = askopenfilename() # show prompt to select a file name
insert_lhires_into_db(filename) # insert file into db