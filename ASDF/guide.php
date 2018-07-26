<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 13:00
 */
include 'header.php';
?>
<script> //Sets the navbar link and title to show which page you're on
    document.getElementById("guide").className += " active";
    document.title = "ASDF - User Guide";

    window.onload = function() { //Adds listeners to each chapter on the page
        var chapters = document.getElementsByClassName("chapter");
        var i;
        for (i = 0; i < chapters.length; i++) {
            chapters[i].addEventListener("click", function () {
                var chapters = document.getElementsByClassName("chapter");
                var i;
                for (i = 0; i < chapters.length; i++) {
                    chapters[i].nextElementSibling.style.display = "none";
                    chapters[i].classList.toggle("open", false);
                }
                this.nextElementSibling.style.display = "block";
                this.classList.toggle("open", true);
            });
        }
        //Clicks the first chapter as default
        document.getElementById("top").click();
        document.getElementById("button_intro").click();

    };

    //Changes guide sections
    function changer(choice) {
        var sections = document.getElementsByClassName("section");
        var i;
        var buttn = 'button_'+choice;
        //Hides all sections of the guide
        for (i = 0; i < sections.length; i++) {
            sections[i].style.display = "none";
        }
        document.getElementById("button_intro").className = "";
        document.getElementById("button_how_to").className = "";
        document.getElementById("button_page_by_page").className = "";
        //Unhides only the chosen one, highlights the button
        document.getElementById(choice).style.display = "block";
        document.getElementById(buttn).className += " active";
    }
</script><br/>
<link rel="stylesheet" href="CSS/guide.css">
<div id="sections">
    <button id='button_intro' onclick="changer('intro')">What is Agile?</button>
    <button id='button_how_to' onclick="changer('how_to')">How do I use ASDF?</button>
    <button id='button_page_by_page' onclick="changer('page_by_page')">Page Guide</button>
