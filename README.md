# OpenCart PayXpert Payment Module
### Version for Opencart >= 2.3

PLEASE NOTE: THIS VERSION IS NOT COMPATIBLE WITH OPENCART PRIOR TO 2.3.
This version only works with Opencart 2.3.x to 3.0.x

Also note that upgrading to a version >= 3.0 from a version prior to 3.0 will require to set the module settings again.
As an alternative the following SQL request could be run on the database:
UPDATE oc_setting SET code = 'payment_payxpert' WHERE code = 'payxpert';

The author of this plugin can NEVER be held responsible for this software.
There is no warranty what so ever. You accept this by using this software.

## Changelog
* 1.0 - Initial Release
* 1.1 - Add compatibility with 2.0.x, 2.1.x and 2.2.x
* 2.0 - Add compatibility with 2.3.x and 3.0.x. Drop compatibility with prior versions (use v1.1 of the module).

## Installation
1. Unzip.

2. Upload the 'admin' and 'catalog' folders to your opencart installation folder. No files will be overwritten, only added.

3. Go to the admin panel. Select "Extensions > Extensions" and then "Payments". Locate the PayXpert entry and select install from the Action column.

4. After installation, select Edit from the Action column.

5. Ensure module status is enabled.

6. Enter your 'Merchant Originator' (only digits) and your 'Merchant Password', you should have receive these information from PayXpert

7. Customize the other settings at will, notably the status of orders in case of payment success or failure.

8. Save your settings and you'll be ready to start using the module.

   
## Support
Please visit the PayXpert website (http://www.payxpert.com) for our support contact details.
