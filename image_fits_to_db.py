# takes a .fit file as argument, extracts some data from the file and commits that data to a database, 
# also copies the file to a local map and saves the path to that file in the database
import mysql
from mysql.connector import errorcode
import pyfits
import sys
import shutil
import string
import os
from math import *

def insert_image_into_db(filename):
  try:
    try:
      file = pyfits.open(filename)
    except Exception as e:
      print "Error with opening the file:"
      print e
      raw_input("Press Enter to continue...")
      exit()
    # get info you want to put into database
    try:
      Size_X = file[0].header['NAXIS1']
      Size_Y = file[0].header['NAXIS2']
      Exposure_time = file[0].header['EXPTIME']
      Datetemp = file[0].header['DATE-OBS']
      JD = file[0].header['JD']
      Filter = file[0].header['FILTER']
      RA = file[0].header['CRVAL1'] # probably not correct
      DEC = file[0].header['CRVAL2'] # probably not correct
      PA = file[0].header['PA']
      Object = file[0].header['OBJECT']
      FWHM = file[0].header['FWHM']
      XBINNING = file[0].header['XBINNING']
      YBINNING = file[0].header['YBINNING']
      Seeing = abs(float(file[0].header['CDELT1']) * 3600 * float(FWHM))
      if Object == "":
        print "ERROR: Object name is not set"
        raw_input("Press Enter to continue...")
        file.close()
        exit()
    except Exception as e:
      print "Missing required keyword:"
      print e
      raw_input("Press Enter to continue...")
      file.close()
      exit()
    # close file
    file.close()
    con = None

    # change Date format to DATETIME format
    try:
      tmp = Datetemp.split('T')
      Date = "'" + tmp[0] + " " + tmp[1] + "'"
    except Exception as e:
      print "Error with date conversion:"
      print e
      raw_input("Press Enter to continue...")
      exit()
      
    # change Filter to VARCHAR format
    Filter = "'" + Filter + "'"
    
    # create directory for file
    datepart = Datetemp.split('T')[0]
    datepart = "'"+datepart+"'"
    if not os.path.exists(datepart):
      os.mkdir("C:/xampp/htdocs/apo/images/"+datepart)
    if not os.path.exists(datepart + "/" + Object):
      os.mkdir("C:/xampp/htdocs/apo/images/"+datepart + "/" + Object)

    # change Path to VARCHAR format
    Path = "'"+filename.split('/')[-1]+"'"
    a = Path.split(' ')
    Path = string.join(a,'_')
    
    Path = datepart + "/" + Object + "/" + Path

    try:
      # connect to database
      con = mysql.connector.connect(user='apouser',password='apo3141',host='localhost',database='apo')
      cur = con.cursor()
      # if table does not exist, create table
      cur.execute("CREATE TABLE IF NOT EXISTS Images(Id INT PRIMARY KEY AUTO_INCREMENT, Size_X INT, Size_Y INT, Exposure_time FLOAT, "
        +"Date TIMESTAMP, Filter VARCHAR(25), Path VARCHAR(512), RA FLOAT, DEC FLOAT, PA FLOAT, OBJECT VARCHAR(128), FWHM FLOAT, "
        +"XBINNING FLOAT, YBINNING FLOAT, SEEING FLOAT) ENGINE=InnoDB")
      # create query for the insert
      query = "INSERT INTO Images (Size_X,Size_Y,Exposure_time,Filter,Date,Path,RA,DEC,PA,OBJECT,FWHM,XBINNING,YBINNING,SEEING) VALUES (%i,%i,%f,%s,%s,%s,%f,%f,%f,%s,%f,%f,%f,%f)" % (int(Size_X),int(Size_Y),float(Exposure_time),Filter,Date,Path,float(RA),float(DEC),float(PA),OBJECT,float(FWHM),float(XBINNING),float(YBINNING),float(SEEING))
      cur.execute(query)
      # commit insert
      con.commit()
      cur.close()
      # copy file to image folder
      target = "C:\\xampp\\htdocs\\apo\\images\\"+filename.split('/')[-1] # we don't care about the file's original path here so strip it
      a = target.split(' ')
      target = string.join(a,'_')
      shutil.copy2(filename,target)

    # catch any errors
    except mysql.connector.Error as err:
      if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
        print("Something is wrong with your username or password")
      elif err.errno == errorcode.ER_BAD_DB_ERROR:
        print("Database does not exist")
      else:
        print(err)
      
    # close connection
    finally:
      if con:
        con.close()
  except Exception as e:
    print e
    raw_input("Press Enter to continue...")

def main():
  insert_image_into_db(sys.argv[1])

if __name__ == '__main__':
  main()
