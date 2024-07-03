import serial
import time
import conn
import mysql.connector
from datetime import datetime

# Replace with your GSM module's serial port
ser = serial.Serial("COM3", 9600)  # Adjust for your port and baud rate

######################################### Connection for database#############################


def storeDatabase(water_level, locationID, status):
    if locationID != "":
        db_conn = conn.mysql_connect()
        cursor = db_conn.cursor()

        insert_data = "INSERT INTO `readings` (water_level, locationID, timestamp, status) VALUES (%s, %s, NOW(), %s)"
        try:
            cursor.execute(insert_data, (water_level, locationID, status))

            db_conn.commit()
            print("Insert into readings table successfully")

        except mysql.connector.Error as err:
            print(f"Error Store: {err}")

        finally:
            cursor.close()
            db_conn.close()
    else:
        print("empty locationID")


def retrieveSettingsID(location):
    db_conn = conn.mysql_connect()
    cursor = db_conn.cursor(dictionary=True)
    settingsID = 0

    # SQL query to retrieve data from locations table
    retrieve_data = "SELECT * FROM `settings` WHERE locationID=%s"

    try:
        cursor.execute(retrieve_data, (location.lower(),))

        # Fetch all records from the query
        locations = cursor.fetchall()

        # Print out each record
        for l in locations:
            settingsID = l["settingsID"]
            break

    except mysql.connector.Error as err:
        print(f"Error Retrieve: {err}")

    finally:
        cursor.close()
        db_conn.close()
        return settingsID


def retrieveLocationID(location):
    db_conn = conn.mysql_connect()
    cursor = db_conn.cursor(dictionary=True)
    locationID = ""

    # SQL query to retrieve data from locations table
    retrieve_data = "SELECT * FROM `location` WHERE locationName=%s"

    try:
        cursor.execute(retrieve_data, (location.lower(),))

        # Fetch all records from the query
        locations = cursor.fetchall()

        # Print out each record
        for l in locations:
            locationID = l["locationID"]
            break

    except mysql.connector.Error as err:
        print(f"Error Retrieve: {err}")

    finally:
        cursor.close()
        db_conn.close()
        return locationID


def throw_data_to_db(waterLevel, status, location, latitude, longitude, normal, base, low, high):
    print("Storing ...")
    locationID = retrieveLocationID(location)
    if locationID == "":
        time.sleep(1)
        isSuccess = createLocationID(location, latitude, longitude)

        if isSuccess:
            locationID = retrieveLocationID(location)
    updateSettings(locationID, normal, base, low, high)
    time.sleep(1)
    storeDatabase(waterLevel, locationID, status)


def updateSettings(locationID, normal, base, low, high):
    print("Updating Settings ...")
    if locationID != "":

        settingsID = retrieveSettingsID(locationID)
        if (settingsID != 0):
            db_conn = conn.mysql_connect()
            cursor = db_conn.cursor()

            insert_data = "UPDATE `settings` set locationID=%s, normal=%s, base=%s, low=%s, high=%s WHERE settingsID=%s"
            try:
                cursor.execute(insert_data, (locationID,
                               normal, base, low, high, settingsID))

                db_conn.commit()
                print("Update into settings table successfully")

            except mysql.connector.Error as err:
                print(f"Error Store: {err}")

            finally:
                cursor.close()
                db_conn.close()
        else:
            db_conn = conn.mysql_connect()
            cursor = db_conn.cursor()

            insert_data = "INSERT INTO `settings` (locationID, normal, base, low, high, created_at) VALUES (%s, %s, %s, %s, %s, NOW())"
            try:
                cursor.execute(insert_data, (locationID,
                               normal, base, low, high))

                db_conn.commit()
                print("Insert into settings table successfully")

            except mysql.connector.Error as err:
                print(f"Error Store: {err}")

            finally:
                cursor.close()
                db_conn.close()
    else:
        print("updateSettings()::empty locationID")


def createLocationID(location, latitude, longitude):
    print("Creating Location ID ...")
    isSuccess = False
    try:
        db_conn = conn.mysql_connect()
        cursor = db_conn.cursor()
        insert_data = "INSERT INTO `location` (locationName, latitude, longitude) VALUES (%s, %s, %s)"
        try:
            cursor.execute(
                insert_data, (location.lower(), latitude, longitude))
            db_conn.commit()
            isSuccess = True
        except mysql.connector.Error as err:
            print(f"Error: {err}")
        finally:
            cursor.close()
            db_conn.close()
    except mysql.connector.Error as conn_err:
        print(f"Connection Error: {conn_err}")
    return isSuccess


