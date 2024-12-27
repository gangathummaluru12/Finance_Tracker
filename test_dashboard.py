from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import unittest
import time
import imaplib
import email
import re
import unittest
from selenium.common.exceptions import UnexpectedAlertPresentException, NoAlertPresentException



class FinanceTrackerDashboardTest(unittest.TestCase):

    def setUp(self):
        # Set up Chrome WebDriver
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        self.driver.get("http://localhost/Finance_Tracker/login.html")  # Replace with the actual URL
        

    def test_dashboard_elements(self):

        driver = self.driver
        
         # Enter email and password
        driver.find_element(By.NAME, "email").send_keys("ganga99088@gmail.com")  # Use your test email
        driver.find_element(By.NAME, "password").send_keys("Ganga@123")  # Use your test password
        

        # Complete the login
        driver.find_element(By.XPATH, "//button[text()='Log In']").click()
        time.sleep(3)


        # Verify the "Current Balance" element
        current_balance = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.XPATH, "//h2[contains(text(), 'Current Balance:')]"))
        )
        print("Current Balance text:", current_balance.text)
        self.assertTrue(current_balance.is_displayed(), "Current Balance is not displayed.")

        # Verify the currency type dropdown
        currency_type = driver.find_element(By.ID, "type")
        currency_type.click()
        driver.find_element(By.XPATH, "//option[@value='USD']").click()  # Select "Income"
        self.assertTrue(currency_type.is_displayed(), "currency type dropdown is not displayed.")

        # Verify the date picker
        date_picker = driver.find_element(By.ID, "date")
        date_picker.send_keys("09-11-2024")  # Example date
        self.assertTrue(date_picker.is_displayed(), "Date picker is not displayed.")

         # Verify the category type dropdown
        category_type = driver.find_element(By.ID, "type")
        category_type.click()
        driver.find_element(By.XPATH, "//option[@value='food']").click()  # Select "Income"
        self.assertTrue(category_type.is_displayed(), "category type dropdown is not displayed.")

        # Verify the description input field
        description_input = driver.find_element(By.ID, "description")
        description_input.send_keys("Test Transaction")
        self.assertTrue(description_input.is_displayed(), "Description input is not displayed.")

        # Verify the amount input field
        amount_input = driver.find_element(By.ID, "amount")
        amount_input.send_keys("200")
        self.assertTrue(amount_input.is_displayed(), "Amount input is not displayed.")

        # Verify the transaction type dropdown
        transaction_type = driver.find_element(By.ID, "type")
        transaction_type.click()
        driver.find_element(By.XPATH, "//option[@value='expense']").click()  # Select "Income"
        self.assertTrue(transaction_type.is_displayed(), "Transaction type dropdown is not displayed.")

        # Verify the "Add Transaction" button
        add_transaction_button = driver.find_element(By.XPATH, "//button[text()='Add Transaction']")
        self.assertTrue(add_transaction_button.is_displayed(), "Add Transaction button is not displayed.")
        add_transaction_button.click()  # Click to add the transaction
        time.sleep(2)  # Allow time for the transaction to be added

    

        #scroll
        transaction_table = driver.find_element(By.ID, "transaction-table")
        driver.execute_script("arguments[0].scrollIntoView();", transaction_table)
        print("Scrolled to transaction table.")
        time.sleep(5)

        #  # Deleting the transaction
        # delete_button = driver.find_element(By.XPATH, "//button[text()='Delete']/following::tr[1]/td[6]/button)[1]")
        # delete_button.click()
        # time.sleep(2)

     
        # alert = driver.switch_to.alert
        # alert.accept()  # Click "OK" on the alert to confirm deletion
        # time.sleep(2)  # Wait for the transaction to be deleted

        # alert.accept()  # Click "OK" on the alert to confirm deletion
        # time.sleep(2)  # Wait for the transaction to be deleted

        # # Verify the transaction is removed
        # table_body = driver.find_element(By.XPATH, "//table[@id='transaction-table']/tbody")
        # self.assertNotIn("Test Transaction", table_body.text)
        # print("Transaction deleted successfully.")



    def tearDown(self):
        self.driver.quit()

if __name__ == "__main__":
    unittest.main()