<div class="section" id="intro">
<h3>User Guide - What is Agile Development?</h3>
    <button class="chapter" id="top">Agile means reacting to change</button>
    <div class="guide">
    <p>Traditional development (also known as ‘waterfall’ because each stage flows into the next) starts with planning,
    followed by development, followed by testing and finally delivering the product to the customer.  This is fine for
    some projects- if you’re building a house you start on the ground, not the roof.  But what if the plan was wrong?
    What if the customer thought they wanted one thing, but actually needed something else?  What if it takes longer than
    you thought to develop, do you still have time to test everything?</p>
    <p>The answer to these problems is to not develop the whole product in one cycle.  By all means, plan, develop and
    test, but do it on a much shorter scale and limited scope.  This is the essence of being Agile; you let your customer
    see progress as it happens, so they can let you know you’re still on the right track.  If there are delays along the
    way, it may mean that lower-priority features are left by the wayside; but the things that are really important to
    the customer get developed first.  They may change their priorities once they’ve had a chance to test it out themselves,
    and that’s ok- it means they get what they need, and your team isn’t wasting their time.  Agile originally started
    as a Software Development thing, but has since been used by all sorts of development teams keen to improve their
    way of working.</p>
    </div>

    <button class="chapter">What is Scrum, and how does it work?</button>
    <div class="guide">
    <p>Scrum is just one of several different Agile processes, although it is the most widely-used for software development.
    It is important to understand at this stage that almost nobody sticks rigidly to a development process ‘rulebook’;
    most teams take bits and pieces from different processes (Scrum, Kanban, DSDM, Extreme Programming to name a few)
    and keep what works for their team specifically.  With that in mind, ASDF adopts the majority of the elements of
    Scrum with one or two small adjustments.  Also, when people were coming up with their new Agile processes back in
    the late-90’s and early 00’s, they also came up with a load of new <span class="term">terminology</span>; sorry.
    These terms are highlighted to help you remember them; you'll see them a lot using ASDF.</p>
    <p>Scrum is based around short development cycles (called <span class="term">Sprints</span>) that are normally
    about two weeks long, although the first Sprint in a project may be a little longer to give you time to get
    set up.  Every Sprint should result in something <b>functional</b>- there might only be one function, but it must work.
    A project will consist of multiple Sprints; the dates for these can be set in stone at the start of the project
    if necessary, but the things you’re going to do within each Sprint should not be!  The whole point is that plans will
    change as development progresses; ASDF will help you keep track of everything and share it with your team.</p>
    <p>The project needs a list of features that will be developed over the course of the project- this is not a full
    detailed product specification!  Typically each feature is presented as a ‘<span class="term">User Story</span>’ and
    doesn’t need much detail at all; for example-</p>
    <ul>
        <li>“As a Manager, I want to see a summary of sales statistics by product category”</li>
        <li>“As a Customer Service Advisor, I need to log-in to the sales system with a user name and password”</li>
    </ul>
    <p>Each User Story also needs an <span class="term">Acceptance Criteria</span> – the team will measure their progress
    against this criteria to determine whether or not they have achieved the requirement.  The list of user stories
    should be prioritised in discussion with the customer- priority starts with ‘1’ as the most-important, and you should
    not have functions ‘tied’ for position.  It’s ok if the list isn’t exhaustive, and the priority isn’t final either-
    you’ll come back to this list again after each Sprint.  Once the list is compiled and prioritised, it is known as the
    <span class="term">Product Backlog</span>, and the User Stories are the Product Backlog Items or <span class="term">
    PBIs</span>.</p>
    </div>

    <button class="chapter">Sprint Planning</button>
    <div class="guide">
    <p>So, you now have a Product Backlog with a list of everything your customer could think of, with the most important
    features at the top of the list.  It’s time to actually start the Sprint- which means just a little more planning!
    Every Sprint should start with a <span class="term">Sprint Planning Meeting</span> (you don’t need the customer
    for this one).  The whole development team should look at the Product Backlog and agree for themselves on how many
    PBI’s they can reasonably accomplish during the next Sprint- remember to always start from the highest-priority and
    work down.</p>
    <p>For each PBI, the team should agree on sub-tasks that achieve the PBI functionality- these can be much more technical
    than the User Stories if you want to, and should be broken down to the lowest practical level.  If a sub-task seems
        like more than a day’s work, then it should be broken down into smaller chunks again; for example-</p>
    <ul>
        <li>"Produce wireframe and mockup for login page" </li>
        <li>"Produce GUI for login page"</li>
        <li>"Design system database"</li>
        <li>"Write script to create database on server"</li>
    </ul>
    <p>In addition, for each of the sub-tasks, the team should estimate how much effort is involved.  This could be something
    like the rough number of hours to complete, or it could be more abstract (for example using 1, 2, 4, 8 or 1, 2, 3,
    5, 8…)  These <span class="term">Effort Units</span> help the team pick an appropriate amount of work for the duration
    of the Sprint.  The list of sub-tasks is known as the <span class="term">Sprint Backlog</span>, and the sub-tasks
    are naturally the Sprint Backlog Items (or <span class="term">SBIs</span>).</p>
    </div>

    <button class="chapter">Development Phase</button>
    <div class="guide">
    <p>Once the Sprint Backlog is ready, the Sprint has officially begun.  The team should tackle the tasks on the Sprint
    Backlog in whatever order makes the most sense for them, while still respecting the overall priorities of the PBIs.
    The effort values assigned to the task are not proscriptive; if something takes a lot longer than estimated, it doesn’t
    mean that development should stop, but it may influence the effort the team assigns during the next Sprint Planning
    meeting.</p>
    <p>One of the foundations of Scrum (and where it gets its name) is the daily Scrum meeting.  This is where the whole
    team gets together every day for an informal meeting, and each member briefs the rest of the team on what they did
    yesterday, what they plan to do today, and whether they’ve run into any holdups along the way.  In theory there’s no
    superiority between team members, but in practice it’s useful to keep the meeting short if someone can coordinate.
    Remember though that this is not the place to air grievances or discuss PBI priorities- keep the meeting <b>short</b>.
    You don’t all need to be in the same place either; teleconferencing or online meetings are fine too.</p>
    <p>It’s very common for SBIs to be mapped out on a large whiteboard (or office wall) so that the whole team can see how
    things are progressing throughout the day; it’s worth noting that this is actually a technique ‘borrowed’ from lean
    manufacturing processes.  One of the core functions of ASDF is to provide an online Task Board, where each team member
    can update their progress in real-time to let the rest of the team know what’s happening.  At any time, the team can
    see the overall progress of development using <span class="term">Burndown charts</span>; these graph the amount of
    SBIs and effort left to complete during a Sprint, as well as the state of the Project over multiple Sprints.</p>
    </div>
    <button class="chapter">Post-Sprint</button>
    <div class="guide">
    <p>At the end of each Sprint, the team should hold a <span class="term">Sprint Review</span> meeting.  This means
    looking at the Sprint Backlog and Product Backlog and agreeing which of the PBI’s were successfully completed according
    to the Acceptance Criteria. It is common to have unfinished tasks at the end of the Sprint- that’s not a failure, but
    it may impact what you choose to tackle on the next Sprint.  You might invite your customer to view a demonstration;
    you definitely want to update them on your progress.  Communication is a vital part of the Agile approach, and keeping
    your customer updated on progress means they can keep you updated on their requirements.</p>
    <p>The next step is for the development team to get together and discuss any non-project-specific issues from the last
    Sprint- for example, was there too much effort planned for the last Sprint, could they be working smarter, and so on.
    This is known as the <span class="term">Sprint Retrospective</span> meeting, and is useful for morale and to keep the
    team working efficiently.</p>
    <p>The final stage after the Sprint is for the customer and a representative from the team to go over the Product Backlog
    again (last meeting- the <span class="term">Backlog Refinement</span> meeting), to cross out any completed features
    and to make any changes or additions to the remaining tasks.  The customer may swap priorities around or come up with
    a completely new outrageous requirement- that’s fine, so long as they understand that adding things to the top of the
    list makes it less likely you’ll complete the things at the bottom.</p>
    <p>Once the Product Backlog has been updated, all that’s left is to take a break, recharge your batteries, and start
    planning the next Sprint.</p>
    </div>