# ----------------------end--------------------- #


def send_at_command(command, timeout=1):
    """Sends an AT command and returns the response."""
    ser.write((command + "\r\n").encode())
    time.sleep(timeout)  # Wait for response
    response = ser.read_all().decode()
    return response


def delete_all_messages():
    """Deletes all messages from the GSM module."""
    send_at_command("AT+CMGD=1,4")  # Delete all messages


def process_messages(response, waterLevel, status, location, latitude, longitude, normal, base, low, high):
    """Processes the messages received in the response."""
    messages = response.split("+CMGL:")

    for msg in messages[1:]:
        # Extract message details using your extraction logic _ mata data remove
        message_start = msg.find("\n", msg.find('""')) + 1
        message_end = msg.find("\n", message_start)
        message = msg[message_start:message_end].strip()
        print("Received message (extracted):", message)

        # pattern of extracted message -> (meter of water)-(place) e.g. --> 293-lumbayao
        findDash = message.find("-")
        # waterLevel = message[:findDash]  # returns water level
        place = message[findDash + 1:]  # returns place

        if place.lower() != "8080" and place.lower() != "-1" and place.lower() != "+1":
            throw_data_to_db(waterLevel, status, location,
                             latitude, longitude, normal, base, low, high)
        else:
            # Gi himoag else para sa mga foreign numbers na mag send sa ani na SIM
            # example, mo send si 8080, ang place na variable mo return og -1 tas +1 so ang whole message ma send supposed to be ang intended ra na message
            pass

            print("Message content (passed):", message)

        # Delete the message from the module
        index = msg.split(",")[0].strip()
        send_at_command("AT+CMGD=" + index)


def receive_unread_messages():
    """Checks for and reads unread incoming messages."""
    location = ""
    latitude = 0.0
    longitude = 0.0
    waterLevel = 0.0
    status = ""
    normal = 0.0
    base = 0.0
    low = 0.0
    high = 0.0
    while True:
        response = send_at_command(
            'AT+CMGL="REC UNREAD"')  # Read unread messages

        # kas-a ra ni ma receive since para pang set rani
        # check if the message stored on response variable is for registration if not, proceed to process_message()
        if isRegisterMessage(response):

            # meta-date remove
            message_start = response.find("\n", response.find('""')) + 1
            message_end = response.find("\n", message_start)
            message = response[message_start:message_end].strip()

            # kasa ra ma receive ang -> lat, long, nromal, max, loc
            parts = message.split("-")
            location = parts[0]  #
            flag = parts[1]

            if flag == "B":
                waterLevel = parts[2]
                status = parts[3]

                process_messages(
                    response, waterLevel, status, location, latitude, longitude, normal, base, low, high
                )
            else:
                latitude = parts[2]  #
                longitude = parts[3]
                normal = parts[4]
                base = parts[5]
                low = parts[6]
                high = parts[7]

                print("location:", location)
                print("flag:", flag)
                print("Latitude:", latitude)
                print("longitude:", longitude)
                print("normal:", normal)
                print("base:", base)
                print("low:", low)
                print("high:", high)

            # print('Inserted to database succesfully')

            # diani ma save ang pag set sa normal na water level, long, lat, max, og location sa device.
            # connection sa database. Maset ni siya kas-a lang
            continue

        # Introduce a delay before checking for the next set of messages
        time.sleep(1)


def isRegisterMessage(msg):
    # this function checks if the received message is for regesteration purposes (green-2342.3-324.542-999) or typical water level
    # only returns true or false
    countDash = 0

    # meta date remove
    message_start = msg.find("\n", msg.find('""')) + 1
    message_end = msg.find("\n", message_start)
    message = msg[message_start:message_end].strip()
    if message == "":
        countDash == len(msg.split("-"))
    for i in message:
        if i == "-":
            countDash += 1
    if countDash == 7:
        return True
    if countDash == 8:
        return True
    return False


print("Waiting for incomming messages..")
send_at_command("AT")  # Check module response
send_at_command("AT+CMGF=1")  # Set text mode
receive_unread_messages()  # Start receiving unread messages
