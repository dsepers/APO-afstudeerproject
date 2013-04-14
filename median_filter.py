# median filter for a fits file
# TODO:
# - get data from fits file, run median3 over it and update the data from the fits file
from numpy import *
import pyfits
from Tkinter import Tk
from tkFileDialog import askopenfilename

# assumes 2d array with numberic data
def median3(data):
  # create new array to store values
  newarray = ndarray((len(data),len(data[0])))
  # loop over each entry
  a_max = len(data) - 1
  for a in range(len(data)):
    b_max = len(data[0]) - 1
    for b in range(len(data[0])):
      list = []
      # add entry and all neighbours to a list
      list.append(data[a][b])
      if a > 0:
        if b > 0:
          list.append(data[a-1][b-1])
        if b < b_max:
          list.append(data[a-1][b+1])
        list.append(data[a-1][b])
      if a < a_max:
        if b > 0:
          list.append(data[a+1][b-1])
        if b < b_max:
          list.append(data[a+1][b+1])
        list.append(data[a+1][b])
      if b > 0:
        list.append(data[a][b-1])
      if b < b_max:
        list.append(data[a][b+1])
      
      # sort list
      list.sort()
      # get middle entry from list
      mid = list[len(list)/2 -1]
      newarray[a][b] = mid
  return newarray

def run_filter(filename):
  file = pyfits.open(filename)
  data = file[0].data
  print data
  newdata = median3(data)
  print newdata
  file[0].data = newdata
  file.writeto("new.fit")
  
if __name__ == '__main__':
  Tk().withdraw()
  filename = askopenfilename()
  run_filter(filename)
