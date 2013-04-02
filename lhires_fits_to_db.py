# takes a .fit file as argument, extracts some data from the file and commits that data to a database, 
# also copies the file to a local map and saves the path to that file in the database
import mysql
from mysql.connector import errorcode
import pyfits
import sys
import shutil

file = pyfits.open(sys.argv[1])
# get info you want to put into database
# the data selected here are some things I think are interesting to put into a database
Size_X = file[0].header['NAXIS1']
Size_Y = file[0].header['NAXIS2']
Exposure_time = file[0].header['EXPTIME']
Date = file[0].header['DATE-OBS']
Filter = file[0].header['FILTER']
# close file
file.close()
con = None

# change Date format to DATETIME format
tmp = Date.split('T')
Date = "'" + tmp[0] + " " + tmp[1] + "'"

# change Filter to VARCHAR format
Filter = "'" + Filter + "'"

# change Path to VARCHAR format
Path = "'"+"/LHIRES/"+sys.argv[1]+"'"

try:
  # connect to database
  con = mysql.connector.connect(user='apouser',password='apo3141',host='localhost',database='apo')
  cur = con.cursor()
  # if table does not exist, create table
  cur.execute("CREATE TABLE IF NOT EXISTS LHIRES(Id INT PRIMARY KEY AUTO_INCREMENT, Size_X INT, Size_Y INT, Exposure_time FLOAT, "
    +"Date TIMESTAMP, Filter VARCHAR(25), Path VARCHAR(512)) ENGINE=InnoDB")
  # create query for the insert
  query = "INSERT INTO LHIRES (Size_X,Size_Y,Exposure_time,Filter,Date,Path) VALUES (%i,%i,%f,%s,%s,%s)" % (int(Size_X),int(Size_Y),float(Exposure_time),Filter,Date,Path)
  cur.execute(query)
  # commit insert
  con.commit()
  cur.close()
  # copy file to image folder
  shutil.copy2(sys.argv[1],"LHIRES\\"+sys.argv[1])

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