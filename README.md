# Basic Personal Blog

This is a basic template for a blogging site.

## Setup

After cloning this repository to the document root of your server, open /lib/.env.
The important settings that must be changed are the BASE_URL (and BASE_URL_TESTING if applicable), remember to set the ENV to 'production' for live deployment, and the database config.
Additionally to this you should change the SITE_NAME and COOKIE_NAME, you can adjust the COOKIE_TIMEOUT too.

The search funtion is not yet active, so no need to change SEARCH (unless you make it active).

The authentications settings consists of REGISTRATION_LOCKED, for a blog this should be left as 'true', CAPTCHA to enable/disable the CAPTCHA, MAX_LOGIN_ATTEMPTS to set the number of failed logins allowed, and LOGIN_ATTEMPT_PERIOD for the period before you can attempt login again.

After editing the .env file you need to use the content of /lib/blog.sql to set up the database.

To change the welcome message, edit the /lib/templates/welcome.html template.

## Authentication
To log in you need to navigate to [IP or FQDN]/auth/login[.php], the default username is admin and the default password is AdminPassword123$, you must remember to change these.


Once you are all set up, just navigate to your domain and blog!

## Note on translations
I do not speak any other languge, the translations are from online translators, hopefully there are no errors.
I am currently in the process of translating the authentication pages.

## Future
Currently there is no way (other than using the database) to edit posts, this is on its way. I hope over time I will be able to make this more than a basic set up.