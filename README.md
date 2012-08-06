<h1> ContentLEAD Article Importer for Joomla! </h1>
<h2>v0.5.5</h2>
<p><b> Please note that whatever version of this you grab, it's still in heavy development.  If you run into any issues, just contact whoever gave you this plugin.</b></p>

<h3> What does it do? </h3>
<p> It makes all your wildest dreams come true!  It also takes articles from Brafton/ContentLEAD/Castleford XML feeds and imports them into your Joomla! CMS.</p>

<h3> How does it work? </h3>
<p> It's easy friend!  Just install this zip folder as an extension on your Joomla! website.  From there, you should see it listed as "Brafton Article Importer"
under your components.  Go ahead and click on that to edit settings.</p>
<p> From the settings screen, you can enter the API key given to you which contains the data for your feed.  Plug that in, change other settings and hit "Submit".  
You're ready for the next step! </p>

<h3> Ready for cron? What does that mean? </h3>
<p> If you've entered a valid API key, the next screen should show you a message that says "Ready for cron".  One of the, um, 'problems' with Joomla! is that there
is no built in cron functionality, so any automation must be done through a script that's called through cron on your own server. 
See <a href="#to-do">To Do</a> for more details on what we're doing about this.</p>
<p> This script is provided to you in the repo.  Download joomlaCron.php (or variation of) and run your cron job on this script directly on your server.  Please note:
The joomlaCron.php file must be edited in order to work correctly, as it uses parallel callings of cURL in order to work.  Just change the first part of the URLs to
http://yourdomain/yourjoomlainstall/index.php?option=com_brafton2&task=loadArticles (don't forget to change the other call!)
If you're entire site is joomla, then you just need http://yourdomain/index.php?option=com_brafton2&task=loadArticles</p>
<p> If you want to just test, you can just call joomlaCron in your browser.  This should start the article imports! </p>
<h3> Making your view </h3>
<p> The cool thing about MVC is probably the C part, the controller.  Sinc the controller can interpret different requests and where they came from, 
our component doubles as an importer and a Joomla! view. </p>
<p> When creating a new menu item, select the Brafton2 view from the list.  This should create a page that contains your blog posts and breif summaries of each.
Click an article to get it's full page!</p>

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
<li> We need to integrate cron somehow.  Ideally, server-side cron is the best option as it's faster and more reliable, however server access is not always possible, so there needs to be a
solution to this.  We're analyzing plugins and seeing how it's done, so this will hopefully happen soon </li>
<li> Consolodate plugins to work across all native versions of Joomla.  Please see the backwards branch for details </li>
<li> Integrate SmartResizer for images </li>
</ul>
<p> These are the 3 huge points we're currently working on.  If you're having trouble with something, please note that it is being actively worked on.</p>