</div>
<div class="section" id="how_to">
    <h3>How do I use ASDF?</h3>
    If you’ve made it this far then you’re doing something right!<br/>
    ASDF is designed to help with using the Scrum process.  It doesn’t replace your existing messaging, document control,
    CRM system or any of that.  What it does do is provide a Product Backlog, Sprint Backlog and Task Board for the team,
    along with a number of useful functions such as Burndown Charts, profiles and a message board.  There are basically
    two states to the system; at any point during the project, you will either have a Sprint in-progress or not.
    <?php
    if ($_SESSION['status'] == '1') {
        echo "<div class='admin'>As an Admin user, you will see additional guidance below about using the system.</div>";
    }
    ?>
    <button class="chapter">Before/After Sprint</button>
    <div class="guide">
        <?php
        if ($_SESSION['status'] == '1') {
        ?>
            <div class="admin">
                <h3>As an Admin:-</h3>
                <p>At the start of a new project or between each Sprint, the majority of work is performed through the Backlog
                page.  You will have access to edit the User Stories and Acceptance Criteria of existing PBIs, move them
                up and down the priority list, and add new PBIs via the form at the bottom of the page.  Note that if you don't
                specify a priority for a new PBI, it will be given a value of 50 initially; whenever you move any PBI up or
                down, all PBIs are re-numbered so there's no ties and no gaps in the rankings. </p>
            </div>
        <?php
        }
        ?>
        If you’re not in the middle of a Sprint, the Task Board will be blank and the Product Backlog will be unlocked,
        allowing Admin users to change the priorities or add new PBIs.  Under the Performance tab, all users can see the
        <span class="term">Project Burndown</span> chart- this shows the overall progress for the project, in terms of
        how many PBI’s are left to complete after each Sprint.
    </div>
    <button class="chapter">Starting a Sprint (Sprint Planning)</button>
    <div class="guide">
        <?php
            if ($_SESSION['status'] == '1') {
                ?>
                <div class="admin">
                    <h3>As an Admin:-</h3>
                    <p>When you're ready to start a Sprint, hold your Sprint Planning Meeting with the team.  At the meeting, one admin
                        user should go into the Admin tab of ASDF, and then click the Sprint Management button.  You will see a form
                        to set the end-date of the Sprint, and a list of your PBIs in order of priority.  Pick a date, and discuss
                        with the team before checking the boxes to select which PBIs you will tackle- start from the top of the list
                        and work down!  Two weeks is a reasonable time for a Sprint, you may want slightly longer for the first Sprint
                        in a project though.  Press the 'Next' button once the team is in agreement.</p>
                    <p>The next step is to define the SBIs for each PBI that you selected.  You will see several rows on the page,
                        these will make sense as you add SBIs. Hover-over the 'PBI' button to view the User Story for the PBI;
                        hover-over the 'Add a new SBI' button and you will be able to enter the details for a new SBI, associated
                        with that PBI.  The SBIs should be small, distinct tasks; they shouldn't be expected to take more than a day
                        to complete (if so, break them up into smaller pieces).  Remember that the 'Effort' indicator is a guideline
                        of how complex the task is likely to be, not a time limit.</p>
                    <p>Pressing the 'Save' button will add the SBI and update the page.  Keep adding SBIs to the PBIs until your
                        team is satisfied that all the tasks required to meet the PBI requirements are covered.  If your team is
                        working remotely, they can watch the Task Board page within ASDF to see the SBIs get added in real-time.
                        If you make a mistake or something crops up during the Sprint, you (as an Admin) can always edit or add more
                        SBIs from the Task Board later.</p>
                </div>
                <?php
            }
        ?>
        <p>At the start of each Sprint, the team should hold a Sprint Planning Meeting to set out what you're hoping to
            achieve in the next Sprint.  You should look at the Product Backlog page to see what the top priorities for
            the project are at the moment; these may well have changed since the last Sprint.  As a team, set the
            end-date for the next Sprint, and then decide on how many items from the top of the list you're going to
            tackle.  An Admin user will enter these into ASDF in preparation for the next step. </p>
        <p>Once you have agreed on which PBIs to tackle, the team needs to define how they're going to go about doing
            this.  The team should identify the tasks or steps necessary (known as SBIs, or Sprint Backlog Items) to
            meet the requirements of the PBI, and your Admin user will enter these onto the Task Board so you can all
            see how they stack up.  These should be small, separate tasks, not requiring more than a day to complete
            each.  You'll also need to come up with a number to indicate the level of effort required for the task- in
            other words, how complex you imagine it being. </p>
        As an example, say your PBI is as follows:<br/>
        <i>"As a user of the website, I want to be able to log-in with a username and password"</i><br/>
        Your SBIs for that PBI may include:<br/>
        <ul>
            <li>"Design the database for login information, effort: 1" </li>
            <li>"Implement the logins database, effort: 2" </li>
            <li>"Produce mockup of login page, effort: 1" </li>
            <li>"Produce HTML for login page, effort :4" </li>
            <li>...and so on. </li>
        </ul>
        <p>There's no limit to the number of SBIs you can have, but they all have to be completed by the end of the
            Sprint if you want the PBI to be marked as 'done'.  SBIs don't have to be completed in order, but it's useful
            where there are prerequisites (for example, designing something before building it) to enter these in order.
            SBIs can be added at a later date by an Admin if you realise there's something important that's been missed. </p>
        <p>You'll see on the Task Board that the SBIs are grouped by the PBI that they belong to.  Hovering-over the
            'PBI No x' button will let you see the details of the PBI, including the acceptance criteria for it.  Once
            the team is happy with the SBIs, the Sprint can begin. </p>
    </div>
    <button class="chapter">During Sprint (Development Phase) </button>
    <div class="guide">
        <?php
        if ($_SESSION['status'] == '1') {
            ?>
            <div class="admin">
                <h3>As an Admin:-</h3>
                <p>During a Sprint you're able to edit the existing SBIs or add new ones to the Task Board, using the buttons on
                    the left-hand side.  You can also change the end-date for the Sprint if you want to, by going into the Admin
                    tab and clicking on 'Sprint Management', then 'Change End-date'. </p>
                <p>If you need to manage your users, you can go to the 'User Administration' section.  Here you'll see a list of
                    all the accounts on the system (even ones who haven't logged-in for the first time and set their names).
                    Simply click the buttons to make or remove an Admin or delete the user account altogether.  Resetting an
                    account password will mean the next time the user logs in, they will be prompted for a new password; note
                    that this leaves the account vulnerable to attack until the user has logged-in again.  A more secure
                    alternative is to access their User Profile (from the Users tab), and use the 'Change Password' option to
                    set it on their behalf to a known value.  They can then login with this temporary password and change it
                    themselves from their Profile page. </p>
            </div>
            <?php
        }
        ?>
        <p>During a Sprint, the header will indicate the remaining time and the Task Board will show the SBIs and their
            current status, so everyone can see what they need to do.  The Product Backlog is locked in its current
            order, so the priorities and items on the list are fixed.  Users can view the Sprint Burndown chart under
            the performance tab- this compares the number of SBIs closed-out by the team each day against the ‘ideal’
            Sprint performance. </p>
        Each day in a Sprint should start with a short, informal meeting (the Scrum meeting).  Each team member has
        three things to say during the Scrum meeting:
        <ol>
            <li>What did they accomplish yesterday? </li>
            <li>What are they going to work on today? </li>
            <li>Is there anything stopping their progress (such as waiting on another task to be completed)? </li>
        </ol>
        <p>The Scrum Meeting is NOT the place for technical discussion, arguments about features or anything else like
            that.  It should take a matter of minutes, and doesn't require minutes to be written; the Scrum meeting can
            be held remotely, or staggered across different time-zones if required. </p>
        <p>During the day, team members should access the Task Board (by logging-in or clicking the ASDF logo at the top
            of any other page) and update the status of the SBIs they're working on.  Clicking the 'Progress' button on
            the SBI will move it forward a stage ('Not Started', 'In Progress', 'Testing', and 'Done').  The SBI will be
            painted with your chosen colour, and the rest of the team will see it update automatically on their own Task
            Boards.  If you accidentally progress the wrong task, or if you need to move a task back again for whatever
            reason, just push the 'Revert' button to move it back a step.  Note that the task will retain the colour of
            whoever progressed it into the current state.  Note also your team may have their own definitions, for
            example 'Testing' may be used to represent 'Ready to be tested'.  These should be discussed at the outset of
            the project. </p>
        <p>The progress of the Sprint is measured on the Burndown charts, accessed through the 'Performance' tab at the
            top of the page.  The Sprint Burndown charts plot the remaining SBIs and effort over time, as the Sprint
            progresses.  The Project Burndown chart shows the remaining PBIs after each Sprint, and so is of little
            interest until at least the second Sprint of a project. </p>
        <p>ASDF also features a 'Chat' page, which is meant as a noticeboard for you to leave short notes for yourself
            or the rest of the team- for example to help remember a source of inspiration, an address or contact details
            for a supplier, and so on.  Just enter your message and hit 'Post', and you'll be on top of the list. </p>
    </div>
    <button class="chapter">When the Sprint is Finished (Post-Sprint)</button>
    <div class="guide">
        <p>Once you reach the end of a Sprint, the team should hold a Sprint Review Meeting.  This is where you can
            demonstrate the progress of the team and agree on which PBIs successfully meet their acceptance criteria,
            and so can be considered 'done'. </p>
        <?php
        if ($_SESSION['status'] == '1') {
        ?>
        <div class="admin">
            <h3>As an Admin:-</h3>
            <p>The Sprint will not automatically close when it reaches the pre-defined end-date.  This is to allow for
                weekends, scheduling issues and so on.  At the Sprint Review meeting, the team should review the Task Board,
                and agree on which PBI's now meet their acceptance criteria.  If there have been any last-minute changes to
                SBI status, these should be updated accordingly.  If a PBI is agreed to have been completed, then ensure all
                of the SBIs for that PBI are marked as 'Done'.  If a PBI has not been completed, then ensure at least one of
                the SBIs for that PBI is not marked as 'Done'. </p>
            <p>Once you are in agreement, go back into the Sprint Management page (under the Admin tab), where you will have
                the option to 'Stop Current Sprint'.  If you confirm that you wish to proceed, any PBIs where all the SBIs
                were completed will be closed by ASDF.  The SBI data will be deleted, and the total PBIs will be updated in
                the Project Burndown chart.  The system will then direct you to the Product Backlog, which has 'unlocked'
                for editing again. </p>
            <p>The next stage is to review the Product Backlog with your customer as part of the Backlog Refinement meeting.
                Following the progress of the last Sprint, they may have changed their priorities or identified some new
                requirements that they'd like to see for the product.  Update the Product Backlog as necessary to reflect
                this (your Team Members can also view the Backlog changes in real-time, if they're not part of the Customer
                meeting). </p>
        </div>
        <?php
            }
        ?>
        <p>The team will hold a Sprint Review meeting, where you'll go through the Task Board and demonstrate and discuss
            which PBIs have been completed or not.  An Admin will formally close the Sprint within ASDF, which wipes the
            Task Board clear again ready for the next one, and updates the Project Burndown chart with the team's
            progress. </p>
        <p>Some team members will hold a Backlog Refinement meeting with the customer, where they will discuss the
            progress and remaining PBIs.  It is likely that new PBIs will be added to the list and priorities will
            change- this is a good thing, it's the whole point of the Agile process!   </p>
        <p>In addition, the whole team should meet to discuss their personal experiences and thoughts about the last
            Sprint.  Was the workload appropriate, did the division of labour make sense, was everyone pulling their
            weight, does the office need a new coffee machine, etc.  This is known as the Sprint Retrospective meeting,
            and it's important to have a proper break in the cycle to consider how it's going.  Scrum can sometimes get
            a bad reputation as being a tool for management to impose more working deadlines on their staff; but in
            actual fact (when used properly) it allows the staff decide for themselves how much they can reasonably do
            without burning out. </p>
        <p>If your project is finished or you just want a fresh start, under the Admin tab you'll find a couple of
            System Functions.  'Backup Project Data' will save all of the PBIs, previous Sprints, and any SBIs (if
            you're in the middle of a Sprint) to a .csv file for safekeeping.  Alternatively, 'Erase Everything' will
            delete everything except for the User accounts, leaving the system blank and ready for the next project. </p>
    </div>
</div>
<div class="section" id="page_by_page">
    <h3>Page-by-page Guide to ASDF</h3>
    A quick overview on what the different parts of ASDF do.
    <button class="chapter">General</button>
    <div class="guide">
        <p>When you log-in to the system for the first time, you are required to set a password (minimum 8 characters,
        case-sensitive).  The next time you log-in, you’ll set up your profile information (see below). If you want
        to log-out from ASDF, mouse-over your name in the upper-right of the page and click the link.</p>
        <p>Across the system, wherever you see something like
        <i class='material-icons' style="color: white; background: linear-gradient(to bottom right, #b92f2f 48%, #2fb9b9 52%);
         border-radius: 5px; padding: 3px;">settings</i>
         or
        <span style="color:white; background: linear-gradient(to bottom right, #b92f2f 48%, #2fb9b9 52%);
         border-radius: 5px; padding: 5px;">PBI No 1</span>
        it’s something you can interact with, either by clicking on it or hovering over with the mouse cursor.  The first
        icon is the Edit button, and clicking it will allow you to change the item of data it's associated with.  Normal
        users can only edit their own profile pages by default, although Admin users can do a lot more.  The second icon
        is a button, link or popup that does different things depending on where you are.<br/>
         </p>
    </div>
    <button class="chapter">Task Board</button>
    <div class="guide">
        <p>The Task Board is the home-page of the system (accessible from the ASDF logo in the upper-left).  Each PBI
            that was selected for the Sprint is displayed along with its SBIs.  Mousing-over the PBI Number displays the
            User Story and Acceptance Criteria for that PBI.  Clicking the Progress or Revert buttons on an SBI will move
            the SBI to the next or previous state (and column) respectively.  The Task Board automatically updates
            whenever someone makes a change, so you’ll always see the latest status.   </p>
        <p>The colours of the SBIs show who progressed that SBI to that stage; so if you mark an SBI as ‘In Progress’,
            it will display your chosen colour. </p>
        <p>If you are an Admin user, you will see an Edit button () alongside each SBI, allowing you to make changes to
            the description and effort for that SBI. </p>
    </div>
    <button class="chapter">Profile Page</button>
    <div class="guide">
        <p>The Profile Pages are accessed from the drop-down menu in the navigation bar.  These are meant for
            team-members to share useful details with the rest of the team- for example which timezone they work in,
            what is their area of expertise, and so on.  If you’re viewing your own profile page (or if you’re an Admin
            user), you’ll also see an Edit button- this will allow you to change your details and pick a new colour if
            you want to. </p>
    </div>
    <button class="chapter">Product Backlog</button>
    <div class="guide">
        <p>The Product Backlog displays an ordered-list of the PBI’s; optionally including the PBI’s already completed.
            If you are an Admin user, and there’s not a Sprint going on at the time, you’ll see buttons to change the
            priorities of the PBI’s and edit their User Stories or Acceptance Criteria. </p>
        <p>You’ll also see a form at the bottom of the page to add new PBI’s; if you want to specify where on the list
            to put it you can, otherwise it’ll get a default value (50).  Note that if you do specify a priority, it may
            duplicate an existing position, so you should move the original one down a position to keep the priorities
            unique. </p>
        <p>The Product Backlog automatically updates, so the whole team can view changes in real-time during the Backlog
            Refinement meeting (for example). </p>
    </div>
    <button class="chapter">Project Performance</button>
    <div class="guide">
        <p>The Performance tab leads to two chart pages.  The Project Burndown chart shows the overall progress of the
            project across multiple Sprints, displayed as a bar chart of total outstanding PBI’s at the end of each
            Sprint.  There’s a trend line showing the long-term performance of the project. </p>
        <p>The Sprint Burndown page is actually two charts, accessed via the buttons at the top of the page.  The
            default chart shows a track of the outstanding SBI’s over time, with a trend line in blue and a target (in
            red) assuming that all SBI’s will be completed by the end of the Sprint.  The alternative Sprint Burndown
            chart presents the outstanding amount of effort (rather than SBI’s) over time, to account for complex tasks
            skewing the regular chart. </p>
    </div>
    <button class="chapter">Guide</button>
    <div class="guide">
        <p>That's this page!</p>
    </div>
    <button class="chapter">Chat</button>
    <div class="guide">
        <p>All users can post messages to the Chat, visible to all other users.  The messages are colour-coded with the
            user-selected colour, just like the SBI’s on the Task Board.  This is meant to be a notice-board more than
            anything else, so the messages are short (180 characters) and simple. </p>
    </div>
    <button class="chapter">Admin</button>
    <div class="guide">
        <p>If you are an Admin user, there are a number of extra functions available to you in the Admin menu, in
            addition to the Editing abilities discussed above.   </p>
        Sprint Management

        User Admin

        Erase Chat Logs

        Setup Mode

        Backup

        Erase
    </div>
</div>
</div>