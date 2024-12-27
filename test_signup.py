# import imaplib
# import email
# import re
# import unittest
# import time
# from selenium import webdriver
# from selenium.webdriver.common.by import By
# from selenium.webdriver.support.ui import WebDriverWait
# from selenium.webdriver.support import expected_conditions as EC
# from selenium.common.exceptions import UnexpectedAlertPresentException, NoAlertPresentException

# def extract_otp_from_email(raw_email):
#     try:
#         # Parse the email message from bytes
#         msg = email.message_from_bytes(raw_email)
#         otp_code = None

#         # Check if the email is multipart
#         if msg.is_multipart():
#             # Loop through each part of the email
#             for part in msg.walk():
#                 content_type = part.get_content_type()
                
#                 if content_type == "text/html" or content_type == "text/plain":
#                     try:
#                         body = part.get_payload(decode=True).decode()
#                         otp_code_match = re.search(r"\b\d{6}\b", body)
#                         if otp_code_match:
#                             otp_code = otp_code_match.group()
#                             break  # Exit loop after finding the OTP
#                     except Exception as e:
#                         print(f"Error decoding email part: {e}")
#         else:
#             # If not multipart, handle it as a single part email
#             body = msg.get_payload(decode=True).decode()
#             otp_code_match = re.search(r"\b\d{6}\b", body)
#             if otp_code_match:
#                 otp_code = otp_code_match.group()

#         # Check if OTP was found
#         if otp_code:
#             return otp_code
#         else:
#             raise ValueError("OTP not found in the email content.")

#     except Exception as e:
#         print(f"Error processing email: {e}")
#         return None

# def get_otp_from_email():
#     # Connect to the Gmail server
#     mail = imaplib.IMAP4_SSL("imap.gmail.com")
    
#     # Login to your email account
#     mail.login("ganga99088@gmail.com", "ihyl apuo rgzk isdh")  # Replace with your Gmail credentials
    
#     # Select the inbox folder
#     mail.select("inbox")

#     # Search for emails from the specific sender's email address
#     result, data = mail.search(None, 'FROM', 'gopilakshmisetty29@gmail.com')
#     email_ids = data[0].split()
#     if not email_ids:
#         raise Exception("OTP not received")
    
#     # Fetch the latest email
#     latest_email_id = email_ids[-1]
#     result, message_data = mail.fetch(latest_email_id, "(RFC822)")
    
#     # Extract the OTP from the email content
#     raw_email = message_data[0][1]
#     otp_code = extract_otp_from_email(raw_email)
    
#     # Logout from the email server
#     mail.logout()
    
#     # Return the extracted OTP code
#     if otp_code:
#         return otp_code
#     else:
#         raise Exception("OTP not found in the latest email")

# class FinanceTrackerLoginTest(unittest.TestCase):

#     def setUp(self):
#         # Set up Chrome WebDriver
#         self.driver = webdriver.Chrome()  # Ensure ChromeDriver is in your PATH or specify the full path
#         self.driver.maximize_window()
#         self.driver.get("http://localhost/Finance_Tracker/signup.html")  # Updated URL

#     def test_login_with_otp(self):
#         driver = self.driver
#         # Enter email and password
#         driver.find_element(By.NAME, "username").send_keys("Ganga Thummaluru")  # Use your test username
#         driver.find_element(By.NAME, "email").send_keys("ganga99088@gmail.com")  # Use your test email
#         driver.find_element(By.NAME, "password").send_keys("Ganga@123")  # Use your test password
#         driver.find_element(By.XPATH, "//button[text()='Request OTP']").click()

#         # Wait for OTP email and fetch it
#         # time.sleep(10)  # Adjust the sleep time to allow email delivery (can use a loop for dynamic wait if needed)

#         try:
#             alert = driver.switch_to.alert
#             print(f"Alert text: {alert.text}")  # Print the alert text for debugging (optional)
#             alert.accept()  # Clicks "OK" on the alert
#             print("Alert accepted.")
#         except NoAlertPresentException:
#             print("No alert appeared after requesting OTP.")

