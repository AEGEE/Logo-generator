# Logo-generator 2.x
Logo generator for AEGEE locals created by Maurits Korse. Version 2.x is now being hosted on Zeus.

## Framework
The logo-generation tool is based on CodeIgniter 3.x (https://www.codeigniter.com). The framework is situated in the `system` folder and should not be adjusted. For help on the CodeIgniter framework see its [user guide](https://www.codeigniter.com/userguide3).
The framework works along the MVC model (model-view-controller).
The templating engine is [Smarty](http://www.smarty.net) 3.x. 

## Application
The application can be found in the `application` folder. 
The pages are controlled through the controller classes (`application/controllers`) and are requested according to the routes configured in `application/config/routes.php`.
The views that are finally shown are located in the `application/views` folder. They are parsed by the Smarty templating engine.

## Logo Generator
The actual logo generator script can be found in *application/models/Logomodel.php*. The script is requested asynchronously by the server to enable the progress bar on the interface. The settings of the logo generator script can be found in `application/config/logogeneration.php`. Only adjust if you know what you are doing. Some of these values are based on the source files on which the logo's are based. The source SVG files for the logo's can be found in `application/resources/`.

### What does the script do?
It gets the requested info from the form (local, subtext or not, image formats, image sizes and logo colours), it then prepares the texts according to the determineFont algorythm. After this it creates the localized SVG source files for this request.
If the user requested it, the localized SVG source files are converted into other image formats (png, jpeg, pdf, eps). Finally the created files are zipped and a link of the zip file is presented to the user.

### DetermineFont Algorythm
To make the text fit *determineFont'()* walks through an algorythm that varies in font size and font family.
It checks if the maximum used font-size fits within the available space. If not it continues at each next step until the text fits:
* It reduces the font-size by maximum 10%.
* It tries to place the text over two lines in the best possible way with the original font-size.
* It reduces the font-size by maximum 15%.
* It tries to place the text over two lines and reduce the text by maximum 10%.
* It will reduce the letter-spacing by a few relative points.
* It will try another, more condensed, font and continues with this font at step 2.

## Change Log
**Version 2.0.2** - 2016-09-02
* \[BUG] PHP Variable type error when reducing font size.
* \[CODE] Refactoring and orgnanization.
* \[DOCS] Improved internal documentation.

**Version 2.0.1** - 2016-08-05
* \[BUG] Name of local only appears in 1 logo per download.

**Version 2.0** - 2016-08-03
After a long time the logo-generator was moved from a personal server to AEGEE's Zeus server. Thanks to Wim for his help on this. The changelog below is a small summary of all the improvements
* \[CORE] Updated to latest CodeIgniter 3.x
* \[BUG] Fixed PDF and EPS functionality
* Linked to Zeus' local database
* Improved progress bar feedback and options
* Improved UI
* Improved local sizeing and positioning algorithm
* Added statistics

**Version 1.x** - 2013-10-08
* First release of the logo generation tool.

## To do
* Add method to reduce letter spacing within font fitting algorythm
* Further improving internal documentation

## Debugging
If you want to debug the script set the mode to development and turn on logging.
In *index.php* set `define('ENVIRONMENT', 'production');`
In *config.php* set `$config['log_threshold'] = 4`
The log messages can be found through ftp in `application/logs`, also within the MySQL table `generator` logs per logo generation request can be found in the `messageLog` column.
PHP error logs can also be viewed within Zeus useradmin interface.

## System Requirements
* PHP 5.3+
* Imagick and PHP-imagick lib
* Inkscape
