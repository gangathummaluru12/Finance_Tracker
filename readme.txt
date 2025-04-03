1. Install PHP, java for selenium
2. Install suitable webdriver for instance for chrome , a compatible version of chromedriver and make sure to put that webdriver excecutable file(.exe) inside the Finance_Tracker folder
3. install xaamp, composer and laravel (https://devrims.com/blog/how-to-install-laravel-localhost/) (perform till step 4)
4. copy and paste the code(Finance_Tracker) folder in <XAAMP intall dir>/htdocs/


  Access the applicaton using http://localhost:3000        
	for example:
		open the any browser and give : localhost/Fianance_Tracker/index.html  - for index page

						localhost/Fianance_Tracker/signup.html - for signup page
						localhost/Fianance_Tracker/login.html  - for login page
						localhost/Fianance_Tracker/dashboard.php - for dashboard page (to access to this dashboard, we must login to the page, that means database tables must be created in the system that intended to run the dashboard page, since it is coded in php)
 
To run Tests
    1. ⁠navigate to Finance_Tracker folder and open terminal with the folder path in it.
        run according to the test files for example
	to run test_login.py   - open terminal and run "python test_login.py"
	to run test_signup.py  - open terminal and run "python test_signup.py"
	to run test_index.py   - open terminal and run "python test_index.py"
	to run test_dashboard.py - open terminal and run "python test_dashboard.py"


So ,
Automation test scripts are inside the Financial_Tracker folder
	test_index.py
	test_signup.py
	test_login.py
	test_dashboard.py

