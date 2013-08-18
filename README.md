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
* If the zip creates a new directory called `tsp-appointments` you will need to run the install script, else you are done
* If `tsp-reorder-reminders` folder created by zip, Navigate to the folder. Update the $target_loc in the install.php and run its. Command: php install.php
* Open CS-Cart Administration Control Panel
* Navigate to Addons -> Manage Addons
* Find the "The Software People: Re-Order Reminders" addon and click "Install" (If you don't see it make sure "All Stores" is selected at the top of the screen)
* After Install, from the Addons listing click on Settings for "The Software People: Re-Order Reminders"
* Update The Appointment settings

## USING THE ADDON

The Re-Order Reminders module, upon install, adds product global options to the database and adds settings to products.

The Products->Global Options that are added include:

* Reorder?
* Remind Me

Each product has settings that can be turned on if the admin wishes to sell a product that requires reorder. 

The Product Listing -> Addon tab includes:

* Product Can be Reordered?
* Reminder Interval
* Expire Reminder after how many email notifications?

### Creating a Product with Reminders

In order to create a product that has reminders you will need to perform the following steps:

* Create the product and save (after save the Addons tab will be available to you.
* Navigate to the Addons tab and scroll to the section "The Software People: Re-Order Reminders".
* Supply the reminders details.
* Save and close the product
* And thats it! Now you can allow your customers to purchase events.


## REPORTING ISSUES

Thank you for downloading Appointments for CS-Cart
If you find any issues, please report them in the issue tracker on our website:
[http://lab.thesoftwarepeople.com/tracker/cscart-ror](http://lab.thesoftwarepeople.com/tracker/cscart-ror)

## COPYRIGHT AND LICENSE

Copyright 2013 The Software People, LLC

Software is available under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License; additional terms may apply. See [http://creativecommons.org/licenses/by-nc-nd/3.0/](Terms of Use) for details.