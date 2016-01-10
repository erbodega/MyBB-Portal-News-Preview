#Copyright Notice 

This plugin is written by me, bodega, and everything should be fine. I tested it several time over a new installation of MyBB 1.8.6 and haven't ever encountered a problem.

You can find more web development related content on my GitHub profile: erbodega.github.io

If you are a dev and want impove my plugin feel free to send me a pull request, if you code is good i will accept it.

Don't remove the copyright notice from this file (ReadMe.txt) and don't change the copyright information inside the plugin, respect my work.



#Portal News Preview 1.0 by bodega - ps4mod.net - erbodega.github.io

This plugin cut the news in the portal adding a Read More button, just like WordPress do. You can choose three way to cut your news, just choose what you prefer.

Before starting i suggest you to edit the templates of your portal in order to remove the avatar column, having a more blog-style portal that is really better.

The three way to cut the news are:

1. After the first image (wordpress like)
2. After a string
3. After N characters (It may show broken bbcode if the setted up number is in the middle of a bbcode tag, i know this and had already a solution, i just need the time to update the plugin)



#Installation

The installation process is very simply, just:

1. Upload the plugin file "portalnewspreview.php" to yourMyBBforum.com/inc/plugins
2. Activate and Install the plugin via the Admin Control Panel -> Plugins
3. Eventually you may want to change the default configuration. To access them jus go to Admin CP -> Setting -> Portal Settings: you will find the configuration at the bottom.


If you want to unistall this plugin just:
1. Go to Admin Control Panel -> Plugins
2. Click on Deactivate
3. Click on Uninstall
4. Remove the php file via ftp
[/list]



#Change Read More button style

This plugin will install a new css in the default theme, so if you want to edit the background color etc. just go to Admin CP -> Template & Style -> Themes then choose your theme.

You should see a new css file called readmore.css that is Inherited from MyBB Master Style, just edit it and save changes. Any changes you make will break the inheritance, and the stylesheet will be copied to your theme.



#Test the functionality           

If you want to test the plugin functionality, post this thread in one of your forum where the threads appear in the portal as a news:

Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. Testing thread. 

[img]http://community.mybb.com/images/logo.png[/img]

Text before the cut string that is: cut_me_here

This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal. This should not be visible on portal.
