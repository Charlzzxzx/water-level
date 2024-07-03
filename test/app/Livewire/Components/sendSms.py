import subprocess
import time
import serial.tools.list_ports
import psutil

def kill_receiver():
    for proc in psutil.process_iter(['pid', 'name']):
        if "python" in proc.info['name']:
            for item in proc.cmdline():
                if "pySql.py" in item:
                    print(f"Terminating process {proc.pid}: {proc.info['name']}")
                    proc.terminate()
                    time.sleep(3)  # Wait for 5 seconds

                    print("done killing process")
                    send_sms()

                    subprocess.Popen(["cmd.exe", "/C", "start", "python", "pySql.py"])  # Start in a new window
                    return

def find_serial_port():
    ports = serial.tools.list_ports.comports()

    # Iterate through the list of ports
    for port in ports:
        # Check if the port contains 'COM' in its description
        if 'COM' in port.description:
            return port.device

    # If no COM port found, return None
    return None

def send_sms():
    serial_port = find_serial_port()  # Change this to the appropriate port
    phone_number = '+639535696660'    # Change this to the recipient's phone number
    message = 'messagePush'  # Your message here

    ser = serial.Serial(serial_port, baudrate=9600, timeout=1)

    time.sleep(1)

    ser.write(b'AT\r\n')
    response = ser.read(100)
    print("Response:", response.decode())

    ser.write(b'AT+CMGF=1\r\n')
    time.sleep(1)
    response = ser.read(100)
    print("Response:", response.decode())

    ser.write(f'AT+CMGS="{phone_number}"\r\n'.encode())
    time.sleep(1)
    response = ser.read(100)
    print("Response:", response.decode())

    ser.write(message.encode() + b"\r\n")
    time.sleep(1)
    ser.write(bytes([26]))  # CTRL+Z to indicate the end of the message
    time.sleep(1)
    response = ser.readlines()
    print("Response:", response)

    ser.close()



if __name__ == "__main__":
    kill_receiver()
