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

    window.onload = function() {
        var chapters = document.getElementsByClassName("chapter");
        var i;
        for (i = 0; i < chapters.length; i++) {
            chapters[i].addEventListener("click", function () {
                this.classList.toggle("open");

                var guide = this.nextElementSibling;
                if (guide.style.display === "block") {
                    guide.style.display = "none";
                } else {
                    guide.style.display = "block";
                }
            });
        }
    }
</script><br/>
<link rel="stylesheet" href="CSS/guide.css">
<div id="disclaimers">
<h3>User Guide - What is Agile Development?</h3>
    <button class="chapter">Agile means reacting to change</button>
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
    Scrum  Also, when people were coming up with their new Agile processes back in the late-90’s and early 00’s, they
    also came up with a load of new terminology; sorry.</p>
    <p>Scrum is based around short development cycles (called Sprints) that are normally about two weeks long, although the
    first Sprint in a project may be a little longer to give you time to get set up.  Every Sprint should result in
    something functional- there may only be one function, but it must work.  A project will consist of multiple Sprints;
    the dates for these can be set in stone at the start of the project if necessary, but the things you’re going to do
    within each Sprint should not be!  The whole point is that plans will change as development progresses; ASDF will
    help you keep track of everything and share it with your team.</p>
    <p>The project needs a list of features that will be developed over the course of the project- this is not a full
    detailed product specification!  Typically each feature is presented as a ‘User Story’ and doesn’t need much detail
    at all; for example-</p>
    <ul>
        <li>“As a Manager, I want to see a summary of sales statistics by product category”</li>
        <li>“As a Customer Service Advisor, I need to log-in to the sales system with a user name and password”</li>
    </ul>
    <p>Each User Story also needs an Acceptance Criteria – the team will measure their progress against this criteria to
    determine whether or not they have achieved the requirement.  The list of user stories should be prioritised in
    discussion with the customer- priority starts with ‘1’ as the most-important, and you should not have functions
    ‘tied’ for position.  It’s ok if the list isn’t exhaustive, and the priority isn’t final either- you’ll come back
    to this list again after each Sprint.  Once the list is compiled and prioritised, it is known as the Product Backlog,
        and the User Stories are the Product Backlog Items or PBIs.</p>
    </div>

    <button class="chapter">Sprint Planning</button>
    <div class="guide">
    <p>So, you now have a Product Backlog with a list of everything your customer could think of, with the most important
    features at the top of the list.  It’s time to actually start the Sprint- which means just a little more planning!
    Every Sprint should start with a Sprint Planning Meeting (you don’t need the customer for this one).  The whole
    development team should look at the Product Backlog and agree for themselves on how many PBI’s they can reasonably
    accomplish during the next Sprint- remember to always start from the highest-priority and work down.</p>
    <p>For each PBI, the team should agree on sub-tasks that achieve the PBI functionality- these can be much more technical
    than the User Stories if you want to, and should be broken down to the lowest practical level.  If a sub-task seems
        like more than a day’s work, then it should be broken down into smaller chunks again; for example-</p>
    <ul>
        <li>Produce wireframe and mockup for login page </li>
        <li>Produce GUI for login page</li>
        <li>Design system database</li>
        <li>Write script to create database on server</li>
    </ul>
    <p>In addition, for each of the sub-tasks, the team should estimate how much effort is involved.  This could be something
    like the rough number of hours to complete, or it could be more abstract (for example using 1, 2, 4, 8 or 1, 2, 3,
    5, 8…  These Effort Units help the team pick an appropriate amount of work for the duration of the Sprint.  The list
    of sub-tasks is known as the Sprint Backlog, and the sub-tasks are naturally the Sprint Backlog Items (or SBIs).</p>
    </div>

    <button class="chapter">Development Phase</button>
    <div class="guide">
    <p>Once the SBI is ready, the Sprint has officially begun.  The team should tackle the tasks on the Sprint Backlog in
    whatever order makes the most sense for them, while still respecting the overall priorities of the PBIs.  The effort
    values assigned to the task are not proscriptive; if something takes a lot longer than estimated, it doesn’t mean
    that development should stop, but it may influence the effort the team assigns during the next Sprint Planning
    meeting.</p>
    <p>One of the foundations of Scrum (and where it gets its name) is the daily Scrum meeting.  This is where the whole
    team gets together every day for an informal meeting, and each member briefs the rest of the team on what they did
    yesterday, what they plan to do today, and whether they’ve run into any holdups along the way.  In theory there’s no
    superiority between team members, but in practice it’s useful to keep the meeting short if someone can coordinate;
    remember though that this is not the place to air grievances or discuss PBI priorities- keep the meeting short.  You
    don’t all need to be in the same place either; teleconferencing or online meetings are fine too.</p>
    <p>It’s very common for SBIs to be mapped out on a large whiteboard (or office wall) so that the whole team can see how
    things are progressing throughout the day; it’s worth noting that this is actually a technique ‘borrowed’ from lean
    manufacturing processes.  One of the core functions of ASDF is to provide an online Task Board, where each team member
    can update their progress in real-time to let the rest of the team know what’s happening.</p>
    </div>
    <button class="chapter">Post-Sprint</button>
    <div class="guide">
    <p>At the end of each Sprint, the team should hold a Sprint Review meeting.  This means looking at the Sprint Backlog
    and Product Backlog and agreeing which of the PBI’s were successfully completed according to the Acceptance Criteria.
    It is common to have unfinished tasks at the end of the Sprint- that’s not a failure, but it may impact what you
    choose to tackle on the next Sprint.  You might invite your customer to view a demonstration; you definitely want to
    update them on your progress.  Communication is a vital part of the Agile approach, and keeping your customer updated
    on progress means they can keep you updated on their requirements.</p>
    <p>The next step is for the development team to get together and discuss any non-project-specific issues from the last
    Sprint- for example, was there too much effort planned for the last Sprint, could they be working smarter, and so on.
    This is known as the Sprint Retrospective meeting, and is useful for morale and to keep the team working efficiently.
    The final stage after the Sprint is for the customer and a representative from the team to go over the Product Backlog
    again (last meeting- the Backlog Refinement meeting), to cross out any completed features and to make any changes
    or additions to the remaining tasks.  The customer may swap priorities around or come up with a completely new
    outrageous requirement- that’s fine, so long as they understand that adding things to the top of the list makes it
    less likely you’ll complete the things at the bottom.</p>
    <p>Once the Product Backlog has been updated, all that’s left is to take a break, recharge your batteries, and start
    planning the next Sprint.</p>
    </div>
</div>









<?php