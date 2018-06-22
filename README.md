# ASDF - Agile Software Development Framework

ASDF is a free, open-source web application built to make it easy for developers to work together in an Agile environment.  

<=<= Work in Progress! Estimated Completion by September 2018 =>=>

Designed specifically for developers who are new to Agile, it features a guided setup mode and help pages to keep the team on the right track as they dip their keyboards into Agile software development.  Team members can log-in, update their latest progress on the Task Board, see how the project is progressing from Burndown charts, define user stories and prioritise the backlog, and so on.
ASDF does NOT include a lot of extra business features (accounting, timekeeping, version control, etc).  Chances are if you need that, you've already got a better version of it that I can provide by myself in three months.

# Need to Know
- ASDF is self-hosted, which means your data remains with you.
- ASDF is free, as in free beer and free speech.  You're also free to modify it to suit your needs, in line with the GNU license.
- It needs a PHP server and a MySQL database, so something like LAMP, MAMP or WAMP is ideal.  ASDF doesn't take up a lot of space.
- It's designed for modern web browsers - HTML5, CSS3 and JavaScript.  Sorry, but there are no plans for IE8 support at this time!
- ASDF is made for 1920x1080 screens; nobody wants to write software on a phone.  It's tested with keyboard and mouse interface; let me know how you get on with a touchscreen, if you try it.

# Installation
- Simply copy the latest version of the files into your PHP server's directory (they'll have their own instructions for this). 
Then access the index.php file from your web browser of choice (Chrome recommended).  
- On first launch, it will guide you through the setup of the system, including connecting to the database, adding users, setting up your project, and so on.
- From there, your users can access the Login page (index.php) and sign in with the names you've defined; they'll set their own passwords, profile information, and so on.

It's recommended for everyone to read through the Guide page within ASDF once they're logged-in.  This will explain a lot more about the process, and how to use the system to help manage a development project.

#Troubleshooting
- If you have problems with the setup mode, the most likely culprit is the user account on your MySQL database.  Make sure the MySQL database manager has a user account set up with full permissions; ASDF needs to create a new database, tables etc.
- If something else goes wrong and you need to re-enter the Setup mode, just delete the 'connection.php' file from the ASDF directory on your server and go back to the Login page.

## About the Developer
Hi!  I'm Brendan.  I'm currently a full-time student at the Robert Gordon University in Aberdeen (Scotland) and ASDF is going to be my final project.  As development goes along I'll also be completing a thesis in the background, hopefully leading to an MSc in IT with Network Management.  My background is in Electronic and Electrical Engineering, so I'm very used to the up-front requirements engineering phase; it's good for some things, but not for others.

I first got the idea for ASDF during a Software Project Engineering module at Uni; a small team with different experience levels, skills and timetables all had to work together using Scrum to develop an application.  It was a bit of a learning curve, but it made me appreciate the Agile process.  We used a combination of Office365 tools to try to keep things coordinated (OneNote, OneDrive and Excel), but it was always an uphill struggle.  

There's got to be a better way!  ...and that's where ASDF came from.
