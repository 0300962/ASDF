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
    document.title = "ASDF - Help Page";

    window.onload = function() {
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
    }



</script>
    <link rel="stylesheet" href="CSS/guide.css">
<br/>
<div id="disclaimers">
<h3>Help</h3>
    <h4>System Requirements</h4>
    <ul>
        <li>PHP version 5.6, or 7.2 tested</li>
        <li>MySQL database server</li>
        <li>HTML5-compliant browser with cookies enabled</li>
        <li>1920x1080 full-colour monitor recommended</li>
    </ul>
    For more information regarding Scrum, see xxx, or for Agile in general, why not try xxx.
</div><br/>
<div id="sections">
<div id="section">
    <h3>FAQs</h3>
    <button class="chapter">How do I use the system?</button>
    <div class="guide">
    Read the User Guide first, it should tell you all you need to know. But the very short version is: Add your features
    to the Backlog and prioritise them, then start a Sprint and add sub-tasks that will achieve the features. The sub-tasks
    will fill-in the front-page, and you can track them with the buttons on each one.</div>
    <button class="chapter">My team-mates can't login?</button>
    <div class="guide">
    <ul>
        <li>Login names are case-sensitive, and set by an Admin- you can't just register yourself.  If they don't have an
        account, ask an Admin user to make them one.</li>
        <li>Your browser has to accept cookies to be able to login.</li>
        <li>If a user has forgotten their password, an Admin user can change or reset it for them.</li>
    </ul>
    </div>
    <button class="chapter">Where have my SBIs gone?</button>
    <div class="guide">
    SBIs are deleted when a Sprint is 'Closed' by an Admin.  The performance of the Sprint, in terms of PBIs that were
    completed, is saved to produce the Project Burndown chart.</div>
    <button class="chapter">Why can't I pick solid black for my colour?</button>
    <div class="guide">
    Because it would make the Task Board and Chat pages unusable for the rest of your team.</div>
    <button class="chapter">Why can't I rearrange the Product Backlog?</button>
    <div class="guide">
    You cannot change the Product Backlog when a Sprint is in progress, or if you are not an Admin user.</div>
    <button class="chapter">I'm a new user and I can't get back to the Task Board?</button>
    <div class="guide">
    Click the ASDF logo in the upper-left of every page to return to the Task Board.</div>
    <button class="chapter">I need to add/remove a User from the system, or make someone else an Admin?</button>
    <div class="guide">
    Go to the User Administration function, under the Admin tab.</div>
    <button class="chapter">I've deleted/lost the password to the only Admin account in the system and now ASDF is unusable?</button>
    <div class="guide">
    That was careless, but there's still hope. Assuming you still have access to the PHP server, open the /ASDF/Scripts
    folder and delete 'connection.php', then attempt to access ASDF again.  You'll be taken to the Setup Mode again,
    where you can add a new System Admin account.  Don't worry about the error messages that ASDF generates, it's just
    upset that your database is already configured- the rest of the data and user accounts will be untouched.</div>
    <button class="chapter">The charts look odd, the dates don't make any sense?</button>
    <div class="guide">
    That is a known bug with Burndown charts, when there's not enough data to work with. Starting a Sprint will correct
    the Sprint charts, and finishing your first Sprint will correct the Project chart.</div>
    <button class="chapter">My team hates this new style of working?</button>
    <div class="guide">
    Well it's not for everyone, but if it's done right it can make your team more productive and efficient, improve the
    quality of deliverables, and even increase both job and customer satisfaction. Listen to the feedback; is it the
    whole team? Some people need a rigid structure, while others simply resist change.  If some element isn't working
    for your team, try ignoring it for a couple of weeks and see what you think. Maybe you don't need daily Scrum meetings,
    or maybe you want longer Sprints? There's no right or wrong way to do it.</div>
    <button class="chapter">How do I integrate ASDF with my AD server, I've a thousand programmers waiting to log-in?</button>
    <div class="guide">
    Nope. ASDF is built for small teams of developers, dipping their toes into Agile development.  You're free to use it
    for commercial or non-commercial projects, open or closed-source, etc. Do with it what you will.  But if you want to
    roll it out at scale, you're on your own.</div>
    <button class="chapter">Where's the Kanban board?</button>
    <div class="guide">
    ASDF uses a Task Board, you saw it when you logged-in.  It's a simplified version of a Kanban board, because ASDF is
    designed for development projects and not manufacturing (like Kanban is).  Each Kanban swim-lane is an SBI; the queue
    is the Sprint Backlog; there is no pulling-in work as it is set at the start of the Sprint, and there are no capacity
        limits on the different 'activities' (states on the Task Board).  ASDF only supports one project and one team at a time.</div>
    <button class="chapter">Something else?</button>
    <div class="guide">
    You're welcome to get in touch at xxx.</div>
</div>
</div>
<?php