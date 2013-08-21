# Re-Order Reminders for CS-Cart
-------
Reorder Reminders for CS-Cart allows users to be reminded when it is time to re-order a product.

## FEATURES

* Users can decide before adding a product to the cart if they would like to be reminded ever so often of reordering
* Users are notified of reordering every interval
* Admins can determine which products can be reordered and the suggested reorder interval
* Admins can determine how many emails to send to the user at the reorder interval before the reminder expires

For up-to-date installation and setup notes, visit the FAQ:
[http://lab.thesoftwarepeople.com/tracker/wiki/Cscart-ror:MainPage](http://lab.thesoftwarepeople.com/tracker/wiki/Cscart-ror:MainPage)


## GENERAL INSTALLATION NOTES

* Download from repository
* Unzip the zip file in the directory where CS-Cart runs
* If the zip creates a new directory called `tsp-reorder-reminders` you will need to run the install script, else you are done
* If `tsp-reorder-reminders` folder created by zip, Navigate to the folder. Update the $target_loc in the install.php and run its. Command: php install.php
* Open CS-Cart Administration Control Panel
* Navigate to Addons -> Manage Addons
* Find the "The Software People: Re-Order Reminders" addon and click "Install" (If you don't see it make sure "All Stores" is selected at the top of the screen)
* After Install, from the Addons listing click on Settings for "The Software People: Re-Order Reminders"
* Update The Re-Order Reminder settings
* Add a Store (new since 4.x) to each Option by editing each one of the options below in Products -> Options

## USING THE ADDON

The Re-Order Reminders module, upon install, adds product global options to the database and adds settings to products.

The Products->Global Options that are added include:

* Remind Me to Re-Order In?

Each product has settings that can be turned on if the admin wishes to sell a product that requires reorder. 

The Product Listing -> Addon tab includes:

* Default Reminder Choice

### Creating a Product with Reminders

In order to create a product that has reminders you will need to perform the following steps:

* Create the product and save (after save the Addons tab will be available to you.
* Navigate to the Options tab and add the Global options below to the product by clicking on "Add Global Option"  (Apply as Link).
** Add `Remind Me to Re-Order In?`.
* Navigate to the Addons tab and scroll to the section "The Software People: Re-Order Reminders".
* Enter in the default reminder interval for the product (ie Memberships default '1 Year', Products default example '3 Months')
* Save and close the product
* And thats it! Now you can allow your customers to purchase events.
* Note: Any variants changed for the Global option `Remind Me to Re-Order In?` will also show in the product's Addon `Default Reminder Choice` dropdown

### Creating a Cron Job to Send Reminders

After you have setup reminders on your products you will need to create a cron on your server to run the script.
Note you will need to copy your Store Access Key from Settings > Security Settings > Cron Password

The cron to run is:
  wget -q -O /dev/null http://www.yourstore.com/index.php?dispatch=notifications.cron&cron_password=YOURCRONPASS
  
Recommended run time:

  Every Day at 8:00 Hrs

Sample Cron:
  0 8 * * * wget -q -O /dev/null http://www.yourstore.com/index.php?dispatch=notifications.cron&cron_password=YOURCRONPASS



## REPORTING ISSUES

Thank you for downloading Re-Order Reminder for CS-Cart
If you find any issues, please report them in the issue tracker on our website:
[http://lab.thesoftwarepeople.com/tracker/cscart-ror](http://lab.thesoftwarepeople.com/tracker/cscart-ror)

## COPYRIGHT AND LICENSE

Copyright 2013 The Software People, LLC

Software is available under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License; additional terms may apply. See [http://creativecommons.org/licenses/by-nc-nd/3.0/](Terms of Use) for details.