<h1> ContentLEAD Article Importer for Joomla! </h1>
<h2>v0.6</h2>
<p><b> Please note that whatever version of this you grab, it's still in heavy development.  If you run into any issues, just contact whoever gave you this plugin.</b></p>

<h3> What does it do? </h3>
<p> It makes all your wildest dreams come true!  It also takes articles from Brafton/ContentLEAD/Castleford XML feeds and imports them into your Joomla! CMS.</p>

<h3> How does it work? </h3>
<p> It's easy friend!  Just install this zip folder as an extension on your Joomla! website.  From there, you should see it listed as "Brafton Article Importer"
under your components.  Go ahead and click on that to edit settings.</p>
<p> From the settings screen, you can enter the API key given to you which contains the data for your feed.  Plug that in, change other settings and hit "Submit".  
If you get a pleasant message informing you that your options have been saved, then you're ready for the next step. </p>

<h3> Setting up the Cron </h3>
<p> One of the, um, 'problems' with Joomla! is that there's no built in cron functionality.  But no fear, because we've come up with a solution for this. </p>
<p> Coupled within the component install, a system plugin should have installed called "Brafton Pseudocron Plugin."  This effectively simulates a cron job using only PHP. 
<a href="http://www.bitfolge.de/index.php?option=com_content&view=article&id=61%3Aphp&catid=38%3Aeigene&Itemid=59&limitstart=3">This is a pretty good website</a> if you're interested
in how it works.</p>
<p>In order to start your cron, go into the plugins menu and click on the Brafton pseudocron plugin.  Make sure to enable it, and you can set when your cron should run (we recommend every 3 hours/180 minutes)</p>
<p><b>Please note:</b> This feature is in it's early stages, so it's not as powerful as a real cron job, and you can only set your time in minutes.  Future releases will increase it's functionality.</p>

<h4> A note about pseudocron </h4>
<p> Try as we may, the only real way to create a cron job is through the server itself.  This is notably faster and doesn't require users to hit your page in order to trigger the importer.
Through the server is the preferred method of cron, and the pseudocron method should be used if there is no ssh/server access available.

<h3> Making your view </h3>
<p> The cool thing about MVC is probably the C part, the controller.  Since the controller can interpret different requests and where they came from, 
our component doubles as an importer and a Joomla! view. </p>
<p> When creating a new menu item, select the Article view from the list.  This should create a page that contains your blog posts and brief summaries of each.
Click an article to get it's full page!</p>
<p> More view types coming later! </p>

<h3> Troubleshooting </h3>
<h5> I tried using this component with Joomla 2.5, yet it's not working as intended.  This sucks!</h5>
<p> Well yes, that's going to happen right now.  Since Joomla! 1.6+ added this whole 'assets table' shennanigans, we have to rewrite several parts of this in order to account
for this major change.  Please see the 'backwards' branch of this project for further information. </p>
<h5> Everything imported great, but the pictures are HUGE.  What do I do? </h5>
<p> Please see <a href="#recommended-plugins">Recommended Plugins</a> for something that will help you :) </p>
<h5> I found a plugin that can have Joomla run on cron, can I use it? </h5>
<p> I'm certainly not going to stop you, but please note that this component has yet to be tested with 3rd party plugins.</p>
<h5> XYZ broke and I don't know what to do! </h5>
<p> If you're stuck, please contact whoever gave you the plugin.  If you downloaded it directly from here, I'm just assuming you work for ContentLEAD and you're a tech guy, so 
just ask me. </p>

<h3>Recommended Plugins</h3>
<ul>
<li><a href="http://extensions.joomla.org/extensions/photos-a-images/images/articles-images/9982">Joomla SmartResizer</a> - for those pictures that just won't fit</li>
</ul>

<h3>To Do</h3>
<p> This should really say "a lot", but I'll break it down here.</p>
<ul>
<li> Consolodate plugins to work across all native versions of Joomla.  Please see the backwards branch for details </li>
<li> Integrate SmartResizer for images </li>
</ul>
<p>If you're having trouble with something, please note that it is being actively worked on.</p>