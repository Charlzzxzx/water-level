import mysql.connector

def mysql_connect():
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="12345",
            database="water-level",
        )
        print('Connected!')
        return conn
    
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None