# QuackHack-2016
The first collegiate gaming hackathon in US/Canada, hosted by University of Oregon & Major League Hacking; January 15-17, 2016

<h3>Counter Strike : Global Offensive Forecast</h3>
![alt tag](https://github.com/DylanSecreast/QuackHack-2016/blob/master/Working%20Screenshots/csgo_forecast.gif)

_**What is CS:GO Forecast?**_ CS:GO Forecastis a web platform dedicated to evaluating the performance of eSports professionals and their careers playing Valve's blockbuster PC game, Counter Strike : Global Offensive.

_**How does it work?**_ Historical and live statistics are pulled directly from the source of top professionals as they play. 
This data is aggregated, analyzed, and published as a roster of a 5-person fantasy draft.

_**More tech-talk please**_ Forecast uses PHP to pull all relevant data directly from a pool of top-caliber professionals using Steam's Web App API. 
This data is then piped into a MySQL database for analytic processing and represented in a user friendly way that could make for a potentially successful fantasy draft.
In addition to PHP and MySQL, Forecast was made with the use of HTML5, CSS3, BootStrap 3, JavaScript, and JSON - all in a local Apache 2 environment.

_**Why even bother?**_ It's our goal with Forecast to present unbiased ironclad data to give fans all over the world a fighting chance to capitalize on a $465 million thriving market in eSports. 
All content on CS:GO Forecast is for educational purposes and there is no affiliation with Valve.

<h4>Installation Instructions</h4>

1. [Download](https://www.apachefriends.org/index.html) and install XAMPP
2. Start localhost Apache server via XAMPP
3. Start localhost MySQL via XAMPP
4. Navigate to `http://localhost/phpmyadmin` to open phpMyAdmin
5. Create a new database named `quackhack` with `utf8_general_ci` collation
6. Navigate to `http://localhost:63342/QuackHack/index.php` to set up the database table
7. Run `http://localhost:63342/QuackHack/index.php`