#         otp_code = get_otp_from_email()
#         print(f"Retrieved OTP: {otp_code}")

#         # Enter the OTP code into the OTP field
#         otp_field = WebDriverWait(driver, 10).until(
#             EC.presence_of_element_located((By.NAME, "otp"))
#         )
#         otp_field.send_keys(otp_code)

#         # Complete the login by clicking the "Sign Up" or "Log In" button
#         driver.find_element(By.XPATH, "//button[text()='Sign Up']").click()
#         time.sleep(5)  # Wait to observe the result or for any redirects

#     def tearDown(self):
#         self.driver.quit()

# if __name__ == "__main__":
#     unittest.main()

import imaplib
import email
import re
import unittest
import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import UnexpectedAlertPresentException, NoAlertPresentException

# Helper function to extract OTP from email
def extract_otp_from_email(raw_email):
    try:
        msg = email.message_from_bytes(raw_email)
        otp_code = None

        if msg.is_multipart():
            for part in msg.walk():
                content_type = part.get_content_type()
                
                if content_type == "text/html" or content_type == "text/plain":
                    body = part.get_payload(decode=True).decode()
                    otp_code_match = re.search(r"\b\d{6}\b", body)
                    if otp_code_match:
                        otp_code = otp_code_match.group()
                        break
        else:
            body = msg.get_payload(decode=True).decode()
            otp_code_match = re.search(r"\b\d{6}\b", body)
            if otp_code_match:
                otp_code = otp_code_match.group()

        if otp_code:
            return otp_code
        else:
            raise ValueError("OTP not found in the email content.")

    except Exception as e:
        print(f"Error processing email: {e}")
        return None

# Function to get OTP from email
def get_otp_from_email():
    mail = imaplib.IMAP4_SSL("imap.gmail.com")
    mail.login("ganga99088@gmail.com", "ihyl apuo rgzk isdh")  # Replace with your Gmail credentials
    mail.select("inbox")
    result, data = mail.search(None, 'FROM', 'gopilakshmisetty29@gmail.com')
    email_ids = data[0].split()
    if not email_ids:
        raise Exception("OTP not received")
    
    latest_email_id = email_ids[-1]
    result, message_data = mail.fetch(latest_email_id, "(RFC822)")
    raw_email = message_data[0][1]
    otp_code = extract_otp_from_email(raw_email)
    
    mail.logout()
    
    if otp_code:
        return otp_code
    else:
        raise Exception("OTP not found in the latest email")

class FinanceTrackerLoginTest(unittest.TestCase):

    def setUp(self):
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        self.driver.get("http://localhost/Finance_Tracker/signup.html")  # Updated URL

    def test_login_with_otp(self):
        driver = self.driver
        driver.find_element(By.NAME, "username").send_keys("Ganga Thummaluru")
        driver.find_element(By.NAME, "email").send_keys("ganga99088@gmail.com")
        driver.find_element(By.NAME, "password").send_keys("Ganga@123")
        driver.find_element(By.XPATH, "//button[text()='Request OTP']").click()

        # Handle the alert that appears after requesting OTP
        try:
            WebDriverWait(driver, 10).until(EC.alert_is_present())  # Wait for alert to be present
            alert = driver.switch_to.alert
            print(f"Alert text: {alert.text}")  # Optional: Print alert text for debugging
            alert.accept()  # Accept the alert
            print("Alert accepted.")
        except NoAlertPresentException:
            print("No alert appeared after requesting OTP.")
        except UnexpectedAlertPresentException:
            print("Unexpected alert present. Handled it.")

        # Wait for the OTP email and fetch it
        otp_code = get_otp_from_email()
        print(f"Retrieved OTP: {otp_code}")

        # Enter the OTP code into the OTP field
        otp_field = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.NAME, "otp"))
        )
        otp_field.send_keys(otp_code)

        # Complete the signup by clicking the "Sign Up" button
        driver.find_element(By.XPATH, "//button[text()='Sign Up']").click()
        # time.sleep(5)

    def tearDown(self):
        self.driver.quit()

if __name__ == "__main__":
    unittest.main()

