# test_login.py
import imaplib
import email
import re
import unittest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import UnexpectedAlertPresentException, NoAlertPresentException
import time
import email
import re


class FinanceTrackerLoginTest(unittest.TestCase):

    def setUp(self):
        # Set up Chrome WebDriver
        self.driver = webdriver.Chrome()  # Ensure ChromeDriver is in your PATH or specify the full path
        self.driver.maximize_window()
        self.driver.get("http://localhost/Finance_Tracker/login.html")  # Adjust to the correct URL of your login page

    def test_login_with_otp(self):
        driver = self.driver

        # Enter email and password
        driver.find_element(By.NAME, "email").send_keys("ganga99088@gmail.com")  # Use your test email
        driver.find_element(By.NAME, "password").send_keys("Ganga@123")  # Use your test password
       

        # Complete the login
        driver.find_element(By.XPATH, "//button[text()='Log In']").click()
        time.sleep(10)


    def tearDown(self):
        self.driver.quit()

if __name__ == "__main__":
    unittest.main()



