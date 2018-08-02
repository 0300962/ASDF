# Quick-Start Guide

1. Identify the server(s) you are going to use.  It/they must support serving PHP files, and support a MySQL database.
 If your database server has a limited number of databases (i.e. you're using a free service somewhere) you can re-use 
 an existing database and ASDF will add it's tables to that.  **Make Sure** your existing database does not have any 
 tables with the following names: *logins, users, project, sprints, chat, pbis, sbis* otherwise ASDF will probably not work.
2. Make an account within the database management software for ASDF to use.  **Make Sure** the account has enough permission 
to create tables (and a database, if you're not reusing an existing one), and make a note of the details you used.
3. Download the latest version of the ASDF source files from this repository.
4. Upload or copy the files to the PHP server.  Make a note of the location of the 'index.php' file. **Do Not** change 
the file structure or ASDF will not work.
5. Using a web browser (Chrome recommended) access the 'index.php' file on your PHP server.  ASDF will now enter Setup Mode.
6. Setup Mode will walk you through the process of connecting to your database, setting a username and password, making accounts
for the rest of the team, and so on.  *Top tip - make a note of the usernames that your team will need.*  You can always
add more accounts later if you need to.
7. Once Setup Mode completes, you and your team can log in using the details you've defined.  Each person will be asked to
fill-in their details, before being shown to the User Guide to learn how to use ASDF.  It is **strongly recommended** to 
read through all of the User Guide before proceeding.


## FAQs

- Don't see Setup Mode? Check that your ASDF/Scripts folder does not have a file called 'connection.php' (this is created during
setup).  If it's present and you haven't already worked through Setup Mode, then delete the file and try again.
- Setup Mode fails to create the database?  Confirm the details that ASDF uses to access the database server, and try again. 
Confirm that the database server allows a new database to be created/confirm that your existing database does not have any
tables with the same names as ASDF (see above).  If all else fails, create a database manually from within your database 
management software, then use that username, password and database name within Setup Mode again.  
- User can't login? Login details are case-sensitive, check and try again.  Check login screen for warning about Cookies
being disabled.  If no-one is able to login, confirm that the account ASDF uses to access the database has sufficient permissions.
- Live updates aren't working? Check login screen for warning about SSE; live updates require that your browser supports
HTML's Server-Sent Events protocol. 