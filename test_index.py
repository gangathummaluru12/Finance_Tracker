from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import unittest

class FinanceTrackerHomePageTest(unittest.TestCase):

    def setUp(self):
        # Set up Chrome WebDriver
        self.driver = webdriver.Chrome()  # Ensure ChromeDriver is in PATH or specify the path directly
        self.driver.maximize_window()
        self.driver.get("http://localhost/Finance_Tracker/index.html")  # Update this URL to the actual page URL

    def test_page_elements(self):
        driver = self.driver
        
        # Verify the page title
        self.assertIn("Finance Tracker", driver.title)

        # Verify the "Log In" button is present and clickable
        login_button = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.XPATH, "//a[text()='Log In']"))
        )
        self.assertTrue(login_button.is_displayed(), "Log In button is not displayed.")
        self.assertTrue(login_button.is_enabled(), "Log In button is not clickable.")

        # Verify the "Sign Up" button is present and clickable
        sign_up_button = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.XPATH, "//a[text()='Sign Up']"))
        )
        self.assertTrue(sign_up_button.is_displayed(), "Sign Up button is not displayed.")
        self.assertTrue(sign_up_button.is_enabled(), "Sign Up button is not clickable.")

        # Verify the main heading text
        main_heading = driver.find_element(By.XPATH, "//h1[contains(text(), 'Take Control of Your Finances')]")
        self.assertTrue(main_heading.is_displayed(), "Main heading is not displayed.")
        self.assertEqual(main_heading.text, "Take Control of Your Finances")

        # Verify the subheading text
        subheading = driver.find_element(By.XPATH, "//p[contains(text(), 'Track your income and expenses effortlessly.')]")
        self.assertTrue(subheading.is_displayed(), "Subheading is not displayed.")
        self.assertIn("Track your income and expenses effortlessly", subheading.text)

        # Verify the "Sign Up Now" button is present and clickable
        sign_up_now_button = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.XPATH, "//a[text()='Sign Up Now']"))
        )
        self.assertTrue(sign_up_now_button.is_displayed(), "Sign Up Now button is not displayed.")
        self.assertTrue(sign_up_now_button.is_enabled(), "Sign Up Now button is not clickable.")

    def test_navigation_to_login(self):
        driver = self.driver
        
        # Click on the "Log In" button and verify navigation
        login_button = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable((By.XPATH, "//a[text()='Log In']"))
        )
        login_button.click()
        
        # Wait for login page elements to load and verify page URL or title
        WebDriverWait(driver, 10).until(EC.url_contains("/login"))
        self.assertIn("Login", driver.title)

    def test_navigation_to_signup(self):
        driver = self.driver
        
        # Click on the "Sign Up" button and verify navigation
        sign_up_button = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable((By.XPATH, "//a[text()='Sign Up']"))
        )
        sign_up_button.click()
        
        # Wait for sign-up page elements to load and verify page URL or title
        WebDriverWait(driver, 10).until(EC.url_contains("/signup"))
        self.assertIn("Sign Up", driver.title)

    def tearDown(self):
        self.driver.quit()

if __name__ == "__main__":
    unittest.main()
